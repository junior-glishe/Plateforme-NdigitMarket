# NDIGITMARKET — Correctifs appliqués

## 1. Bugs PHP critiques corrigés

### `App/Controllers/Admin/Traits/VendorsTrait.php`
- **BUG** : dans `approveVendor()`, la chaîne
  `$db->prepare(...)->execute([$id]) ? $db->prepare(...)->fetch() : null`
  créait un **deuxième `prepare()` non exécuté**, donc `$demande` valait toujours `false`
  → aucune approbation ne fonctionnait.
- **FIX** : séparation prepare/execute/fetch, création automatique du portefeuille
  si absent, filtres `statut` + `search` ajoutés à la liste.

### `App/Controllers/Admin/Traits/WithdrawalsTrait.php`
- Même bug que ci-dessus dans `approveWithdrawal()` → corrigé.
- Ajout de la protection `GREATEST(0, solde - ?)` pour empêcher un solde négatif.
- Filtres `statut` + `search` sur la liste.

### `App/Controllers/Admin/Traits/CategoriesTrait.php`
- **BUG** : `addCategory()` insérait dans la colonne `image` alors que la BDD
  a `image_cat` → erreur SQL silencieuse.
- **FIX** : utilise `image_cat`. Ajout de `getCategory`, `updateCategory`,
  upload d'image sécurisé, refus de suppression si des produits sont rattachés
  (conforme CDC 4.8).

### `App/Controllers/Admin/Traits/AdminHelpersTrait.php`
- **BUG** : `checkAuth()` redirigeait vers `/admin/dashboard` en cas d'échec,
  créant une **boucle infinie** avec `AdminMiddleware`.
- **FIX** : redirection vers la page de connexion (`BASE_URL . '/'`) + réponse
  JSON 401 pour les requêtes AJAX.
- Ajout de `sortParams()` (colonne + ordre sécurisés).
- `logAction()` : création de la table `admin_logs` mémorisée par requête
  (au lieu d'un `CREATE TABLE` à chaque appel).

### `App/Controllers/Admin/Traits/LogsTrait.php`
- **BUG** : lisait `email_logs` au lieu de `admin_logs` (audit trail).
- **FIX** : lit `admin_logs` avec filtres (`action`, `status`, `date_from`,
  `date_to`, `search`) + jointure sur la table `admin` pour afficher le nom.
- Ajout de la route `admin/exportLogs` (export CSV conforme CDC 4.13).

### `App/Core/Router.php`
- Liste `ADMIN_ACTIONS_WITH_ID` étendue avec les nouvelles actions
  (`blockUser`, `unblockUser`, `deleteUser`, `getCategory`, `updateCategory`).

## 2. Nouveaux fichiers

### `database/migrations_fixes.sql` — à exécuter APRÈS `ndigitmarket.sql`
Tables manquantes (idempotentes, `CREATE TABLE IF NOT EXISTS`) :
- `admin_logs`       — journal d'audit admin
- `avis`             — avis produits (module 4.10)
- `notes_internes`   — notes admin sur utilisateurs/vendeurs (CDC 6.1)
- `admin_notifications` — notifications admin (module 10)

Section commentée en bas du fichier : `ALTER TABLE ... ADD PRIMARY KEY ...`
si tu constates que certaines tables du dump n'ont pas de `AUTO_INCREMENT`.

### `assets/JS/admin.js` — module ES6 (310 lignes)
Comportement global piloté par attributs `data-*`, chargé automatiquement
sur toutes les pages admin via `App/Views/components/sidebar.php`.

| Comportement                | Attribut HTML                                  |
| --------------------------- | ---------------------------------------------- |
| Bouton d'action AJAX        | `data-action="admin/xxx" data-id="42"`         |
| Confirmation SweetAlert2    | `data-confirm="Question ?"`                    |
| Formulaire AJAX             | `<form data-ajax data-action="admin/xxx">`     |
| Tri de colonne              | `<th data-sort-col="nom">`                     |
| Recherche debounce          | `<input data-search-input="search">`           |
| Filtre auto-submit          | `<form data-filter>` avec `<select>/<input>`   |
| Sélection multiple          | `data-bulk-master` + `data-bulk-checkbox`      |
| Action groupée              | `<button data-bulk-action="admin/bulkBlock">`  |
| Modale                      | `data-open-modal="id"` / `data-close-modal`    |
| Édition (pré-remplissage)   | `data-edit='{"nom":"…","email":"…"}'`          |
| Pagination client-side      | `<div data-paginate data-page-size="25">`      |

### `App/Views/components/sidebar.php`
Ajout automatique en bas de :
- `sweetalert2` (CSS + JS via CDN)
- `assets/JS/admin.js` (module global)
- `window.NDIGIT_BASE_URL` (pour construire les URLs AJAX)

## 3. Vue d'exemple entièrement câblée

`App/Views/admin/gestion-utilisateurs.php` a été refactorée pour utiliser
le nouveau système :
- Recherche + filtres (rôle, statut) fonctionnels via GET
- Tri sur toutes les colonnes (ID, nom, email, rôle, statut, commandes, CA)
- Boutons **par ligne** : Éditer, Bloquer/Débloquer, Promouvoir, Supprimer
  → tous branchés en AJAX avec confirmation SweetAlert2
- Actions groupées (bulk block/unblock/promote/delete) : conforme CDC
- Modale d'ajout/édition en AJAX (mêmes champs, action calculée dynamiquement)
- Pagination client-side (25/page)

## 4. À faire toi-même sur les autres vues (procédure identique)

Pour appliquer le même traitement aux autres pages, il suffit d'ajouter
sur chaque bouton d'action un couple `data-action` + `data-id` (+ `data-confirm`).
Le JS global fait le reste. Exemples :

```html
<!-- Approuver un vendeur -->
<button data-action="admin/approveVendor" data-id="<?= $vendor['id'] ?>"
        data-confirm="Approuver ce vendeur ?">Approuver</button>

<!-- Refuser un produit -->
<button data-action="admin/rejectProduct" data-id="<?= $product['id'] ?>"
        data-confirm="Refuser ce produit ?">Refuser</button>

<!-- Approuver un retrait -->
<button data-action="admin/approveWithdrawal" data-id="<?= $w['id'] ?>"
        data-confirm="Approuver ce retrait ?">Approuver</button>
```

Pour les tris :
```html
<th data-sort-col="nom">Nom <i class="fas fa-sort"></i></th>
```

Pour la recherche :
```html
<input type="text" data-search-input="search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
```

Toutes les méthodes de contrôleur existantes acceptent déjà les filtres GET.

## 5. Ce qui n'a PAS été touché (volontairement)

- Structure MVC : identique
- Noms de tables, colonnes, clés : identiques
- Fonctionnement du login `public/index.php` : inchangé
- Comportement des méthodes existantes : inchangé (extension additive uniquement)

## 6. Étapes de mise en route

1. Copier le projet dans `htdocs/ndigitmarket/` (XAMPP).
2. Importer `database/ndigitmarket.sql` si ce n'est pas déjà fait.
3. Importer **`database/migrations_fixes.sql`** dans la même base.
4. Vérifier que `config/database.php` pointe sur la bonne base (défaut : `ndigi2561261`, user `root`, sans mot de passe).
5. Ouvrir `http://localhost/ndigitmarket/` → login admin.
6. La page **Gestion utilisateurs** doit maintenant afficher les données,
   les filtres, tris, recherche, actions par ligne et actions groupées
   fonctionnels.

Pour rendre les autres pages (produits, vendeurs, commandes, catégories,
retraits, avis) aussi fonctionnelles, applique la même convention
`data-action` + `data-id` sur leurs boutons — le JS global s'en charge.

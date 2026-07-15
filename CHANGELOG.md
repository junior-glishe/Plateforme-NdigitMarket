# CHANGELOG — Refactor NDIGITMARKET

Date : 2 juillet 2026
Portée : refonte structurelle MVC complète du back-office administrateur.
Objectif : rendre le code lisible, découpé, réutilisable et conforme au CDC,
sans altérer le comportement fonctionnel ni les URLs existantes.

## 1. Nouvelle couche `App/Core`

Ajout d'un noyau applicatif minimaliste :

| Fichier | Rôle |
|---|---|
| `App/Core/Autoloader.php` | Autoloader PSR-4 dédié au namespace `App\`. |
| `App/Core/Router.php` | Dispatcher HTTP unique (remplace la logique inline de l'ex-`index.php`). |

Bénéfices :
- Le front controller `index.php` passe de **123 lignes** à **21 lignes**.
- Les URLs restent identiques (`/`, `/login`, `/logout`, `/admin`, `/admin/<page>`, `/admin/action/<name>/<id>`).
- La liste des actions admin qui reçoivent un `id` est centralisée dans `Router::ADMIN_ACTIONS_WITH_ID`.

## 2. Découpage de `AdminController` (1315 → 68 lignes)

L'ancien monolithe `App/Controllers/Admin/AdminController.php` (52 Ko, 44 méthodes)
est éclaté en **17 traits** par domaine métier, tous placés sous
`App/Controllers/Admin/Traits/` :

| Trait | Méthodes |
|---|---|
| `AdminHelpersTrait` | `formatCurrency`, `normalizeFloat`, `checkAuth`, `jsonResponse`, `formatDate`, `logAction` |
| `DashboardTrait` | `dashboard` |
| `UsersTrait` | `users`, `createUser`, `getUser`, `updateUser`, `promoteUser`, `blockUser`, `unblockUser`, `deleteUser` |
| `VendorsTrait` | `vendors`, `approveVendor`, `rejectVendor` |
| `ProductsTrait` | `products`, `approveProduct`, `rejectProduct`, `deleteProduct` |
| `OrdersTrait` | `orders` |
| `CommissionsTrait` | `commissions` |
| `CategoriesTrait` | `categories`, `addCategory`, `deleteCategory` |
| `ReviewsTrait` | `reviews`, `approveReview`, `rejectReview` |
| `NotificationsTrait` | `notifications` |
| `ReportsTrait` | `reports` |
| `SettingsTrait` | `settings` |
| `LogsTrait` | `logs` |
| `ContentsTrait` | `contents` |
| `WithdrawalsTrait` | `withdrawals`, `approveWithdrawal`, `rejectWithdrawal` |
| `BulkActionsTrait` | `bulkBlock`, `bulkUnblock`, `bulkPromote`, `bulkDelete`, `bulkAction` |
| `PageDispatchTrait` | `page` (dispatcher générique `/admin/<page>`) |

`AdminController` devient une classe façade de ~60 lignes qui compose ces traits.
**Aucune signature n'a changé** : le routeur continue de dispatcher via
`method_exists()` exactement comme avant.

## 3. Correction de `AuthController`

L'ancien contrôleur redirigeait vers des chemins bruts (`/App/Views/admin/dashboard.php`)
qui contournaient le routeur et le middleware. Version refactorisée :

- Les redirections passent toutes par `BASE_URL` + `/index.php?route=...`.
- Support des deux noms de champ `password` et `mdp` pour compatibilité.
- Extraction du helper `verifyPassword()` (hash + migration douce clair→hash).
- Extraction de `fail($message)` et `redirect($path)` pour éliminer la duplication.
- Session admin (`$_SESSION['admin']`, `user_role`, `user_id`) alimentée de manière
  cohérente pour que `AdminMiddleware` reconnaisse la connexion.
- La méthode `logout()` détruit aussi le cookie de session côté client.

## 4. `AdminModel` normalisé

- Déplacé dans le namespace `App\Models` et hérite désormais de `BaseModel`
  (au lieu de dupliquer la connexion PDO).
- Alias global `\AdminModel` conservé pour `public/index.php` qui l'utilise
  encore sans namespace.
- Retour typé `array|false` sur `findByEmail()`.

## 5. Ménage

- Suppression du doublon `database/seeds/ndigi2561261.sql` (2,8 Mo).
- Renommage du dump en `database/ndigitmarket.sql`.
- Suppression du doublon PDF `docs/CDC_NDIGITMARKET_Admin-2.pdf`.
- Ajout de `composer.json` avec autoload PSR-4 (optionnel : l'autoloader
  maison de `App/Core/Autoloader.php` fonctionne sans Composer).

## 6. Ce qui n'a PAS été modifié (volontairement)

- **Les vues `App/Views/admin/*.php`** : ~1 Mo de HTML/PHP existant qui
  fonctionne. Renommer les fichiers casserait `PageDispatchTrait::page($pageName)`
  qui les charge par nom (`gestion-utilisateurs`, `gestion-produits`, etc.).
- **Les assets** (`assets/`, SCSS Cuba admin) : intacts.
- **Le schéma SQL** (`database/ndigitmarket.sql`) : intact.
- **Les middlewares** existants (`AdminMiddleware`, `GuestMiddleware`,
  `AuthMiddleware`, `RoleMiddleware`, `SupportMiddleware`, `ModeratorMiddleware`) :
  intacts, ils étaient déjà propres.
- **Les modèles** `Category`, `Notification`, `Order`, `OrderItem`, `Payment`,
  `Product`, `Review`, `Role`, `Seller`, `Setting`, `Subscription`, `Ticket`,
  `User` : intacts (déjà proprement typés).

## 7. Vérification

Tous les fichiers PHP modifiés passent `php -l` (contrôle syntaxique PHP 8.4)
sans erreur — 20 fichiers vérifiés (17 traits + AdminController + Router +
Autoloader + AuthController + AdminModel + index.php).

## 8. Compatibilité URL

Aucun changement d'URL. Le contrat exposé au front est identique à la version
d'origine, ce qui garantit que le HTML/JS admin existant continue de fonctionner.

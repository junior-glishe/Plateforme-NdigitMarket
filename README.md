# NDIGITMARKET — Back-office administrateur

Application PHP MVC (PHP 8+, MySQL/MariaDB) qui implémente l'espace
**administrateur** décrit dans le cahier des charges (`docs/CDC_NDIGITMARKET_Admin.pdf`).

## 1. Architecture MVC

```
ndigitmarket/
├── index.php                 ← Front controller (routeur unique)
├── .htaccess                 ← URL rewriting Apache
├── config/
│   ├── config.php            ← Constantes globales / debug
│   └── database.php          ← Connexion PDO singleton
├── App/
│   ├── Controllers/
│   │   ├── Admin/AdminController.php
│   │   └── Auth/AuthController.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   ├── RoleMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   ├── SupportMiddleware.php
│   │   └── ModeratorMiddleware.php
│   ├── Models/
│   │   ├── BaseModel.php     ← CRUD générique
│   │   ├── AdminModel.php
│   │   ├── User.php, Product.php, Order.php, Category.php,
│   │   ├── Seller.php, Payment.php, Subscription.php, Review.php,
│   │   ├── Notification.php, Ticket.php, Setting.php, Role.php,
│   │   └── OrderItem.php
│   └── Views/
│       ├── auth/
│       ├── components/       ← sidebar, header
│       └── admin/            ← 13 pages du back-office
├── assets/                   ← CSS / JS / images (front-end intact)
├── public/                   ← Point d'entrée public (login)
├── Auth/connexion.html       ← Maquette originale
├── database/
│   ├── ndigi2561261.sql      ← Dump complet à importer
│   └── seeds/
└── docs/                     ← Cahier des charges
```

## 2. Installation

1. Copier le dossier `ndigitmarket/` dans `htdocs/` (XAMPP) ou
   `www/` (WAMP/Laragon).
2. Démarrer Apache + MySQL, ouvrir phpMyAdmin.
3. Créer une base **`ndigi2561261`** et importer
   `database/ndigi2561261.sql`.
4. Vérifier les identifiants MySQL dans `config/database.php`
   (par défaut : host `localhost`, user `root`, password `""`).
5. Ouvrir <http://localhost/ndigitmarket/>.

Compte administrateur : voir la table `admin` du dump.

## 3. Routage (URLs)

| URL                                     | Rôle                           |
|-----------------------------------------|--------------------------------|
| `/`                                     | Page de connexion              |
| `/login` (POST)                         | Traitement du login            |
| `/logout`                               | Déconnexion                    |
| `/admin` ou `/admin/dashboard`          | Tableau de bord                |
| `/admin/gestion-utilisateurs`           | Utilisateurs                   |
| `/admin/gestion-vendeur`                | Vendeurs / demandes            |
| `/admin/gestion-produits`               | Produits                       |
| `/admin/gestion-commande`               | Commandes                      |
| `/admin/financieres-commission`         | Finances & commissions         |
| `/admin/categorie`                      | Catégories                     |
| `/admin/contenus`                       | Contenus / templates           |
| `/admin/notifications`                  | Notifications                  |
| `/admin/rapport-stat`                   | Rapports & stats               |
| `/admin/avis-commentaires`              | Avis                           |
| `/admin/parametres-systeme`             | Paramètres                     |
| `/admin/logs-audit`                     | Journaux d'audit               |
| `/admin/action/<name>/<id?>`            | Actions CRUD / AJAX            |

## 4. Workflow implémenté (conforme au CDC)

- **Dashboard** — KPIs (CA, commissions, panier moyen, utilisateurs, vendeurs, produits en attente…), 12 derniers mois, top 5 produits.
- **Utilisateurs** — Liste, tri, recherche, création, édition, promotion, blocage, suppression, actions groupées.
- **Vendeurs** — Validation / rejet des demandes.
- **Produits** — Approbation / rejet / suppression.
- **Commandes** — Suivi + revenus.
- **Finances** — Commissions (10 %), demandes de retrait vendeur.
- **Catégories** — Ajout / suppression.
- **Avis** — Modération (approuver / rejeter).
- **Notifications** — Historique des emails envoyés.
- **Rapports** — Exports & KPIs consolidés.
- **Paramètres** — Configuration système.
- **Logs** — Journalisation automatique des actions admin.

Toutes les actions sensibles passent par `logAction()` et sont conservées
dans la table `admin_logs` (créée automatiquement à la première écriture).

## 5. Sécurité

- Sessions HTTP-only ; `session_regenerate_id()` après login.
- Middlewares (`AdminMiddleware`, `GuestMiddleware`, `RoleMiddleware`).
- Requêtes préparées PDO partout (aucune concaténation SQL).
- Vérification des mots de passe via `password_verify()` avec migration
  douce depuis les anciens mots de passe en clair.
- Journalisation IP + user-agent sur les actions admin.

## 6. Développement / production

Dans `config/config.php` :
```php
$GLOBALS['APP_DEBUG'] = true; // false en production
```
Dans `config/database.php`, passer `$debug = false` en production.

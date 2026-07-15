# Système RBAC (Role-Based Access Control)

## Vue d'ensemble

Ce document décrit le système de gestion des rôles et permissions (RBAC) implémenté pour le panneau d'administration de NDIGITMARKET.

## Architecture

### Fichiers créés

1. **`App/Core/Permission.php`** - Définit toutes les permissions et les mappings par rôle
2. **`App/Core/Auth.php`** - Classe utilitaire pour vérifier les permissions
3. **`App/Core/ViewHelper.php`** - Fonctions helper pour les vues
4. **`App/Middleware/PermissionMiddleware.php`** - Middleware de vérification des permissions
5. **`App/Views/errors/403.php`** - Page d'erreur 403 personnalisée
6. **`database/migrate_passwords.php`** - Script de migration des mots de passe

### Fichiers modifiés

1. **`App/Controllers/Auth/AuthController.php`** - Authentification améliorée avec migration automatique des mots de passe
2. **`App/Middleware/AdminMiddleware.php`** - Vérification par permissions
3. **`App/Middleware/ModeratorMiddleware.php`** - Vérification par permissions
4. **`App/Middleware/SupportMiddleware.php`** - Vérification par permissions
5. **`App/Views/components/sidebar.php`** - Menu dynamique selon les permissions

## Rôles et Permissions

### Super Admin
Accès complet à toutes les fonctionnalités.

**Permissions :**
- Dashboard complet
- Gestion des administrateurs (CRUD)
- Gestion des rôles et permissions
- Gestion des utilisateurs (CRUD + validation)
- Gestion des vendeurs (CRUD + validation/approbation/rejet/suspension)
- Gestion des produits (CRUD + validation/approbation/rejet)
- Gestion des catégories (CRUD)
- Gestion des commandes (CRUD + validation)
- Gestion des paiements (CRUD + validation)
- Gestion financière (commissions, retraits)
- Gestion des publicités et bannières (CRUD)
- Gestion des avis et signalements
- Paramètres système (y compris critiques)
- Logs et sauvegardes
- Statistiques

### Admin
Accès opérationnel avancé, sans gestion des rôles/administrateurs.

**Permissions :**
- Dashboard
- Utilisateurs (CRUD + validation)
- Vendeurs (CRUD + validation/approbation/rejet/suspension)
- Produits (CRUD + validation/approbation/rejet)
- Catégories (CRUD)
- Commandes (CRUD + validation)
- Paiements (CRUD + validation)
- Bannières et publicités (CRUD)
- Statistiques
- Avis (CRUD + validation)
- Notifications (vue + envoi)
- Profil

**Restrictions :**
- Ne peut pas gérer les rôles
- Ne peut pas gérer les administrateurs
- Ne peut pas modifier les paramètres critiques
- Ne peut pas supprimer un Super Admin

### Modérateur
Accès limité à la modération.

**Permissions :**
- Vendeurs (vue, validation, approbation, rejet)
- Produits (vue, validation, approbation, rejet)
- Avis (vue, édition, suppression, validation)
- Signalements (vue, gestion)
- Profil

**Restrictions :**
- Aucun accès financier
- Aucun accès aux paramètres
- Aucun CRUD complet

### Support
Accès lecture uniquement.

**Permissions :**
- Utilisateurs (vue uniquement)
- Vendeurs (vue uniquement)
- Commandes (vue uniquement)
- Produits (vue uniquement)
- Profil

**Restrictions :**
- Aucun ajout
- Aucune modification
- Aucune suppression
- Aucune validation

## Utilisation

### 1. Dans les Contrôleurs

```php
<?php
// Vérifier une permission avant une action
\App\Core\Auth::authorize('users.edit');

// Ou utiliser le middleware
$permissionMiddleware = new \App\Middleware\PermissionMiddleware();
$permissionMiddleware->handle('users.edit');

// Vérifier plusieurs permissions (au moins une)
$permissionMiddleware->handleAny(['users.edit', 'users.create']);

// Vérifier plusieurs permissions (toutes requises)
$permissionMiddleware->handleAll(['users.edit', 'users.delete']);
```

### 2. Dans les Vues

```php
<?php
// Vérifier une permission
if (\App\Core\Auth::hasPermission('users.edit')): ?>
    <button>Modifier</button>
<?php endif; ?>

<?php if (\App\Core\Auth::can('users.delete')): ?>
    <button>Supprimer</button>
<?php endif; ?>

<?php if (\App\Core\Auth::hasAnyPermission(['users.edit', 'users.create'])): ?>
    <a href="...">Action</a>
<?php endif; ?>

<?php if (\App\Core\Auth::isSuperAdmin()): ?>
    <button>Action Super Admin</button>
<?php endif; ?>
```

### 3. Avec les Helpers de Vue

```php
<?php
// Charger le helper (déjà fait dans sidebar.php)
require_once __DIR__ . '/../../Core/ViewHelper.php';

// Utiliser les fonctions globales
if (can('users.edit')): ?>
    <button>Modifier</button>
<?php endif; ?>

<?php if (canAny(['users.edit', 'users.create'])): ?>
    <a href="...">Action</a>
<?php endif; ?>

<?php if (isSuperAdmin()): ?>
    <button>Action Super Admin</button>
<?php endif; ?>

<?php
// Générer des attributs conditionnels
?>
<button <?= disabledIf('users.edit') ?>>Modifier</button>
<div <?= hideIf('users.edit') ?>>Contenu caché</div>
```

### 4. Récupérer les Informations

```php
<?php
// Utilisateur connecté
$user = \App\Core\Auth::user();
// ['id' => 1, 'name' => 'Admin', 'email' => '...', 'role' => 'Super Admin']

// Rôle de l'utilisateur
$role = \App\Core\Auth::role(); // 'Super Admin', 'Admin', 'Modérateur', 'Support'

// Toutes les permissions
$permissions = \App\Core\Auth::permissions();
// ['dashboard.view', 'users.view', 'users.create', ...]

// Vérifier si connecté
if (\App\Core\Auth::check()) {
    // Utilisateur connecté
}

// Vérifier le rôle
if (\App\Core\Auth::isSuperAdmin()) { }
if (\App\Core\Auth::isAdmin()) { }
if (\App\Core\Auth::isModerator()) { }
if (\App\Core\Auth::isSupport()) { }
```

## Sécurité

### Côté Serveur

Toutes les vérifications sont effectuées côté serveur. Même si un utilisateur :
- Tape directement une URL
- Envoie une requête POST
- Utilise AJAX
- Appelle une route interne

Le système vérifie systématiquement les permissions via les middlewares et la classe Auth.

### Côté Client (Interface)

Les éléments d'interface (menus, boutons, actions) sont masqués ou désactivés si l'utilisateur n'a pas les permissions. Cela améliore l'expérience utilisateur mais ne remplace pas la vérification serveur.

## Migration des Mots de Passe

### Script de Migration

```bash
php database/migrate_passwords.php
```

Ce script va :
1. Parcourir tous les administrateurs
2. Détecter les mots de passe en clair
3. Les convertir en hashs sécurisés avec `password_hash()`
4. Mettre à jour la base de données

### Migration Automatique

Le système de connexion migre automatiquement les mots de passe en clair lors de la première connexion réussie. Aucune action manuelle n'est nécessaire.

## Ajout de Nouvelles Permissions

### 1. Définir la permission

Dans `App/Core/Permission.php`, ajouter la permission dans le tableau `ALL` :

```php
public const ALL = [
    // ...
    'nouvelle_module.view',
    'nouvelle_module.create',
    // ...
];
```

### 2. Assigner aux rôles

Dans le même fichier, ajouter la permission aux rôles concernés :

```php
public const ROLE_PERMISSIONS = [
    'Super Admin' => [
        // ...
        'nouvelle_module.view',
        'nouvelle_module.create',
        // ...
    ],
    'Admin' => [
        // ...
        'nouvelle_module.view',
        // ...
    ],
    // ...
];
```

### 3. Utiliser dans le code

```php
// Dans un contrôleur
\App\Core\Auth::authorize('nouvelle_module.create');

// Dans une vue
if (\App\Core\Auth::hasPermission('nouvelle_module.create')): ?>
    <button>Créer</button>
<?php endif; ?>
```

## Ajout d'un Nouveau Rôle

### 1. Ajouter le rôle dans Permission.php

```php
public const ROLE_PERMISSIONS = [
    'Super Admin' => [ /* ... */ ],
    'Admin' => [ /* ... */ ],
    'Modérateur' => [ /* ... */ ],
    'Support' => [ /* ... */ ],
    'Nouveau Rôle' => [
        'dashboard.view',
        'users.view',
        // Définir les permissions
    ],
];
```

### 2. Ajouter un helper (optionnel)

Dans `App/Core/Auth.php` :

```php
public static function isNouveauRole(): bool
{
    return self::role() === 'Nouveau Rôle';
}
```

### 3. Mettre à jour la base de données

```sql
INSERT INTO `admin` (`nom`, `email`, `mdp`, `image_auteur`, `role`)
VALUES ('Nom', 'email@example.com', 'HASH', 'image.png', 'Nouveau Rôle');
```

## Bonnes Pratiques

1. **Toujours vérifier côté serveur** - Ne jamais faire confiance uniquement au frontend
2. **Utiliser les constantes** - Éviter les strings magiques
3. **Privilégier les permissions spécifiques** - `users.edit` plutôt que `users.manage`
4. **Grouper les permissions logiquement** - Par module/fonctionnalité
5. **Documenter les permissions** - Commenter leur usage
6. **Tester avec différents rôles** - Vérifier chaque rôle systématiquement

## Dépannage

### Permission non reconnue

Vérifier que la permission est bien définie dans `Permission::ALL` et assignée dans `Permission::ROLE_PERMISSIONS`.

### Rôle non reconnu

Vérifier que le rôle est bien normalisé dans `Permission::normalizeRole()`.

### Erreur 403 intempestive

Vérifier que l'utilisateur a bien la permission requise pour la ressource accédée.

### Menu qui ne s'affiche pas

Vérifier que la permission `*.view` est bien assignée au rôle de l'utilisateur.

## Support

Pour toute question ou problème, consulter la documentation ou contacter l'équipe de développement.
# Guide d'Installation Rapide - Système RBAC

## Étapes d'Installation

### 1. Vérifier les fichiers créés

Assurez-vous que tous les fichiers suivants ont été créés :

```
App/Core/Permission.php
App/Core/Auth.php
App/Core/ViewHelper.php
App/Middleware/PermissionMiddleware.php
App/Views/errors/403.php
database/migrate_passwords.php
docs/RBAC_SYSTEM.md
docs/EXEMPLES_INTEGRATION.md
```

### 2. Migrer les mots de passe (IMPORTANT)

Si vous avez des administrateurs existants avec des mots de passe en clair, exécutez le script de migration :

```bash
php database/migrate_passwords.php
```

Ce script va :
- Parcourir tous les administrateurs dans la table `admin`
- Convertir les mots de passe en clair en hashs sécurisés
- Mettre à jour la base de données

**Note :** Le système de connexion migre également les mots de passe automatiquement lors de la première connexion.

### 3. Vérifier la table admin

Assurez-vous que votre table `admin` contient la colonne `role` :

```sql
DESCRIBE admin;
```

Si la colonne n'existe pas, ajoutez-la :

```sql
ALTER TABLE `admin` 
ADD COLUMN `role` varchar(100) NOT NULL DEFAULT 'Admin' 
AFTER `mdp`;
```

### 4. Insérer les administrateurs de test

Exécutez ce script SQL pour créer les comptes administrateurs de test :

```sql
-- IMPORTANT: Remplacez 'VOTRE_MOT_de_passe' par un mot de passe sécurisé
-- Les mots de passe seront hashés automatiquement lors de la première connexion

INSERT INTO `admin` (`id_gestion`, `nom`, `email`, `mdp`, `image_auteur`, `role`) VALUES
(1, 'NTECH DIGIT', 'missambounawane8@gmail.com', 'SuperAdmin123!', 'uploads/rtyf.png', 'Super Admin'),
(2, 'Administrateur Principal', 'admin@ndigitmarket.com', 'Admin123!', 'uploads/admin.png', 'Admin'),
(3, 'Modérateur Produits', 'moderateur@ndigitmarket.com', 'Moderateur123!', 'uploads/moderateur.png', 'Modérateur'),
(4, 'Support Client', 'support@ndigitmarket.com', 'Support123!', 'uploads/support.png', 'Support');
```

**Sécurité :** Changez ces mots de passe après la première connexion !

### 5. Tester la connexion

Accédez à la page de connexion et testez avec les comptes créés :

- **Super Admin** : `missambounawane8@gmail.com` / `SuperAdmin123!`
- **Admin** : `admin@ndigitmarket.com` / `Admin123!`
- **Modérateur** : `moderateur@ndigitmarket.com` / `Moderateur123!`
- **Support** : `support@ndigitmarket.com` / `Support123!`

### 6. Vérifier les permissions

Après connexion, vérifiez que :

1. ✅ Le sidebar affiche les menus selon les permissions
2. ✅ Les boutons d'action sont visibles/masqués correctement
3. ✅ L'accès à une page sans permission retourne une erreur 403
4. ✅ La déconnexion fonctionne

## Configuration du Système

### Modifier les permissions d'un rôle

Éditez le fichier `App/Core/Permission.php` :

```php
public const ROLE_PERMISSIONS = [
    'Super Admin' => [
        // Ajouter ou supprimer des permissions
        'dashboard.view',
        'users.view',
        'users.create',
        // ...
    ],
    // ...
];
```

### Ajouter une nouvelle permission

1. **Définir la permission** dans `Permission::ALL` :

```php
public const ALL = [
    // ...
    'mon_module.view',
    'mon_module.create',
    'mon_module.edit',
    'mon_module.delete',
];
```

2. **Assigner aux rôles** dans `Permission::ROLE_PERMISSIONS` :

```php
'Super Admin' => [
    // ...
    'mon_module.view',
    'mon_module.create',
    'mon_module.edit',
    'mon_module.delete',
],
'Admin' => [
    // ...
    'mon_module.view',
    'mon_module.create',
],
```

3. **Utiliser dans le code** :

```php
// Dans un contrôleur
\App\Core\Auth::authorize('mon_module.create');

// Dans une vue
if (\App\Core\Auth::hasPermission('mon_module.create')): ?>
    <button>Créer</button>
<?php endif; ?>
```

### Ajouter un nouveau rôle

1. **Ajouter le rôle** dans `Permission::ROLE_PERMISSIONS` :

```php
public const ROLE_PERMISSIONS = [
    // ...
    'Nouveau Rôle' => [
        'dashboard.view',
        'users.view',
        // Définir les permissions
    ],
];
```

2. **Ajouter un helper** (optionnel) dans `App/Core/Auth.php` :

```php
public static function isNouveauRole(): bool
{
    return self::role() === 'Nouveau Rôle';
}
```

3. **Créer le compte** dans la base de données :

```sql
INSERT INTO `admin` (`nom`, `email`, `mdp`, `image_auteur`, `role`)
VALUES ('Nom du rôle', 'email@example.com', 'MOT_DE_PASSE_HASH', 'image.png', 'Nouveau Rôle');
```

## Intégration dans le Code Existant

### Dans les Contrôleurs

Ajoutez des vérifications de permissions au début de chaque méthode :

```php
public function editUser(int $id): void
{
    // Vérifier la permission
    \App\Core\Auth::authorize('users.edit');
    
    // Le reste du code...
}
```

### Dans les Vues

Enveloppez les éléments sensibles avec des conditions :

```php
<?php if (\App\Core\Auth::hasPermission('users.create')): ?>
    <button>Ajouter</button>
<?php endif; ?>
```

### Dans le Router (optionnel)

Pour une sécurité renforcée, ajoutez des vérifications dans le routeur :

```php
// App/Core/Router.php

$actionPermissions = [
    'users' => 'users.view',
    'createUser' => 'users.create',
    'updateUser' => 'users.edit',
    'deleteUser' => 'users.delete',
];

$requiredPermission = $actionPermissions[$action] ?? null;
if ($requiredPermission && !\App\Core\Auth::hasPermission($requiredPermission)) {
    http_response_code(403);
    require_once __DIR__ . '/../Views/errors/403.php';
    return;
}
```

## Dépannage

### Erreur : "Class not found"

Vérifiez que l'autoloader est configuré correctement :

```php
// App/Core/Autoloader.php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
```

### Erreur 403 sur toutes les pages

Vérifiez que le rôle est bien défini dans la session :

```php
// Dans AuthController::login()
$role = \App\Core\Permission::normalizeRole((string) $account['role']);
$_SESSION['user_role'] = $role;
```

### Menu qui ne s'affiche pas

Vérifiez que la permission `dashboard.view` est bien assignée au rôle.

### Boutons qui ne s'affichent pas

Vérifiez que les permissions sont bien assignées au rôle dans `Permission::ROLE_PERMISSIONS`.

## Vérification Post-Installation

### Checklist

- [ ] Les fichiers RBAC sont créés
- [ ] Les mots de passe ont été migrés (ou le script a été exécuté)
- [ ] La table `admin` a la colonne `role`
- [ ] Les comptes administrateurs ont été créés
- [ ] La connexion fonctionne pour tous les rôles
- [ ] Le sidebar affiche les bons menus selon le rôle
- [ ] Les boutons d'action sont visibles/masqués correctement
- [ ] L'accès à une page sans permission retourne 403
- [ ] La déconnexion fonctionne

### Tests par Rôle

#### Super Admin
- [ ] Accès à toutes les pages
- [ ] Peut gérer les administrateurs
- [ ] Peut modifier les paramètres système
- [ ] Peut voir les logs

#### Admin
- [ ] Accès aux pages opérationnelles
- [ ] Ne peut pas gérer les administrateurs
- [ ] Ne peut pas modifier les paramètres critiques
- [ ] Ne peut pas supprimer un Super Admin

#### Modérateur
- [ ] Accès limité à la modération
- [ ] Peut valider/rejeter les vendeurs
- [ ] Peut valider/rejeter les produits
- [ ] Ne peut pas accéder aux finances

#### Support
- [ ] Accès en lecture seule
- [ ] Peut consulter les utilisateurs, vendeurs, commandes, produits
- [ ] Ne peut pas modifier/supprimer/valider

## Maintenance

### Sauvegarde

Sauvegardez régulièrement :
- La table `admin`
- Le fichier `App/Core/Permission.php` (contient la configuration des rôles)

### Mises à jour

Lors de l'ajout de nouvelles fonctionnalités :
1. Définir les permissions nécessaires
2. Les assigner aux rôles concernés
3. Ajouter les vérifications dans les contrôleurs
4. Masquer/afficher les éléments dans les vues

### Monitoring

Surveillez les logs pour détecter :
- Tentatives d'accès non autorisées (403)
- Erreurs de permissions
- Comportements suspects

## Support

Pour toute question :
1. Consultez `docs/RBAC_SYSTEM.md` pour la documentation complète
2. Consultez `docs/EXEMPLES_INTEGRATION.md` pour des exemples d'utilisation
3. Vérifiez les logs d'erreur PHP

## Sécurité

### Bonnes Pratiques

1. **Mots de passe** : Utilisez toujours des mots de passe forts
2. **Permissions** : Appliquez le principe du moindre privilège
3. **Vérifications** : Vérifiez toujours côté serveur
4. **Logs** : Conservez un historique des accès
5. **Sauvegardes** : Sauvegardez régulièrement la configuration

### Alertes de Sécurité

- Ne jamais stocker de mots de passe en clair
- Ne jamais faire confiance uniquement au frontend
- Toujours vérifier les permissions côté serveur
- Protéger les comptes Super Admin
- Logger les actions sensibles

## Conclusion

Le système RBAC est maintenant opérationnel. Pour toute question ou problème, consultez la documentation ou contactez l'équipe de développement.

**Prochaines étapes :**
1. Tester chaque rôle
2. Intégrer les vérifications dans tous les contrôleurs
3. Mettre à jour toutes les vues
4. Former les administrateurs
5. Documenter les permissions spécifiques à votre métier
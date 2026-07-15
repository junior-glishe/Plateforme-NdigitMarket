# Exemples d'Intégration du Système RBAC

## Table des matières

1. [Intégration dans les Contrôleurs](#intégration-dans-les-contrôleurs)
2. [Intégration dans le Router](#intégration-dans-le-router)
3. [Intégration dans les Vues](#intégration-dans-les-vues)
4. [Exemples Complets](#exemples-complets)

---

## Intégration dans les Contrôleurs

### Exemple 1 : Vérification simple dans une action

```php
<?php
// App/Controllers/Admin/UsersTrait.php

public function editUser(int $id): void
{
    // Vérifier la permission avant toute action
    \App\Core\Auth::authorize('users.edit');
    
    // Récupérer l'utilisateur
    $user = $this->getUser($id);
    
    // Afficher le formulaire
    require_once __DIR__ . '/../../Views/admin/edit-user.php';
}
```

### Exemple 2 : Vérification avec redirection personnalisée

```php
<?php
public function deleteUser(int $id): void
{
    // Vérifier la permission
    if (!\App\Core\Auth::hasPermission('users.delete')) {
        // Redirection vers le dashboard avec message d'erreur
        $_SESSION['error'] = 'Vous n\'avez pas les permissions pour supprimer un utilisateur.';
        $this->redirect('/index.php?route=admin/dashboard');
        return;
    }
    
    // Vérifier si c'est un Super Admin (protection supplémentaire)
    $user = $this->getUser($id);
    if ($user['role'] === 'Super Admin') {
        $_SESSION['error'] = 'Impossible de supprimer un Super Admin.';
        $this->redirect('/index.php?route=admin/gestion-utilisateurs');
        return;
    }
    
    // Supprimer l'utilisateur
    $this->delete($id);
    
    $_SESSION['success'] = 'Utilisateur supprimé avec succès.';
    $this->redirect('/index.php?route=admin/gestion-utilisateurs');
}
```

### Exemple 3 : Utilisation du PermissionMiddleware

```php
<?php
// Au début de la méthode
public function createUser(): void
{
    $permissionMiddleware = new \App\Middleware\PermissionMiddleware();
    $permissionMiddleware->handle('users.create');
    
    // Le code continue seulement si la permission est accordée
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Traiter le formulaire
    }
    
    require_once __DIR__ . '/../../Views/admin/create-user.php';
}
```

### Exemple 4 : Vérification multiple (au moins une permission)

```php
<?php
public function manageUser(int $id): void
{
    // L'utilisateur doit avoir au moins une de ces permissions
    $permissionMiddleware = new \App\Middleware\PermissionMiddleware();
    $permissionMiddleware->handleAny([
        'users.edit',
        'users.view',
        'users.validate'
    ]);
    
    $user = $this->getUser($id);
    require_once __DIR__ . '/../../Views/admin/user-details.php';
}
```

### Exemple 5 : Vérification multiple (toutes les permissions)

```php
<?php
public function bulkDeleteUsers(): void
{
    // L'utilisateur doit avoir TOUTES ces permissions
    $permissionMiddleware = new \App\Middleware\PermissionMiddleware();
    $permissionMiddleware->handleAll([
        'users.delete',
        'users.view'
    ]);
    
    // Traiter la suppression en masse
    $this->processBulkDelete();
}
```

### Exemple 6 : Actions conditionnelles selon le rôle

```php
<?php
public function dashboard(): void
{
    // Tout le monde avec dashboard.view peut accéder
    \App\Core\Auth::authorize('dashboard.view');
    
    $stats = [];
    
    // Statistiques pour tous
    if (\App\Core\Auth::hasPermission('statistics.view')) {
        $stats['general'] = $this->getGeneralStats();
    }
    
    // Statistiques financières (seulement pour certains rôles)
    if (\App\Core\Auth::hasPermission('finance.view')) {
        $stats['finance'] = $this->getFinancialStats();
    }
    
    // Statistiques utilisateurs (seulement pour admins)
    if (\App\Core\Auth::hasPermission('users.view')) {
        $stats['users'] = $this->getUserStats();
    }
    
    require_once __DIR__ . '/../../Views/admin/dashboard.php';
}
```

---

## Intégration dans le Router

### Exemple 1 : Ajouter une vérification de permission dans le Router

```php
<?php
// App/Core/Router.php

private function dispatchAdmin(AdminController $controller, array $segments): void
{
    $action = $segments[1] ?? 'dashboard';
    $id     = isset($segments[2]) ? (int) $segments[2] : 0;

    // Mapping action -> permission requise
    $actionPermissions = [
        'dashboard' => 'dashboard.view',
        'users' => 'users.view',
        'createUser' => 'users.create',
        'updateUser' => 'users.edit',
        'deleteUser' => 'users.delete',
        'vendors' => 'vendors.view',
        'approveVendor' => 'vendors.approve',
        'products' => 'products.view',
        'createProduct' => 'products.create',
        // ... etc
    ];

    // Vérifier la permission si elle est définie
    if (isset($actionPermissions[$action])) {
        $permission = $actionPermissions[$action];
        if (!\App\Core\Auth::hasPermission($permission)) {
            http_response_code(403);
            require_once __DIR__ . '/../Views/errors/403.php';
            return;
        }
    }

    // 1) Méthode directe du contrôleur
    if (method_exists($controller, $action)) {
        if (in_array($action, self::ADMIN_ACTIONS_WITH_ID, true)) {
            $controller->$action($id);
        } else {
            $controller->$action();
        }
        return;
    }

    // 2) Dispatcher de page
    if (method_exists($controller, 'page')) {
        $controller->page($action);
        return;
    }

    http_response_code(404);
    echo '<h1>404 - Action admin introuvable</h1>';
}
```

### Exemple 2 : Router avec middleware

```php
<?php
// App/Core/Router.php

private function dispatchAdmin(AdminController $controller, array $segments): void
{
    $action = $segments[1] ?? 'dashboard';
    $id     = isset($segments[2]) ? (int) $segments[2] : 0;

    // Tableau de mapping action -> middleware
    $actionMiddlewares = [
        'dashboard' => null,
        'users' => 'users.view',
        'createUser' => 'users.create',
        'updateUser' => 'users.edit',
        'deleteUser' => 'users.delete',
        'approveVendor' => 'vendors.approve',
        'rejectVendor' => 'vendors.reject',
    ];

    // Appliquer le middleware si nécessaire
    $requiredPermission = $actionMiddlewares[$action] ?? null;
    if ($requiredPermission) {
        $permissionMiddleware = new \App\Middleware\PermissionMiddleware();
        try {
            $permissionMiddleware->handle($requiredPermission);
        } catch (\Exception $e) {
            // Le middleware a déjà affiché la page 403 et arrêté l'exécution
            return;
        }
    }

    // Continuer avec le dispatch normal
    if (method_exists($controller, $action)) {
        if (in_array($action, self::ADMIN_ACTIONS_WITH_ID, true)) {
            $controller->$action($id);
        } else {
            $controller->$action();
        }
        return;
    }

    if (method_exists($controller, 'page')) {
        $controller->page($action);
        return;
    }

    http_response_code(404);
    echo '<h1>404 - Action admin introuvable</h1>';
}
```

---

## Intégration dans les Vues

### Exemple 1 : Afficher/Masquer un bouton

```php
<?php
// Dans une vue de liste d'utilisateurs

// Bouton "Ajouter" - seulement pour ceux qui peuvent créer
if (\App\Core\Auth::hasPermission('users.create')): ?>
    <a href="<?= BASE_URL ?>/index.php?route=admin/createUser" 
       class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un utilisateur
    </a>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <?php if (\App\Core\Auth::hasPermission('users.view')): ?>
                    <a href="<?= BASE_URL ?>/index.php?route=admin/getUser/<?= $user['id'] ?>"
                       class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (\App\Core\Auth::hasPermission('users.edit')): ?>
                    <a href="<?= BASE_URL ?>/index.php?route=admin/updateUser/<?= $user['id'] ?>"
                       class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (\App\Core\Auth::hasPermission('users.delete')): ?>
                    <button onclick="deleteUser(<?= $user['id'] ?>)"
                            class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

### Exemple 2 : Formulaire avec champs conditionnels

```php
<?php
// Dans un formulaire d'édition d'utilisateur

<form method="POST" action="<?= BASE_URL ?>/index.php?route=admin/updateUser/<?= $user['id'] ?>">
    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" 
               class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
               class="form-control" required>
    </div>
    
    <?php if (\App\Core\Auth::hasPermission('users.edit')): ?>
    <div class="form-group">
        <label>Rôle</label>
        <select name="role" class="form-control">
            <option value="Support" <?= $user['role'] === 'Support' ? 'selected' : '' ?>>Support</option>
            <option value="Modérateur" <?= $user['role'] === 'Modérateur' ? 'selected' : '' ?>>Modérateur</option>
            <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
            
            <?php if (\App\Core\Auth::hasPermission('roles.manage')): ?>
                <option value="Super Admin" <?= $user['role'] === 'Super Admin' ? 'selected' : '' ?>>Super Admin</option>
            <?php endif; ?>
        </select>
    </div>
    <?php endif; ?>
    
    <?php if (\App\Core\Auth::hasPermission('users.validate')): ?>
    <div class="form-group">
        <label>
            <input type="checkbox" name="status" value="active" 
                   <?= $user['status'] === 'active' ? 'checked' : '' ?>>
            Compte actif
        </label>
    </div>
    <?php endif; ?>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        
        <?php if (\App\Core\Auth::hasPermission('users.delete')): ?>
            <button type="button" onclick="deleteUser(<?= $user['id'] ?>)" 
                    class="btn btn-danger">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        <?php endif; ?>
    </div>
</form>
```

### Exemple 3 : Cartes du dashboard

```php
<?php
// App/Views/admin/dashboard.php

// Charger les statistiques selon les permissions
$statsCards = [];

if (\App\Core\Auth::hasPermission('users.view')) {
    $statsCards[] = [
        'title' => 'Utilisateurs',
        'value' => $totalUsers,
        'icon' => 'fas fa-users',
        'color' => 'primary',
        'link' => BASE_URL . '/index.php?route=admin/gestion-utilisateurs'
    ];
}

if (\App\Core\Auth::hasPermission('vendors.view')) {
    $statsCards[] = [
        'title' => 'Vendeurs',
        'value' => $totalVendors,
        'icon' => 'fas fa-store',
        'color' => 'success',
        'link' => BASE_URL . '/index.php?route=admin/gestion-vendeur'
    ];
}

if (\App\Core\Auth::hasPermission('products.view')) {
    $statsCards[] = [
        'title' => 'Produits',
        'value' => $totalProducts,
        'icon' => 'fas fa-box',
        'color' => 'info',
        'link' => BASE_URL . '/index.php?route=admin/gestion-produits'
    ];
}

if (\App\Core\Auth::hasPermission('orders.view')) {
    $statsCards[] = [
        'title' => 'Commandes',
        'value' => $totalOrders,
        'icon' => 'fas fa-shopping-cart',
        'color' => 'warning',
        'link' => BASE_URL . '/index.php?route=admin/gestion-commande'
    ];
}

if (\App\Core\Auth::hasPermission('finance.view')) {
    $statsCards[] = [
        'title' => 'Revenus',
        'value' => number_format($totalRevenue, 0, ',', ' ') . ' FCFA',
        'icon' => 'fas fa-coins',
        'color' => 'success',
        'link' => BASE_URL . '/index.php?route=admin/financieres-commission'
    ];
}

// Afficher les cartes
if (!empty($statsCards)): ?>
    <div class="row">
        <?php foreach ($statsCards as $card): ?>
            <div class="col-md-4 mb-4">
                <a href="<?= $card['link'] ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1"><?= $card['title'] ?></p>
                                    <h3 class="mb-0"><?= $card['value'] ?></h3>
                                </div>
                                <div class="bg-<?= $card['color'] ?> bg-opacity-10 p-3 rounded">
                                    <i class="<?= $card['icon'] ?> text-<?= $card['color'] ?> fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
```

### Exemple 4 : Tableau avec actions conditionnelles

```php
<?php
// Dans une vue de liste de produits

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= number_format($product['price'], 0, ',', ' ') ?> FCFA</td>
            <td>
                <span class="badge badge-<?= $product['status'] === 'active' ? 'success' : 'warning' ?>">
                    <?= ucfirst($product['status']) ?>
                </span>
            </td>
            <td>
                <div class="btn-group" role="group">
                    <?php if (\App\Core\Auth::hasPermission('products.view')): ?>
                        <a href="<?= BASE_URL ?>/index.php?route=admin/getProduct/<?= $product['id'] ?>"
                           class="btn btn-sm btn-info" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (\App\Core\Auth::hasPermission('products.edit')): ?>
                        <a href="<?= BASE_URL ?>/index.php?route=admin/updateProduct/<?= $product['id'] ?>"
                           class="btn btn-sm btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (\App\Core\Auth::hasPermission('products.validate')): ?>
                        <?php if ($product['status'] === 'pending'): ?>
                            <button onclick="approveProduct(<?= $product['id'] ?>)"
                                    class="btn btn-sm btn-success" title="Approuver">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="rejectProduct(<?= $product['id'] ?>)"
                                    class="btn btn-sm btn-danger" title="Rejeter">
                                <i class="fas fa-times"></i>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (\App\Core\Auth::hasPermission('products.delete')): ?>
                        <button onclick="deleteProduct(<?= $product['id'] ?>)"
                                class="btn btn-sm btn-danger" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

### Exemple 5 : Utilisation des helpers de vue

```php
<?php
// Charger le helper (une fois par page)
require_once __DIR__ . '/../../Core/ViewHelper.php';

// Dans le HTML
?>

<?php if (can('users.create')): ?>
    <button class="btn btn-primary">Ajouter</button>
<?php endif; ?>

<?php if (canAny(['users.edit', 'users.delete'])): ?>
    <div class="action-buttons">
        <?php if (can('users.edit')): ?>
            <button>Modifier</button>
        <?php endif; ?>
        
        <?php if (can('users.delete')): ?>
            <button>Supprimer</button>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (isSuperAdmin()): ?>
    <div class="super-admin-panel">
        <h3>Panneau Super Admin</h3>
        <!-- Contenu réservé au Super Admin -->
    </div>
<?php endif; ?>

<!-- Génération d'attributs conditionnels -->
<button <?= disabledIf('users.edit') ?> class="btn btn-warning">
    Modifier
</button>

<div <?= hideIf('users.edit') ?>>
    Ce contenu est caché si l'utilisateur n'a pas la permission
</div>

<!-- Avec classe CSS personnalisée -->
<button class="<?= disabledClass('users.edit', 'btn-disabled') ?>">
    Action
</button>
```

---

## Exemples Complets

### Exemple complet : Contrôleur de gestion d'utilisateurs

```php
<?php
// App/Controllers/Admin/Traits/UsersTrait.php

namespace App\Controllers\Admin\Traits;

trait UsersTrait
{
    public function users(): void
    {
        \App\Core\Auth::authorize('users.view');
        
        $users = $this->getAllUsers();
        require_once __DIR__ . '/../../../Views/admin/gestion-utilisateurs.php';
    }
    
    public function createUser(): void
    {
        \App\Core\Auth::authorize('users.create');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAndCreateUser();
        }
        
        require_once __DIR__ . '/../../../Views/admin/create-user.php';
    }
    
    public function updateUser(int $id): void
    {
        \App\Core\Auth::authorize('users.edit');
        
        $user = $this->getUser($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Utilisateur introuvable.';
            $this->redirect('/index.php?route=admin/gestion-utilisateurs');
            return;
        }
        
        // Protection : ne pas modifier un Super Admin sans la permission
        if ($user['role'] === 'Super Admin' && !\App\Core\Auth::hasPermission('roles.manage')) {
            $_SESSION['error'] = 'Vous ne pouvez pas modifier un Super Admin.';
            $this->redirect('/index.php?route=admin/gestion-utilisateurs');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateAndUpdateUser($id);
        }
        
        require_once __DIR__ . '/../../../Views/admin/edit-user.php';
    }
    
    public function deleteUser(int $id): void
    {
        \App\Core\Auth::authorize('users.delete');
        
        $user = $this->getUser($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Utilisateur introuvable.';
            $this->redirect('/index.php?route=admin/gestion-utilisateurs');
            return;
        }
        
        // Protection : ne pas supprimer un Super Admin
        if ($user['role'] === 'Super Admin') {
            $_SESSION['error'] = 'Impossible de supprimer un Super Admin.';
            $this->redirect('/index.php?route=admin/gestion-utilisateurs');
            return;
        }
        
        $this->delete($id);
        
        $_SESSION['success'] = 'Utilisateur supprimé avec succès.';
        $this->redirect('/index.php?route=admin/gestion-utilisateurs');
    }
    
    public function blockUser(int $id): void
    {
        \App\Core\Auth::authorize('users.edit');
        
        $this->updateUserStatus($id, 'blocked');
        $_SESSION['success'] = 'Utilisateur bloqué.';
        $this->redirect('/index.php?route=admin/gestion-utilisateurs');
    }
    
    public function unblockUser(int $id): void
    {
        \App\Core\Auth::authorize('users.edit');
        
        $this->updateUserStatus($id, 'active');
        $_SESSION['success'] = 'Utilisateur débloqué.';
        $this->redirect('/index.php?route=admin/gestion-utilisateurs');
    }
    
    public function validateUser(int $id): void
    {
        \App\Core\Auth::authorize('users.validate');
        
        $this->updateUserStatus($id, 'active');
        $_SESSION['success'] = 'Utilisateur validé.';
        $this->redirect('/index.php?route=admin/gestion-utilisateurs');
    }
}
```

### Exemple complet : Vue de liste avec permissions

```php
<?php
// App/Views/admin/gestion-utilisateurs.php

// Charger les helpers
require_once __DIR__ . '/../../Core/ViewHelper.php';

$currentPage = 'gestion-utilisateurs';
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/sidebar.php';
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Gestion des Utilisateurs</h1>
            
            <?php if (can('users.create')): ?>
                <a href="<?= BASE_URL ?>/index.php?route=admin/createUser" 
                   class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvel utilisateur
                </a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $user['role'] === 'Super Admin' ? 'danger' : 'primary' ?>">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $user['status'] === 'active' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if (can('users.view')): ?>
                                            <a href="<?= BASE_URL ?>/index.php?route=admin/getUser/<?= $user['id'] ?>"
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (can('users.edit') && $user['role'] !== 'Super Admin'): ?>
                                            <a href="<?= BASE_URL ?>/index.php?route=admin/updateUser/<?= $user['id'] ?>"
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (can('users.validate') && $user['status'] === 'pending'): ?>
                                            <button onclick="validateUser(<?= $user['id'] ?>)"
                                                    class="btn btn-sm btn-success" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (can('users.delete') && $user['role'] !== 'Super Admin'): ?>
                                            <button onclick="deleteUser(<?= $user['id'] ?>)"
                                                    class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../components/footer.php'; ?>

<script>
function deleteUser(id) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette action est irréversible.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>/index.php?route=admin/deleteUser/' + id;
        }
    });
}

function validateUser(id) {
    Swal.fire({
        title: 'Valider cet utilisateur ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, valider',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>/index.php?route=admin/validateUser/' + id;
        }
    });
}
</script>
```

---

## Notes importantes

1. **Toujours vérifier côté serveur** : Les vérifications dans les vues améliorent l'UX mais ne remplacent pas les vérifications dans les contrôleurs.

2. **Protection des Super Admins** : Toujours ajouter des protections supplémentaires pour les Super Admins.

3. **Messages d'erreur** : Utiliser les sessions pour les messages flash (succès/erreur).

4. **Cohérence** : Utiliser les mêmes noms de permissions dans tout le projet.

5. **Documentation** : Commenter les permissions utilisées dans chaque méthode.

## Support

Pour plus d'informations, consulter `RBAC_SYSTEM.md`.
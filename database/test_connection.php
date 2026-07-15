<?php

/**
 * Script de test de connexion administrateur
 * 
 * Ce script permet de diagnostiquer les problèmes de connexion
 * et de vérifier que le système RBAC fonctionne correctement.
 * 
 * Usage: php database/test_connection.php
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../App/Core/Autoloader.php';

use App\Models\AdminModel;
use App\Core\Permission;

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "=== Test de Connexion RBAC ===\n\n";

// Récupérer la connexion à la base de données
try {
    $db = \Database::getConnection();
    echo "✓ Connexion à la base de données réussie\n\n";
} catch (Exception $e) {
    echo "✗ Erreur de connexion à la base de données : " . $e->getMessage() . "\n";
    exit;
}

$adminModel = new AdminModel($db);

// Test 1: Vérifier la structure de la table admin
echo "1. Vérification de la table admin...\n";
try {
    $stmt = $db->query("DESCRIBE admin");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $requiredColumns = ['id_gestion', 'nom', 'email', 'mdp', 'image_auteur', 'role'];
    $missingColumns = array_diff($requiredColumns, $columns);

    if (empty($missingColumns)) {
        echo "  ✓ Toutes les colonnes requises sont présentes\n";
    } else {
        echo "  ✗ Colonnes manquantes : " . implode(', ', $missingColumns) . "\n";
    }

    // Vérifier la colonne role
    if (in_array('role', $columns)) {
        echo "  ✓ Colonne 'role' présente\n";
    } else {
        echo "  ✗ Colonne 'role' MANQUANTE - Exécutez ce SQL :\n";
        echo "    ALTER TABLE `admin` ADD COLUMN `role` varchar(100) NOT NULL DEFAULT 'Admin' AFTER `mdp`;\n";
    }
} catch (Exception $e) {
    echo "  ✗ Erreur : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Vérifier les administrateurs
echo "2. Vérification des administrateurs...\n";
try {
    $stmt = $db->query("SELECT id_gestion, nom, email, role, LENGTH(mdp) as mdp_length FROM admin");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($admins)) {
        echo "  ⚠ Aucun administrateur trouvé dans la base de données\n";
        echo "  → Exécutez le SQL d'insertion pour créer les comptes de test\n";
    } else {
        echo "  ✓ " . count($admins) . " administrateur(s) trouvé(s)\n\n";

        foreach ($admins as $admin) {
            echo "  ID: {$admin['id_gestion']}\n";
            echo "    Nom: {$admin['nom']}\n";
            echo "    Email: {$admin['email']}\n";
            echo "    Rôle: {$admin['role']}\n";
            echo "    Longueur mot de passe: {$admin['mdp_length']} caractères\n";

            // Vérifier si le mot de passe est hashé
            if (password_get_info($admin['mdp'])['algo'] !== null) {
                echo "    ✓ Mot de passe hashé\n";
            } else {
                echo "    ⚠ Mot de passe EN CLAIR (sera migré lors de la première connexion)\n";
            }

            // Vérifier que le rôle est valide
            $normalizedRole = Permission::normalizeRole($admin['role']);
            if (isset(Permission::ROLE_PERMISSIONS[$normalizedRole])) {
                echo "    ✓ Rôle valide : $normalizedRole\n";
            } else {
                echo "    ✗ Rôle INCONNU : {$admin['role']} (normalisé: $normalizedRole)\n";
            }

            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "  ✗ Erreur : " . $e->getMessage() . "\n";
}

// Test 3: Vérifier les permissions
echo "3. Vérification du système de permissions...\n";
try {
    $totalPermissions = count(Permission::ALL);
    $roles = array_keys(Permission::ROLE_PERMISSIONS);

    echo "  ✓ $totalPermissions permissions définies\n";
    echo "  ✓ " . count($roles) . " rôles définis : " . implode(', ', $roles) . "\n";

    foreach ($roles as $role) {
        $permissions = Permission::getForRole($role);
        echo "    - $role : " . count($permissions) . " permissions\n";
    }
} catch (Exception $e) {
    echo "  ✗ Erreur : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Tester la recherche par email
echo "4. Test de recherche par email...\n";
$testEmail = 'missambounawane8@gmail.com';
try {
    $admin = $adminModel->findByEmail($testEmail);
    if ($admin) {
        echo "  ✓ Email trouvé : $testEmail\n";
        echo "    ID: {$admin['id_gestion']}\n";
        echo "    Nom: {$admin['nom']}\n";
        echo "    Rôle: {$admin['role']}\n";
    } else {
        echo "  ✗ Email non trouvé : $testEmail\n";
        echo "  → Vérifiez que le compte existe dans la base de données\n";
    }
} catch (Exception $e) {
    echo "  ✗ Erreur : " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Vérifier l'autoloader
echo "5. Vérification de l'autoloader...\n";
try {
    $authClass = new \App\Core\Auth();
    echo "  ✓ Classe Auth chargée\n";

    $permissionClass = new \App\Core\Permission();
    echo "  ✓ Classe Permission chargée\n";

    $permissionMiddleware = new \App\Middleware\PermissionMiddleware();
    echo "  ✓ Classe PermissionMiddleware chargée\n";
} catch (Exception $e) {
    echo "  ✗ Erreur de chargement : " . $e->getMessage() . "\n";
}

echo "\n";

// Résumé
echo "=== Résumé ===\n";
echo "Si vous avez des erreurs, vérifiez :\n";
echo "1. La table admin existe et a la colonne 'role'\n";
echo "2. Les comptes administrateurs sont créés\n";
echo "3. Les emails sont corrects\n";
echo "4. Le mode debug est activé dans config/database.php\n";
echo "\n";
echo "Pour tester la connexion, utilisez ces identifiants :\n";
echo "- Super Admin: missambounawane8@gmail.com / SuperAdmin123!\n";
echo "- Admin: admin@ndigitmarket.com / Admin123!\n";
echo "- Modérateur: moderateur@ndigitmarket.com / Moderateur123!\n";
echo "- Support: support@ndigitmarket.com / Support123!\n";
echo "\n";
echo "⚠️  Ces mots de passe sont en clair et seront migrés automatiquement lors de la première connexion.\n";
echo "    Changez-les après la première connexion !\n";

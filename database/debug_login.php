<?php

/**
 * Script de debug pour diagnostiquer les problèmes de connexion
 * 
 * Ce script simule le processus de connexion et affiche chaque étape
 * pour identifier où se trouve le problème.
 * 
 * Usage: php database/debug_login.php
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../App/Core/Autoloader.php';

use App\Models\User;
use App\Core\Permission;

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "=== DEBUG CONNEXION ===\n\n";

// Récupérer la connexion à la base de données
try {
    $db = \Database::getConnection();
    echo "✓ Connexion base de données OK\n\n";
} catch (Exception $e) {
    echo "✗ Erreur base de données : " . $e->getMessage() . "\n";
    exit;
}

// Test avec l'email du Super Admin
$testEmail = 'missambounawane8@gmail.com';
$testPassword = 'SuperAdmin123!';

echo "Test de connexion avec :\n";
echo "  Email: $testEmail\n";
echo "  Mot de passe: $testPassword\n\n";

// Étape 1: Rechercher l'utilisateur
echo "Étape 1: Recherche de l'utilisateur...\n";
$userModel = new User($db);
$account = $userModel->findByEmail($testEmail);

if (!$account) {
    echo "  ✗ UTILISATEUR NON TROUVÉ\n";
    echo "  → Vérifiez que l'email existe dans la table admin\n";
    exit;
}

echo "  ✓ Utilisateur trouvé:\n";
echo "    ID: {$account['id']}\n";
echo "    Nom: {$account['name']}\n";
echo "    Email: {$account['email']}\n";
echo "    Rôle: {$account['role']}\n";
echo "    Mot de passe (hash): {$account['password']}\n";
echo "    Longueur hash: " . strlen($account['password']) . " caractères\n\n";

// Étape 2: Vérifier le mot de passe
echo "Étape 2: Vérification du mot de passe...\n";
$storedPassword = $account['password'];

// Vérifier si c'est un hash valide
$passwordInfo = password_get_info($storedPassword);
echo "  Info hash: ";
print_r($passwordInfo);
echo "\n";

// Test avec password_verify
echo "  Test password_verify()...\n";
if (password_verify($testPassword, $storedPassword)) {
    echo "    ✓ Mot de passe CORRECT\n";
} else {
    echo "    ✗ Mot de passe INCORRECT\n";

    // Test avec hash_equals (migration)
    echo "  Test hash_equals() (pour mots de passe en clair)...\n";
    if (hash_equals($storedPassword, $testPassword)) {
        echo "    ✓ Mot de passe en clair correspond\n";
    } else {
        echo "    ✗ Mot de passe en clair ne correspond pas\n";
    }
}

echo "\n";

// Étape 3: Vérifier le statut
echo "Étape 3: Vérification du statut...\n";
if (isset($account['status'])) {
    echo "  Statut: {$account['status']}\n";
    if ($account['status'] !== 'active') {
        echo "  ✗ Compte désactivé\n";
    } else {
        echo "  ✓ Compte actif\n";
    }
} else {
    echo "  ⚠ Pas de statut défini (considéré comme actif)\n";
}

echo "\n";

// Étape 4: Normaliser le rôle
echo "Étape 4: Normalisation du rôle...\n";
$rawRole = $account['role'];
$normalizedRole = Permission::normalizeRole($rawRole);
echo "  Rôle brut: $rawRole\n";
echo "  Rôle normalisé: $normalizedRole\n";

if (isset(Permission::ROLE_PERMISSIONS[$normalizedRole])) {
    echo "  ✓ Rôle valide\n";
    $permissions = Permission::getForRole($normalizedRole);
    echo "    Permissions: " . count($permissions) . " assignées\n";
} else {
    echo "  ✗ Rôle INVALIDE\n";
    echo "    Rôles disponibles: " . implode(', ', array_keys(Permission::ROLE_PERMISSIONS)) . "\n";
}

echo "\n";

// Étape 5: Vérifier la permission dashboard.view
echo "Étape 5: Vérification de la permission dashboard.view...\n";
if (Permission::has('dashboard.view', $normalizedRole)) {
    echo "  ✓ Permission dashboard.view accordée\n";
} else {
    echo "  ✗ Permission dashboard.view NON accordée\n";
    echo "    → Le middleware AdminMiddleware va refuser l'accès\n";
}

echo "\n";

// Résumé
echo "=== RÉSUMÉ ===\n";
echo "Si vous voyez 'Accès réservé aux administrateurs', c'est que :\n";
echo "1. Soit le mot de passe est incorrect\n";
echo "2. Soit le rôle n'est pas reconnu\n";
echo "3. Soit la permission dashboard.view n'est pas accordée\n\n";

echo "Vérifiez les étapes ci-dessus pour identifier le problème.\n";

<?php

/**
 * Script de réinitialisation du mot de passe Super Admin
 * 
 * Usage: php database/fix_superadmin.php
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../App/Models/BaseModel.php';
require_once __DIR__ . '/../App/Models/AdminModel.php';

use App\Models\AdminModel;

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "=== Réinitialisation du Super Admin ===\n\n";

// Récupérer la connexion à la base de données
$db = \Database::getConnection();
$adminModel = new AdminModel($db);

// Nouveau mot de passe pour le Super Admin
$newPassword = 'SuperAdmin123!';
$email = 'missambounawane8@gmail.com';

echo "Réinitialisation du mot de passe pour :\n";
echo "  Email: $email\n";
echo "  Nouveau mot de passe: $newPassword\n\n";

// Vérifier que l'utilisateur existe
$stmt = $db->prepare('SELECT id_gestion, nom, email FROM admin WHERE email = :email');
$stmt->execute([':email' => $email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "✗ Utilisateur non trouvé avec l'email: $email\n";
    exit;
}

echo "✓ Utilisateur trouvé:\n";
echo "  ID: {$admin['id_gestion']}\n";
echo "  Nom: {$admin['nom']}\n\n";

// Hasher le nouveau mot de passe
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

if ($hashedPassword === false) {
    echo "✗ Erreur lors du hashage du mot de passe\n";
    exit;
}

echo "✓ Nouveau hash généré: $hashedPassword\n\n";

// Mettre à jour dans la base de données
if ($adminModel->updatePassword($admin['id_gestion'], $hashedPassword)) {
    echo "✓ Mot de passe mis à jour avec succès !\n\n";
    echo "Vous pouvez maintenant vous connecter avec :\n";
    echo "  Email: $email\n";
    echo "  Mot de passe: $newPassword\n\n";

    // Vérifier que le hash fonctionne
    echo "Vérification du hash...\n";
    if (password_verify($newPassword, $hashedPassword)) {
        echo "  ✓ Hash valide et fonctionnel\n";
    } else {
        echo "  ✗ Erreur: le hash ne fonctionne pas\n";
    }
} else {
    echo "✗ Erreur lors de la mise à jour du mot de passe\n";
}

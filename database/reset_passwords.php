<?php
/**
 * Script de réinitialisation des mots de passe administrateurs
 * 
 * Ce script permet de définir de nouveaux mots de passe en clair
 * qui seront automatiquement hashés et stockés dans la base de données.
 * 
 * Usage: php database/reset_passwords.php
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

// Récupérer la connexion à la base de données
$db = \Database::getConnection();

echo "=== Réinitialisation des Mots de Passe ===\n\n";

$adminModel = new AdminModel($db);

// Définir les nouveaux mots de passe (en clair)
$newPasswords = [
    1 => 'SuperAdmin123!',    // Super Admin
    2 => 'Admin123!',         // Admin
    3 => 'Moderateur123!',    // Modérateur
    4 => 'Support123!',       // Support
];

$success = 0;
$errors = 0;

foreach ($newPasswords as $id => $password) {
    echo "Traitement de l'administrateur #$id...\n";
    
    // Vérifier que l'administrateur existe
    $stmt = $db->prepare('SELECT id_gestion, nom, email FROM admin WHERE id_gestion = :id');
    $stmt->execute([':id' => $id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        echo "  ✗ Administrateur #$id introuvable\n\n";
        $errors++;
        continue;
    }
    
    echo "  Nom: {$admin['nom']}\n";
    echo "  Email: {$admin['email']}\n";
    
    // Hasher le nouveau mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    if ($hashedPassword === false) {
        echo "  ✗ Erreur lors du hashage du mot de passe\n\n";
        $errors++;
        continue;
    }
    
    // Mettre à jour dans la base de données
    if ($adminModel->updatePassword($id, $hashedPassword)) {
        echo "  ✓ Mot de passe réinitialisé avec succès\n";
        echo "    Nouveau mot de passe: $password\n\n";
        $success++;
    } else {
        echo "  ✗ Erreur lors de la mise à jour\n\n";
        $errors++;
    }
}

echo "=== Résumé ===\n";
echo "Réussis: $success\n";
echo "Erreurs: $errors\n";

if ($success > 0) {
    echo "\n✓ Réinitialisation terminée !\n";
    echo "\nVous pouvez maintenant vous connecter avec :\n";
    echo "- Super Admin: missambounawane8@gmail.com / SuperAdmin123!\n";
    echo "- Admin: admin@ndigitmarket.com / Admin123!\n";
    echo "- Modérateur: moderateur@ndigitmarket.com / Moderateur123!\n";
    echo "- Support: support@ndigitmarket.com / Support123!\n";
} else {
    echo "\n✗ Aucune réinitialisation effectuée.\n";
}
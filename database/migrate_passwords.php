<?php



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

echo "=== Migration des mots de passe administrateurs ===\n\n";

$adminModel = new AdminModel($db);

// Récupérer tous les administrateurs
$stmt = $db->query('SELECT id_gestion, nom, email, mdp FROM admin');
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

$migrated = 0;
$skipped = 0;
$errors = 0;

foreach ($admins as $admin) {
    $id = $admin['id_gestion'];
    $nom = $admin['nom'];
    $email = $admin['email'];
    $password = $admin['mdp'];

    echo "Traitement: $nom ($email) - ID: $id\n";

    // Vérifier si le mot de passe est déjà un hash valide
    if (password_get_info($password)['algo'] !== null) {
        echo "  ✓ Mot de passe déjà hashé, ignoré\n\n";
        $skipped++;
        continue;
    }

    // Vérifier si c'est un mot de passe en clair (non vide)
    if (empty($password)) {
        echo "  ⚠ Mot de passe vide, ignoré\n\n";
        $skipped++;
        continue;
    }

    // Convertir en hash sécurisé
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($hashedPassword === false) {
        echo "  ✗ Erreur lors du hashage\n\n";
        $errors++;
        continue;
    }

    // Mettre à jour dans la base de données
    if ($adminModel->updatePassword($id, $hashedPassword)) {
        echo "  ✓ Mot de passe migré avec succès\n\n";
        $migrated++;
    } else {
        echo "  ✗ Erreur lors de la mise à jour\n\n";
        $errors++;
    }
}

echo "=== Résumé de la migration ===\n";
echo "Total administrateurs: " . count($admins) . "\n";
echo "Migrés: $migrated\n";
echo "Ignorés (déjà hashés ou vides): $skipped\n";
echo "Erreurs: $errors\n";

if ($migrated > 0) {
    echo "\n✓ Migration terminée avec succès!\n";
} else {
    echo "\n✓ Aucune migration nécessaire.\n";
}

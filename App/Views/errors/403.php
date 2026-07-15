<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

http_response_code(403);

$user = \App\Core\Auth::user();
$role = \App\Core\Auth::role();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Refusé - 403</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #e74c3c;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 30px;
        }

        h1 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #7f8c8d;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .user-info {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 30px;
            text-align: left;
            border-radius: 5px;
        }

        .user-info p {
            margin: 5px 0;
            color: #555;
            font-size: 14px;
        }

        .user-info strong {
            color: #2c3e50;
        }

        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-logout {
            display: inline-block;
            background: #e74c3c;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            margin-left: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.6);
        }

        .actions {
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 80px;
            }

            h1 {
                font-size: 24px;
            }

            .error-message {
                font-size: 16px;
            }

            .btn-logout {
                margin-left: 0;
                margin-top: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">🔒</div>
        <div class="error-code">403</div>
        <h1>Accès Refusé</h1>
        <p class="error-message">
            Désolé, vous n'avez pas les permissions nécessaires pour accéder à cette page.
            <br>
            Veuillez contacter votre administrateur si vous pensez qu'il s'agit d'une erreur.
        </p>

        <?php if ($user && $role): ?>
            <div class="user-info">
                <p><strong>Utilisateur connecté :</strong> <?php echo htmlspecialchars($user['name'] ?? $user['email']); ?></p>
                <p><strong>Rôle :</strong> <?php echo htmlspecialchars($role); ?></p>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/index.php?route=admin/dashboard" class="btn-home">
                🏠 Retour au Dashboard
            </a>

            <?php if ($user): ?>
                <a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/index.php?route=logout" class="btn-logout">
                    🚪 Déconnexion
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
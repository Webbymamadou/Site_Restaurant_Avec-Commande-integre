<?php
/**
 * login.php
 * Page de connexion de l'administrateur
 */
require_once 'config.php';

// Si l'utilisateur est déjà connecté, redirection vers parametres.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: parametres.php");
    exit;
}

$error_msg = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        // Recherche de l'utilisateur en base de données
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // Création de la session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            
            header("Location: parametres.php");
            exit;
        } else {
            $error_msg = "Identifiants incorrects.";
        }
    } else {
        $error_msg = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - <?= htmlspecialchars($settings['restaurant_name']) ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1a1a1a; /* Fond sombre */
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: #2c2c2c; /* Carte grise sombre */
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .text-accent {
            color: <?= htmlspecialchars($settings['theme_color_accent']) ?>;
        }
        .btn-accent {
            background-color: <?= htmlspecialchars($settings['theme_color_accent']) ?>;
            border: none;
            color: white;
            font-weight: 600;
        }
        .btn-accent:hover {
            opacity: 0.9;
            color: white;
        }
        .form-control {
            background-color: #3d3d3d;
            border: 1px solid #4d4d4d;
            color: white;
        }
        .form-control:focus {
            background-color: #3d3d3d;
            border-color: <?= htmlspecialchars($settings['theme_color_accent']) ?>;
            color: white;
            box-shadow: none;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Admin <span class="text-accent">Login</span></h2>
            <p class="text-secondary small">Accès réservé à l'administrateur</p>
        </div>

        <?php if ($error_msg): ?>
            <div class="alert alert-danger py-2 small text-center" role="alert">
                <?= $error_msg ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label small">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label small">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-accent w-100 py-2">Se connecter</button>
        </form>

        <div class="text-center mt-4">
            <a href="index.php" class="text-secondary text-decoration-none small">← Retour au site</a>
        </div>
    </div>

</body>
</html>

<?php
/**
 * parametres.php
 * Interface de gestion du site : Changement logo, fond, couleurs, nom, infos...
 */
require_once 'config.php';

// --- PROTECTION DE LA PAGE ---
// Si l'utilisateur n'est pas connecté, redirection vers login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Logique de déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Initialisation des messages de retour
$success_msg = '';
$error_msg = '';

// --- Traitement du formulaire de mise à jour (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    // Récupération et nettoyage des données textuelles
    $restaurant_name = trim($_POST['restaurant_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $theme_color_primary = trim($_POST['theme_color_primary'] ?? '');
    $theme_color_secondary = trim($_POST['theme_color_secondary'] ?? '');
    $theme_color_accent = trim($_POST['theme_color_accent'] ?? '');
    $contact_phone = trim($_POST['contact_phone'] ?? '');
    $contact_email = trim($_POST['contact_email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment_number_wave = trim($_POST['payment_number_wave'] ?? '');
    $payment_number_orange = trim($_POST['payment_number_orange'] ?? '');

    // On conserve les chemins actuels par défaut
    $logo_path = $settings['logo_path'];
    $hero_image_path = $settings['hero_image_path'];

    $upload_dir = 'assets/images/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // --- 1. Gestion de l'upload du logo ---
    if (isset($_FILES['logo_upload']) && $_FILES['logo_upload']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['logo_upload']['tmp_name'];
        $name = time() . '_logo_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "", basename($_FILES['logo_upload']['name']));
        $target_file = $upload_dir . $name;
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($tmp_name);

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($tmp_name, $target_file)) {
                $logo_path = $target_file;
            } else {
                $error_msg = "Erreur lors de l'enregistrement du logo.";
            }
        } else {
            $error_msg = "Format de logo non autorisé.";
        }
    }

    // --- 2. Gestion de l'upload de l'image de fond (Hero) ---
    if (isset($_FILES['hero_upload']) && $_FILES['hero_upload']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['hero_upload']['tmp_name'];
        $name = time() . '_hero_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "", basename($_FILES['hero_upload']['name']));
        $target_file = $upload_dir . $name;
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($tmp_name);

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($tmp_name, $target_file)) {
                $hero_image_path = $target_file;
            } else {
                $error_msg = "Erreur lors de l'enregistrement de l'image de fond.";
            }
        } else {
            $error_msg = "Format d'image de fond non autorisé.";
        }
    }

    // --- Enregistrement en base de données ---
    if (!empty($restaurant_name) && empty($error_msg)) {
        try {
            // Mise à jour de la table 'settings'
            $stmt = $pdo->prepare("
                UPDATE settings SET 
                restaurant_name = ?, 
                description = ?,
                logo_path = ?, 
                hero_image_path = ?,
                theme_color_primary = ?, 
                theme_color_secondary = ?, 
                theme_color_accent = ?, 
                contact_phone = ?, 
                contact_email = ?, 
                address = ?,
                payment_number_wave = ?,
                payment_number_orange = ?
                WHERE id = 1
            ");
            $stmt->execute([
                $restaurant_name, 
                $description,
                $logo_path, 
                $hero_image_path,
                $theme_color_primary, 
                $theme_color_secondary, 
                $theme_color_accent, 
                $contact_phone, 
                $contact_email, 
                $address,
                $payment_number_wave,
                $payment_number_orange
            ]);
            
            $success_msg = "Paramètres mis à jour avec succès !";
            
            // Rechargement des paramètres
            $stmt = $pdo->prepare("SELECT * FROM settings LIMIT 1");
            $stmt->execute();
            $settings = array_merge($default_settings, $stmt->fetch(PDO::FETCH_ASSOC) ?: []);

        } catch(PDOException $e) {
            $error_msg = "Erreur BDD : " . $e->getMessage();
        }
    } else if (empty($restaurant_name)) {
        $error_msg = "Le nom du restaurant est obligatoire.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - <?= htmlspecialchars($settings['restaurant_name']) ?></title>
    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; color: #333; }
        .admin-header { background-color: #1a1a1a; color: white; padding: 1rem 0; margin-bottom: 2rem; border-bottom: 4px solid <?= htmlspecialchars($settings['theme_color_accent']) ?>; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-label { font-weight: 600; }
        .current-logo { max-height: 80px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .current-hero { max-height: 120px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; object-fit: cover; }
    </style>
</head>
<body>

    <header class="admin-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="m-0">⚙️ Paramètres du Site</h4>
            <div>
                <a href="commandes.php" class="btn btn-warning btn-sm me-2 fw-bold text-dark"><i class="fa-solid fa-shopping-basket me-1"></i> Voir les commandes</a>
                <a href="index.php" class="btn btn-outline-light btn-sm me-2">Retour au site</a>
                <a href="parametres.php?logout=1" class="btn btn-danger btn-sm">Déconnexion</a>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Feedback utilisateur -->
                <?php if($success_msg): ?>
                    <div class="alert alert-success shadow-sm"><?= $success_msg ?></div>
                <?php endif; ?>
                
                <?php if($error_msg): ?>
                    <div class="alert alert-danger shadow-sm"><?= $error_msg ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header bg-white p-4 border-bottom">
                        <h5 class="m-0">Informations & Personnalisation</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="parametres.php" enctype="multipart/form-data">
                            
                            <h6 class="text-muted mb-3 border-bottom pb-2">Identité du Restaurant</h6>
                            <div class="mb-3">
                                <label for="restaurant_name" class="form-label">Nom du Restaurant</label>
                                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" value="<?= htmlspecialchars($settings['restaurant_name']) ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label">Histoire / À Propos</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($settings['description']) ?></textarea>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="logo_upload" class="form-label">Logo du Site</label>
                                    <div class="mb-2">
                                        <?php if(!empty($settings['logo_path'])): ?>
                                            <img src="<?= htmlspecialchars($settings['logo_path']) ?>" alt="Logo" class="current-logo mb-2">
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="logo_upload" name="logo_upload" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="hero_upload" class="form-label">Image de Fond (Accueil)</label>
                                    <div class="mb-2">
                                        <?php if(!empty($settings['hero_image_path'])): ?>
                                            <img src="<?= htmlspecialchars($settings['hero_image_path']) ?>" alt="Hero" class="current-hero mb-2">
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="hero_upload" name="hero_upload" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-muted mb-3 border-bottom pb-2">Design (Couleurs)</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fond (Principal)</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="theme_color_primary" name="theme_color_primary" value="<?= htmlspecialchars($settings['theme_color_primary']) ?>">
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($settings['theme_color_primary']) ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cartes (Secondaire)</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="theme_color_secondary" name="theme_color_secondary" value="<?= htmlspecialchars($settings['theme_color_secondary']) ?>">
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($settings['theme_color_secondary']) ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Accent (Rouge/Boutons)</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="theme_color_accent" name="theme_color_accent" value="<?= htmlspecialchars($settings['theme_color_accent']) ?>">
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($settings['theme_color_accent']) ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-muted mb-3 border-bottom pb-2">Contact & Localisation</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_phone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone']) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email']) ?>">
                                </div>
                                <div class="col-12 mb-4">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($settings['address']) ?>">
                                </div>
                            </div>

                            <h6 class="text-muted mb-3 border-bottom pb-2">Paiement Mobile Money</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payment_number_wave" class="form-label">Numéro Wave</label>
                                    <input type="text" class="form-control" id="payment_number_wave" name="payment_number_wave" value="<?= htmlspecialchars($settings['payment_number_wave'] ?? '') ?>" placeholder="Ex: 771234567">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_number_orange" class="form-label">Numéro Orange Money</label>
                                    <input type="text" class="form-control" id="payment_number_orange" name="payment_number_orange" value="<?= htmlspecialchars($settings['payment_number_orange'] ?? '') ?>" placeholder="Ex: 771234567">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" name="update_settings" class="btn btn-primary btn-lg" style="background-color: <?= htmlspecialchars($settings['theme_color_accent']) ?>; border-color: <?= htmlspecialchars($settings['theme_color_accent']) ?>;">
                                    Enregistrer les Changements
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('input[type="color"]').forEach(input => {
            input.addEventListener('input', function() {
                this.nextElementSibling.value = this.value;
            });
        });
    </script>
</body>
</html>

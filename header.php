<?php 
/**
 * header.php
 * Entête commune à toutes les pages : Inclusion config, Balises meta, Navigation
 */
require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre dynamique chargé depuis la base de données -->
    <title><?= htmlspecialchars($settings['restaurant_name']) ?></title>
    
    <!-- Google Fonts : Inter pour le texte, Playfair pour les titres -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS : Framework pour la mise en page responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AOS Animation CSS : Bibliothèque pour les animations au scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- FontAwesome : Pour les icônes (Panier, Paramètres, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles Personnalisés locaux -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Injection dynamique des couleurs choisies dans les Paramètres (White Label) -->
    <style>
        :root {
            --color-primary: <?= htmlspecialchars($settings['theme_color_primary']) ?>;
            --color-secondary: <?= htmlspecialchars($settings['theme_color_secondary']) ?>;
            --color-accent: <?= htmlspecialchars($settings['theme_color_accent']) ?>;
        }
        .cart-badge {
            font-size: 0.6rem;
            vertical-align: top;
            margin-left: -5px;
        }
    </style>
</head>
<body>
    <!-- Barre de progression de lecture -->
    <div class="scroll-progress"></div>

    <!-- Barre de Navigation fixe en haut -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar" data-aos="fade-down" data-aos-duration="1000">
        <div class="container">
            <!-- Logo et Nom du Restaurant -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <?php if(!empty($settings['logo_path'])): ?>
                    <img src="<?= htmlspecialchars($settings['logo_path']) ?>" alt="Logo" width="50" height="50" class="me-2 rounded-circle" style="object-fit: cover;">
                <?php endif; ?>
                <span class="brand-text"><?= htmlspecialchars($settings['restaurant_name']) ?></span>
            </a>

            <!-- Bouton pour mobile -->
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Liens de navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#apropos">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#menu">Notre Menu</a>
                    </li>
                    <!-- Icône Panier avec compteur -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="panier.php">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <?php 
                            $cart_count = array_sum($_SESSION['cart']); 
                            if ($cart_count > 0):
                            ?>
                                <span class="badge rounded-pill bg-danger cart-badge"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-accent" href="parametres.php"><i class="fa-solid fa-cog"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

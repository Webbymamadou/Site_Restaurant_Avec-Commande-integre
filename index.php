<?php 
/**
 * index.php
 * Page d'accueil principale : Hero, À propos, Menu, Commande et Contact
 */
include 'header.php'; 
?>

<!-- Hero Section : Accueil avec image de fond plein écran -->
<section class="hero-section d-flex align-items-center" style="min-height: 100vh;">
    <!-- Div dédiée à l'animation de fond -->
    <div class="hero-bg" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= htmlspecialchars($settings['hero_image_path']) ?>');"></div>
    
    <div class="container text-center">
        <!-- Nom du restaurant dynamique -->
        <h1 class="display-3 text-white fw-bold mb-4" data-aos="fade-up">Bienvenue chez <span class="text-accent"><?= htmlspecialchars($settings['restaurant_name']) ?></span></h1>
        <p class="lead text-light mb-5" data-aos="fade-up" data-aos-delay="200">L'art de la viande grillée et braisée. Une expérience culinaire inoubliable.</p>
        <div data-aos="fade-up" data-aos-delay="400">
            <a href="#menu" class="btn btn-outline-light btn-lg me-3 custom-btn">Découvrir le Menu</a>
            <a href="#commander" class="btn btn-accent btn-lg custom-btn-accent">Commander !</a>
        </div>
    </div>
</section>

<!-- À Propos Section : Histoire du restaurant -->
<section id="apropos" class="py-5 bg-dark">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <img src="assets/images/spencer-davis-5dsZnCVDHd0-unsplash.jpg" alt="Notre histoire" class="img-fluid rounded shadow-lg border border-secondary">
            </div>
            <div class="col-lg-6 px-lg-5" data-aos="fade-left">
                <h2 class="display-5 fw-bold text-white mb-4">À Propos de <span class="text-accent">Nous</span></h2>
                <div class="text-secondary-light lead" style="line-height: 1.8;">
                    <!-- Description chargée depuis les paramètres -->
                    <?= nl2br(htmlspecialchars($settings['description'])) ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Section : Liste des plats -->
<section id="menu" class="py-5 bg-primary-dark">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold text-white mb-3">Notre Menu <span class="text-accent">Gourmand</span></h2>
            <p class="text-secondary-light">Des viandes sélectionnées avec soin, grillées à la perfection.</p>
        </div>

        <div class="row g-4">
            <?php
            // Récupération des plats disponibles classés par catégorie
            $stmtMenu = $pdo->prepare("
                SELECT m.*, c.name as category_name 
                FROM menu_items m 
                LEFT JOIN categories c ON m.category_id = c.id 
                WHERE is_available = 1
                ORDER BY c.id, m.name
            ");
            $stmtMenu->execute();
            $menuItems = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);

            if(count($menuItems) > 0):
                foreach($menuItems as $index => $item): 
                    // Petit délai pour l'effet d'apparition en cascade
                    $delay = ($index % 3) * 100;
            ?>
            <!-- Carte d'un plat -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                <div class="card h-100 menu-card bg-secondary-dark border-0">
                    <div class="card-img-wrapper">
                        <img src="<?= htmlspecialchars($item['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="price-badge text-accent fw-bold"><?= number_format($item['price'], 0, ',', ' ') ?> FCFA</div>
                    </div>
                    <div class="card-body">
                        <span class="badge bg-dark border border-secondary mb-2 text-secondary-light"><?= htmlspecialchars($item['category_name']) ?></span>
                        <h4 class="card-title text-white fw-bold"><?= htmlspecialchars($item['name']) ?></h4>
                        <p class="card-text text-secondary-light small"><?= htmlspecialchars($item['description']) ?></p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                        <!-- Lien vers l'action d'ajout au panier -->
                        <a href="cart_action.php?action=add&id=<?= $item['id'] ?>" class="btn btn-accent w-100 custom-btn-accent">
                            <i class="fa-solid fa-plus me-2"></i> Ajouter au Panier
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                endforeach; 
            else:
            ?>
                <div class="col-12 text-center text-white">
                    <p>Le menu est en cours de mise à jour...</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Commande Section : Formulaire de contact -->
<section id="commander" class="py-5 bg-dark">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                <h2 class="display-5 fw-bold text-white mb-3">Passez votre <span class="text-accent">Commande</span></h2>
                <p class="text-secondary-light mb-4">Remplissez le formulaire ci-contre pour passer votre commande. Notre équipe vous contactera rapidement pour la confirmation et la livraison.</p>
                <img src="assets/images/spencer-davis-5dsZnCVDHd0-unsplash.jpg" alt="Grillades" class="img-fluid rounded shadow-lg border border-secondary">
            </div>
            <div class="col-lg-6 offset-lg-1" data-aos="fade-left">
                <div class="card bg-secondary-dark border-0 shadow-lg form-card">
                    <div class="card-body p-4 p-md-5">
                        
                        <?php
                        // Logique de traitement de la commande (POST)
                        $success_msg = '';
                        $error_msg = '';
                        
                        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
                            $name = trim($_POST['customer_name'] ?? '');
                            $phone = trim($_POST['customer_phone'] ?? '');
                            $details = trim($_POST['order_details'] ?? '');
                            
                            if(!empty($name) && !empty($phone) && !empty($details)) {
                                try {
                                    $stmtOrder = $pdo->prepare("INSERT INTO orders (customer_name, customer_phone, order_details) VALUES (?, ?, ?)");
                                    $stmtOrder->execute([$name, $phone, $details]);
                                    $success_msg = "Votre commande a été enregistrée avec succès ! Nous vous contactons sous peu.";
                                } catch(PDOException $e) {
                                    $error_msg = "Une erreur s'est produite lors de l'enregistrement de votre commande.";
                                }
                            } else {
                                $error_msg = "Veuillez remplir tous les champs.";
                            }
                        }
                        ?>

                        <!-- Messages d'alerte -->
                        <?php if($success_msg): ?>
                            <div class="alert alert-success bg-transparent border-success text-success" role="alert">
                                <?= $success_msg ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error_msg): ?>
                            <div class="alert alert-danger bg-transparent border-danger text-danger" role="alert">
                                <?= $error_msg ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire de commande -->
                        <form method="POST" action="index.php#commander">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label text-secondary-light">Votre Nom</label>
                                <input type="text" class="form-control custom-input" id="customer_name" name="customer_name" required placeholder="Ex: Jean Dupont">
                            </div>
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label text-secondary-light">Numéro de Téléphone</label>
                                <input type="tel" class="form-control custom-input" id="customer_phone" name="customer_phone" required placeholder="Ex: 77 123 45 67">
                            </div>
                            <div class="mb-4">
                                <label for="order_details" class="form-label text-secondary-light">Que souhaitez-vous commander ?</label>
                                <textarea class="form-control custom-input" id="order_details" name="order_details" rows="4" required placeholder="Ex: 2 Poulet Braisé & Frites, 1 Pizza Feu de Bois..."></textarea>
                            </div>
                            <button type="submit" name="submit_order" class="btn btn-accent btn-lg w-100 custom-btn-accent">Confirmer ma commande</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section : Coordonnées et Adresse -->
<section id="contact" class="py-5 bg-primary-dark">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold text-white mb-3">Nos <span class="text-accent">Coordonnées</span></h2>
            <p class="text-secondary-light">Venez nous rendre visite ou contactez-nous pour toute question.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Adresse -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 bg-secondary-dark border-0 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 text-accent mb-3">📍</div>
                        <h4 class="text-white mb-3">Adresse</h4>
                        <p class="text-secondary-light mb-0"><?= nl2br(htmlspecialchars($settings['address'])) ?></p>
                    </div>
                </div>
            </div>
            <!-- Téléphone -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 bg-secondary-dark border-0 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 text-accent mb-3">📞</div>
                        <h4 class="text-white mb-3">Téléphone</h4>
                        <p class="text-secondary-light mb-0"><?= htmlspecialchars($settings['contact_phone']) ?></p>
                    </div>
                </div>
            </div>
            <!-- Email -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 bg-secondary-dark border-0 text-center p-4">
                    <div class="card-body">
                        <div class="display-4 text-accent mb-3">✉️</div>
                        <h4 class="text-white mb-3">Email</h4>
                        <p class="text-secondary-light mb-0"><?= htmlspecialchars($settings['contact_email']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

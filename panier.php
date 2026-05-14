<?php
/**
 * panier.php
 * Affichage du contenu du panier et finalisation de la commande
 */
include 'header.php';

// Récupération des détails des articles du panier depuis la BDD
$cart_items_details = [];
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $cart_items_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$total_price = 0;
?>

<!-- Section Panier -->
<section class="py-5 bg-dark" style="min-height: 80vh; padding-top: 100px !important;">
    <div class="container py-4">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold text-white mb-3">Votre <span class="text-accent">Panier</span></h2>
            <p class="text-secondary-light">Récapitulatif de votre commande gourmande.</p>
        </div>

        <div class="row g-4">
            <!-- Liste des articles -->
            <div class="col-lg-8" data-aos="fade-right">
                <div class="card bg-secondary-dark border-0 shadow-lg">
                    <div class="card-body p-0">
                        <?php if (empty($cart_items_details)): ?>
                            <div class="p-5 text-center text-secondary-light">
                                <i class="fa-solid fa-cart-shopping display-1 mb-4 opacity-25"></i>
                                <h4>Votre panier est vide</h4>
                                <p class="mb-4">Il est temps de découvrir notre délicieux menu !</p>
                                <a href="index.php#menu" class="btn btn-accent px-4">Voir le Menu</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle mb-0">
                                    <thead class="bg-primary-dark">
                                        <tr>
                                            <th class="ps-4 py-3">Plat</th>
                                            <th class="py-3">Prix</th>
                                            <th class="py-3 text-center">Quantité</th>
                                            <th class="py-3 text-end">Total</th>
                                            <th class="py-3 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($cart_items_details as $item): 
                                            $qty = $_SESSION['cart'][$item['id']];
                                            $subtotal = $item['price'] * $qty;
                                            $total_price += $subtotal;
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="50" height="50" class="rounded me-3" style="object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($item['name']) ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= number_format($item['price'], 0, ',', ' ') ?> FCFA</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <a href="cart_action.php?action=update&id=<?= $item['id'] ?>&qty=<?= $qty - 1 ?>" class="btn btn-sm btn-outline-secondary px-2">-</a>
                                                    <span class="fw-bold mx-2"><?= $qty ?></span>
                                                    <a href="cart_action.php?action=update&id=<?= $item['id'] ?>&qty=<?= $qty + 1 ?>" class="btn btn-sm btn-outline-secondary px-2">+</a>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-accent"><?= number_format($subtotal, 0, ',', ' ') ?> FCFA</td>
                                            <td class="text-center">
                                                <a href="cart_action.php?action=remove&id=<?= $item['id'] ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 text-end bg-primary-dark border-top border-secondary">
                                <a href="cart_action.php?action=clear" class="btn btn-sm btn-outline-danger me-2">Vider le panier</a>
                                <a href="index.php#menu" class="btn btn-sm btn-outline-light">Continuer mes achats</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Résumé et Formulaire de commande -->
            <div class="col-lg-4" data-aos="fade-left">
                <div class="card bg-secondary-dark border-0 shadow-lg position-sticky" style="top: 100px;">
                    <div class="card-body p-4">
                        <h4 class="text-white fw-bold mb-4">Résumé</h4>
                        <div class="d-flex justify-content-between mb-2 text-secondary-light">
                            <span>Articles :</span>
                            <span><?= array_sum($_SESSION['cart']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 text-white fw-bold fs-5 border-top border-secondary pt-3">
                            <span>TOTAL :</span>
                            <span class="text-accent"><?= number_format($total_price, 0, ',', ' ') ?> FCFA</span>
                        </div>

                        <?php if (!empty($cart_items_details)): ?>
                            <hr class="border-secondary my-4">
                            <h5 class="text-white mb-3">Valider la commande</h5>
                            
                            <?php
                            // Logique de traitement de la commande
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
                                $name = trim($_POST['customer_name'] ?? '');
                                $phone = trim($_POST['customer_phone'] ?? '');
                                
                                if (!empty($name) && !empty($phone)) {
                                    // Création du résumé de la commande pour la BDD
                                    $order_summary = "COMMANDE PANIER :\n";
                                    foreach ($cart_items_details as $item) {
                                        $qty = $_SESSION['cart'][$item['id']];
                                        $order_summary .= "- " . $item['name'] . " x" . $qty . " (" . number_format($item['price'] * $qty, 0, ',', ' ') . " FCFA)\n";
                                    }
                                    $order_summary .= "\nTOTAL : " . number_format($total_price, 0, ',', ' ') . " FCFA";

                                    try {
                                        $stmtOrder = $pdo->prepare("INSERT INTO orders (customer_name, customer_phone, order_details) VALUES (?, ?, ?)");
                                        $stmtOrder->execute([$name, $phone, $order_summary]);
                                        
                                        // Vider le panier après succès
                                        $_SESSION['cart'] = [];
                                        
                                        echo "<div class='alert alert-success bg-transparent border-success text-success p-4 mb-3'>
                                                <h5 class='fw-bold'><i class='fa-solid fa-check-circle me-2'></i> Commande enregistrée !</h5>
                                                <p class='mb-3 small'>Pour valider votre commande, merci d'effectuer le paiement Mobile Money :</p>
                                                <div class='d-flex flex-column gap-2'>";
                                        
                                        if(!empty($settings['payment_number_wave'])) {
                                            echo "<div class='p-2 bg-dark rounded border border-info small'><i class='fa-solid fa-money-bill-wave text-info me-2'></i> <strong>Wave :</strong> " . htmlspecialchars($settings['payment_number_wave']) . "</div>";
                                        }
                                        if(!empty($settings['payment_number_orange'])) {
                                            echo "<div class='p-2 bg-dark rounded border border-warning small'><i class='fa-solid fa-mobile-screen text-warning me-2'></i> <strong>Orange Money :</strong> " . htmlspecialchars($settings['payment_number_orange']) . "</div>";
                                        }
                                        
                                        echo "  </div>
                                                <p class='mt-3 mb-0 x-small text-secondary'>Un agent vous contactera dès réception du paiement.</p>
                                              </div>";
                                        echo "<script>setTimeout(() => { window.location.href = 'index.php'; }, 10000);</script>";
                                    } catch (PDOException $e) {
                                        echo "<div class='alert alert-danger bg-transparent border-danger text-danger small mb-3'>Erreur BDD.</div>";
                                    }
                                }
                            }
                            ?>

                            <form method="POST" action="panier.php">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label text-secondary-light small">Votre Nom</label>
                                    <input type="text" class="form-control custom-input bg-dark" id="customer_name" name="customer_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label text-secondary-light small">Téléphone</label>
                                    <input type="tel" class="form-control custom-input bg-dark" id="customer_phone" name="customer_phone" required>
                                </div>
                                <button type="submit" name="submit_order" class="btn btn-commander-panier w-100 py-3 mt-3 fw-bold">COMMANDER MAINTENANT</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 py-3 mt-3 fw-bold" disabled>PANIER VIDE</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

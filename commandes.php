<?php
/**
 * commandes.php
 * Dashboard d'administration pour voir et gérer les commandes
 */
require_once 'config.php';

// --- PROTECTION DE LA PAGE ---
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$success_msg = '';

// --- ACTIONS SUR LES COMMANDES ---
if (isset($_GET['action'])) {
    $order_id = $_GET['id'] ?? '';
    
    if ($_GET['action'] === 'complete' && !empty($order_id)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'termine' WHERE id = ?");
        $stmt->execute([$order_id]);
        $success_msg = "Commande marquée comme terminée.";
    }
    
    if ($_GET['action'] === 'delete' && !empty($order_id)) {
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        $success_msg = "Commande supprimée.";
    }
}

// Récupération de toutes les commandes (les nouvelles en premier)
// Note: Utilisation de 'order_date' car c'est le nom de la colonne dans la base
$stmt = $pdo->query("SELECT * FROM orders ORDER BY order_date DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - <?= htmlspecialchars($settings['restaurant_name']) ?></title>
    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f2f5; color: #333; }
        .admin-header { background-color: #1a1a1a; color: white; padding: 1.2rem 0; margin-bottom: 2.5rem; border-bottom: 4px solid <?= htmlspecialchars($settings['theme_color_accent']) ?>; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .container { max-width: 1100px; }
        
        /* Style des cartes de commandes */
        .order-card { 
            border: none; 
            border-radius: 15px; 
            background: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            overflow: hidden;
            border-left: 5px solid <?= htmlspecialchars($settings['theme_color_accent']) ?>;
            transition: all 0.3s ease;
        }
        .order-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .order-card.termine { border-left-color: #28a745; opacity: 0.8; }
        
        .card-header-custom { background: #fafafa; padding: 15px 25px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .card-body-custom { padding: 25px; }
        
        .client-info h5 { margin: 0; font-weight: 700; color: #1a1a1a; }
        .order-date { font-size: 0.85rem; color: #888; }
        
        .order-details-box { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 10px; 
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.95rem;
            color: #444;
            border: 1px dashed #ddd;
            margin: 15px 0;
        }
        
        .status-badge { font-weight: 600; padding: 6px 15px; border-radius: 50px; font-size: 0.75rem; }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-termine { background-color: #d4edda; color: #155724; }
        
        .action-btns .btn { border-radius: 8px; font-weight: 600; padding: 8px 15px; }
        .btn-wa { background-color: #25d366; color: white; border: none; }
        .btn-wa:hover { background-color: #1ebe57; color: white; }
    </style>
</head>
<body>

    <header class="admin-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="m-0"><i class="fa-solid fa-utensils me-2"></i> Dashboard Commandes</h4>
            <div>
                <a href="parametres.php" class="btn btn-light btn-sm fw-bold me-2"><i class="fa-solid fa-cog me-1"></i> Paramètres</a>
                <a href="index.php" class="btn btn-outline-light btn-sm">Retour au site</a>
            </div>
        </div>
    </header>

    <div class="container pb-5">
        
        <?php if($success_msg): ?>
            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> <?= $success_msg ?>
            </div>
        <?php endif; ?>

        <?php if(empty($orders)): ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076503.png" alt="Empty" width="100" class="mb-4 opacity-50">
                <h4 class="text-muted">Aucune commande reçue</h4>
                <p class="text-secondary">Les commandes de vos clients s'afficheront ici en temps réel.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($orders as $order): ?>
                <div class="col-12">
                    <div class="card order-card <?= $order['status'] === 'termine' ? 'termine' : '' ?>">
                        <div class="card-header-custom">
                            <div class="order-date">
                                <i class="fa-regular fa-calendar-alt me-1"></i> <?= date('d M Y à H:i', strtotime($order['order_date'])) ?>
                            </div>
                            <span class="status-badge <?= $order['status'] === 'termine' ? 'bg-termine' : 'bg-pending' ?>">
                                <?= $order['status'] === 'termine' ? 'TERMINÉE' : 'EN ATTENTE' ?>
                            </span>
                        </div>
                        <div class="card-body-custom">
                            <div class="row align-items-center">
                                <div class="col-md-4 client-info">
                                    <h5 class="mb-1 text-uppercase"><?= htmlspecialchars($order['customer_name']) ?></h5>
                                    <p class="text-primary fw-bold mb-3"><i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($order['customer_phone']) ?></p>
                                    
                                    <div class="action-btns d-flex gap-2">
                                        <a href="tel:<?= $order['customer_phone'] ?>" class="btn btn-outline-primary btn-sm flex-grow-1">
                                            <i class="fa-solid fa-phone"></i> Appeler
                                        </a>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order['customer_phone']) ?>?text=Bonjour%20<?= urlencode($order['customer_name']) ?>,%20nous%20avons%20reçu%20votre%20commande%20chez%20<?= urlencode($settings['restaurant_name']) ?>" target="_blank" class="btn btn-wa btn-sm flex-grow-1">
                                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="order-details-box">
                                        <?= nl2br(htmlspecialchars($order['order_details'])) ?>
                                    </div>
                                </div>
                                <div class="col-md-3 text-end">
                                    <div class="d-flex flex-column gap-2">
                                        <?php if($order['status'] !== 'termine'): ?>
                                            <a href="commandes.php?action=complete&id=<?= $order['id'] ?>" class="btn btn-success">
                                                <i class="fa-solid fa-check-double me-1"></i> Marquer prêt
                                            </a>
                                        <?php endif; ?>
                                        <a href="commandes.php?action=delete&id=<?= $order['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer définitivement cette commande ?')">
                                            <i class="fa-solid fa-trash me-1"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

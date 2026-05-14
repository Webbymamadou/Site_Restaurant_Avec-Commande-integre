<?php
/**
 * cart_action.php
 * Gère les actions sur le panier (Ajout, suppression, modification)
 */
require_once 'config.php';

$action = $_GET['action'] ?? '';
$item_id = $_GET['id'] ?? '';

if ($action === 'add' && !empty($item_id)) {
    // Si l'article existe déjà, on augmente la quantité
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]++;
    } else {
        $_SESSION['cart'][$item_id] = 1;
    }
    
    // Redirection vers l'accueil avec un message de succès (ou JSON pour AJAX)
    if (isset($_GET['ajax'])) {
        echo json_encode(['success' => true, 'count' => array_sum($_SESSION['cart'])]);
        exit;
    }
    header("Location: index.php#menu");
    exit;
}

if ($action === 'remove' && !empty($item_id)) {
    unset($_SESSION['cart'][$item_id]);
    header("Location: panier.php");
    exit;
}

if ($action === 'update' && !empty($item_id)) {
    $qty = intval($_GET['qty'] ?? 1);
    if ($qty <= 0) {
        unset($_SESSION['cart'][$item_id]);
    } else {
        $_SESSION['cart'][$item_id] = $qty;
    }
    header("Location: panier.php");
    exit;
}

if ($action === 'clear') {
    $_SESSION['cart'] = [];
    header("Location: panier.php");
    exit;
}

header("Location: index.php");
exit;

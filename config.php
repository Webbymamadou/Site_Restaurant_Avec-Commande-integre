<?php
/**
 * config.php
 * Fichier de configuration centrale : Connexion BDD et Paramètres globaux
 */

// Démarrage de la session pour la gestion des données utilisateur
session_start();

// --- Configuration des accès à la base de données ---
$db_host = '127.0.0.1'; // Utilisation de l'IP directe pour éviter les soucis de résolution localhost sur Windows
$db_name = 'gourmand';  // Nom de la base de données importée
$db_user = 'root';      // Utilisateur par défaut de MySQL sur XAMPP
$db_pass = '';          // Mot de passe par défaut (vide)

try {
    // Création de l'objet PDO pour interagir avec MySQL
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    
    // Configuration de PDO pour lancer des exceptions en cas d'erreur SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Si la connexion échoue (ex: MySQL éteint), on arrête tout et on affiche l'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// --- Récupération des réglages du site (White Label) ---
// On cherche la première ligne de la table 'settings'
$stmt = $pdo->prepare("SELECT * FROM settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Valeurs de secours au cas où la table est vide ou incomplète
$default_settings = [
    'restaurant_name' => 'Le Gourmand',
    'logo_path' => 'assets/images/prestige.png',
    'theme_color_primary' => '#1a1a1a', 
    'theme_color_secondary' => '#2c2c2c',
    'theme_color_accent' => '#d92027',
    'contact_phone' => '+221 00 000 00 00',
    'contact_email' => 'contact@legourmand.sn',
    'address' => 'Dakar, Sénégal',
    'description' => 'Bienvenue dans notre restaurant gastronomique. Nous mettons un point d\'honneur à vous offrir les meilleures viandes braisées et grillées.'
];

// Si aucune donnée n'est trouvée en BDD, on utilise les valeurs par défaut
if (!$settings) {
    $settings = $default_settings;
} else {
    // Si des données existent, on fusionne pour s'assurer qu'aucune clé ne manque
    $settings = array_merge($default_settings, $settings);
}

// --- Initialisation du Panier ---
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

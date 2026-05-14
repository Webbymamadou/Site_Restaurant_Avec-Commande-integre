-- Script de création de la base de données Le Gourmand

CREATE DATABASE IF NOT EXISTS `gourmand` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gourmand`;

-- --------------------------------------------------------
-- Table `settings`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_name` varchar(255) NOT NULL,
  `logo_path` varchar(255) NOT NULL,
  `hero_image_path` varchar(255) NOT NULL,
  `theme_color_primary` varchar(50) NOT NULL,
  `theme_color_secondary` varchar(50) NOT NULL,
  `theme_color_accent` varchar(50) NOT NULL,
  `contact_phone` varchar(50) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`id`, `restaurant_name`, `logo_path`, `hero_image_path`, `theme_color_primary`, `theme_color_secondary`, `theme_color_accent`, `contact_phone`, `contact_email`, `address`, `description`) VALUES
(1, 'Le Prestige Gourmand', 'assets/images/prestige.png', 'assets/images/jenn-kosar-jrWoDRmhwRY-unsplash(1).jpg', '#1a1a1a', '#2c2c2c', '#d92027', '+221 77 123 45 67', 'contact@leprestigegourmand.sn', 'Dakar, Sénégal', 'Bienvenue dans notre restaurant gastronomique. Nous mettons un point d\'honneur à vous offrir les meilleures viandes braisées et grillées, dans une ambiance chaleureuse et conviviale.');

INSERT INTO `users` (`username`, `password`) VALUES ('admin', '$2y$10$Ynd/W0.Y6A2Sg8S6yI4Q9.pS8E7E/9J.G1G/J.I/G1G/J.I/G1G/J'); -- Password: admin123 (hashé)

-- --------------------------------------------------------
-- Table `categories`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Grillades de Boeuf'),
(2, 'Poulet Braisé'),
(3, 'Accompagnements'),
(4, 'Boissons');

-- --------------------------------------------------------
-- Table `menu_items`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_menu_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `menu_items` (`id`, `category_id`, `name`, `description`, `price`, `image_path`, `is_available`) VALUES
(1, 2, 'Poulet Braisé & Frites', 'Ce plat délicieux est accompagné de frites maison. Une de nos meilleures spécialités.', 6500.00, 'assets/images/images (1).jpeg', 1),
(2, 1, 'Pizza Feu de Bois', 'Pizza cuite au feu de bois avec des légumes grillés, très délicieuse.', 5000.00, 'assets/images/images (10).jpeg', 1),
(3, 1, 'Entrecôte Grillée', 'Une viande tendre et savoureuse, cuite à la perfection sur notre grill.', 8500.00, 'assets/images/images (11).jpeg', 1),
(4, 3, 'Salade Maison', 'Salade fraîcheur pour accompagner vos viandes.', 2500.00, 'assets/images/images (12).jpg', 1),
(5, 2, 'Ailes de Poulet Epicées', 'Marinnées et grillées au charbon de bois.', 4500.00, 'assets/images/images (3).jpeg', 1),
(6, 1, 'Brochettes de Boeuf', 'Brochettes de viande de boeuf fondantes.', 5500.00, 'assets/images/images (4).jpeg', 1);

-- --------------------------------------------------------
-- Table `orders`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `order_details` text NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

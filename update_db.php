<?php
require 'config.php';

try {
    // Add column if it doesn't exist
    $pdo->exec("ALTER TABLE settings ADD COLUMN description TEXT NULL AFTER restaurant_name");
    
    // Set a default value if it's currently empty
    $pdo->exec("UPDATE settings SET description = 'Bienvenue dans notre restaurant gastronomique. Nous mettons un point d\'honneur à vous offrir les meilleures viandes braisées et grillées, dans une ambiance chaleureuse et conviviale.' WHERE description IS NULL");
    
    echo "Database updated successfully.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

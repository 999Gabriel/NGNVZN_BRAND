<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Datenbankverbindung herstellen
$host = "db";
$dbname = "ngnvzn_shop";
$username = "root";
$password = "macintosh";
$port = 3306;

try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Database connection successful");
} catch (PDOException $e) {
    error_log("Verbindung fehlgeschlagen: " . $e->getMessage());
    exit("Verbindung fehlgeschlagen: " . $e->getMessage());
}

// Produkte abrufen
$queryStr = "SELECT p.id, p.name, p.description, p.price, c.name AS category,
                    GROUP_CONCAT(DISTINCT ps.size SEPARATOR ', ') AS product_sizes,
                    GROUP_CONCAT(DISTINCT pi.image_url SEPARATOR ', ') AS product_images
             FROM products p
             JOIN categories c ON p.category_id = c.id
             LEFT JOIN product_sizes ps ON p.id = ps.product_id
             LEFT JOIN product_images pi ON p.id = pi.product_id
             GROUP BY p.id";
$query = $pdo->prepare($queryStr);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Log the fetched products
error_log("Fetched products: " . print_r($products, true));

// Warenkorb aus der Session abrufen
session_start();
$cart_items = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];

// Add product details to cart items
foreach ($cart_items as &$item) {
    foreach ($products as $product) {
        if ($product['id'] == $item['product_id']) {
            $item['name'] = $product['name'];
            $item['images'] = $product['product_images'] ? explode(', ', $product['product_images']) : [];
            $item['price'] = $product['price'];
            break;
        }
    }
}

$cart_count = array_sum(array_column($cart_items, "quantity"));

// Gesamtanzahl der Artikel im Warenkorb
header('Content-Type: application/json');
echo json_encode([
    'cartItems' => $cart_items,
    'cartCount' => $cart_count,
    'products' => $products
]);
?>
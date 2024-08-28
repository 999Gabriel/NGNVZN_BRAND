<?php
session_start();
header('Content-Type: application/json');

// Überprüfen, ob der Warenkorb vorhanden ist
if (!isset($_SESSION['cart'])) {
    echo json_encode(['cartItems' => []]);
    exit;
}

$cartItems = $_SESSION['cart'];

// Datenbankverbindung
$host = 'db';
$dbname = 'ngnvzn_shop';
$username = 'root';
$password = 'macintosh';
$port = 3306;

try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $items = [];
    foreach ($cartItems as $item) {
        // Produktinformationen aus der Datenbank abrufen
        $stmt = $pdo->prepare('SELECT p.id, p.name, p.price, p.image_url, s.name AS size FROM products p JOIN sizes s ON s.id = :size_id WHERE p.id = :product_id');
        $stmt->execute(['product_id' => $item['product_id'], 'size_id' => $item['size_id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $items[] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'size' => $product['size'],
                'quantity' => $item['quantity']
            ];
        }
    }

    echo json_encode(['cartItems' => $items]);
} catch (PDOException $e) {
    echo json_encode(['cartItems' => [], 'error' => $e->getMessage()]);
}
?>
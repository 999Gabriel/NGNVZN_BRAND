<?php
session_start();
header('Content-Type: application/json');

// Function to add product to cart
function addToCart($productId, $size, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $productExists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId && $item['size'] == $size) {
            $item['quantity'] += $quantity;
            $productExists = true;
            break;
        }
    }

    if (!$productExists) {
        $_SESSION['cart'][] = [
            'product_id' => $productId,
            'size' => $size,
            'quantity' => $quantity
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $size = isset($_POST['size']) ? $_POST['size'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Debugging statements
    error_log("Product ID: $productId");
    error_log("Size: $size");
    error_log("Quantity: $quantity");

    // Check if product and size exist in the database
    $host = 'db';
    $dbname = 'ngnvzn_shop';
    $username = 'root';
    $password = 'macintosh';
    $port = 3306;

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE id = :product_id');
        $stmt->execute(['product_id' => $productId]);
        $productExists = $stmt->fetchColumn() > 0;

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM product_sizes WHERE product_id = :product_id AND size = :size');
        $stmt->execute(['product_id' => $productId, 'size' => $size]);
        $sizeExists = $stmt->fetchColumn() > 0;

        if ($productExists && $sizeExists && $quantity > 0) {
            addToCart($productId, $size, $quantity);
            error_log("Product added to cart successfully");
            echo json_encode(['success' => true]);
        } else {
            error_log("Invalid product data: Product ID: $productId, Size: $size, Quantity: $quantity");
            echo json_encode(['success' => false, 'message' => 'Invalid product data']);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    error_log("Invalid request method");
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
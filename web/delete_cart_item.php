<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    error_log("Received request to delete product ID: $productId");

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        echo json_encode(['success' => true]);
    } else {
        error_log("Product ID $productId not found in cart");
        echo json_encode(['success' => false, 'error' => 'Product not found in cart']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
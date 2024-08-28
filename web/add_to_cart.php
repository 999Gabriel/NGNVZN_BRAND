<?php
session_start();

// Überprüfen, ob die POST-Daten vorhanden sind
if (isset($_POST['product_id']) && isset($_POST['size_id'])) {
    $product_id = intval($_POST['product_id']);
    $size_id = intval($_POST['size_id']);

    // Überprüfen, ob der Warenkorb bereits existiert
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Überprüfen, ob das Produkt bereits im Warenkorb ist
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] === $product_id && $item['size_id'] === $size_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }

    // Wenn das Produkt noch nicht im Warenkorb ist, füge es hinzu
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'size_id' => $size_id,
            'quantity' => 1
        ];
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Produkt-ID oder Größe fehlt.']);
}
?>
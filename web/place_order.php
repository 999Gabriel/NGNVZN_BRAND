<?php
global $pdo;
session_start();
header('Content-Type: text/html; charset=utf-8');

// Überprüfen, ob der Warenkorb leer ist
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Ihr Warenkorb ist leer. Bitte fügen Sie Artikel hinzu, bevor Sie eine Bestellung aufgeben.</p>";
    exit;
}

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

    // Transaktion starten
    $pdo->beginTransaction();

    // Bestellung in der Datenbank speichern
    $stmt = $pdo->prepare('INSERT INTO orders (order_date) VALUES (NOW())');
    $stmt->execute();
    $orderId = $pdo->lastInsertId();

    // Warenkorb-Artikel speichern
    $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, size_id, quantity) VALUES (:order_id, :product_id, :size_id, :quantity)');

    foreach ($_SESSION['cart'] as $item) {
        $stmt->execute([
            'order_id' => $orderId,
            'product_id' => $item['product_id'],
            'size_id' => $item['size_id'],
            'quantity' => $item['quantity']
        ]);
    }

    // Transaktion abschließen
    $pdo->commit();

    // Warenkorb leeren
    unset($_SESSION['cart']);

    // Bestätigungsnachricht anzeigen
    echo "<h1>Bestellung erfolgreich aufgegeben!</h1>";
    echo "<p>Vielen Dank für Ihren Einkauf. Ihre Bestellung-ID lautet: " . htmlspecialchars($orderId) . "</p>";

} catch (PDOException $e) {
    // Transaktion zurückrollen, falls ein Fehler auftritt
    $pdo->rollBack();
    echo "<p>Fehler beim Aufgeben der Bestellung: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
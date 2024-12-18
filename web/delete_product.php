<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$host = "db";
$dbname = "ngnvzn_shop";
$username = "root";
$password = "macintosh";
$port = 3306;

try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}

$product_id = $_GET['id'];

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("DELETE FROM product_sizes WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $product_id]);

    $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $product_id]);

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $product_id]);

    $pdo->commit();
    header('Location: admin_panel.php');
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed to delete product: " . $e->getMessage();
}
?>
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $sizes = explode(',', $_POST['sizes']);
    $images = explode(',', $_POST['images']);

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category_id) VALUES (:name, :description, :price, :category_id)");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id
        ]);
        $productId = $pdo->lastInsertId();

        foreach ($sizes as $size) {
            $stmt = $pdo->prepare("INSERT INTO product_sizes (product_id, size) VALUES (:product_id, :size)");
            $stmt->execute([
                'product_id' => $productId,
                'size' => trim($size)
            ]);
        }

        foreach ($images as $image) {
            $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)");
            $stmt->execute([
                'product_id' => $productId,
                'image_url' => trim($image)
            ]);
        }

        $pdo->commit();
        header('Location: admin_panel.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed to add product: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<h2>Add Product</h2>
<form method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea>
    <br>
    <label for="price">Price:</label>
    <input type="number" step="0.01" id="price" name="price" required>
    <br>
    <label for="category_id">Category ID:</label>
    <input type="number" id="category_id" name="category_id" required>
    <br>
    <label for="sizes">Sizes (comma separated):</label>
    <input type="text" id="sizes" name="sizes" required>
    <br>
    <label for="images">Images (comma separated URLs):</label>
    <input type="text" id="images" name="images" required>
    <br>
    <button type="submit">Add Product</button>
</form>
<a href="admin_panel.php">Back to Admin Panel</a>
</body>
</html>
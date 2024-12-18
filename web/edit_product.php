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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $sizes = explode(',', $_POST['sizes']);
    $images = explode(',', $_POST['images']);

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id,
            'id' => $product_id
        ]);

        $stmt = $pdo->prepare("DELETE FROM product_sizes WHERE product_id = :product_id");
        $stmt->execute(['product_id' => $product_id]);

        foreach ($sizes as $size) {
            $stmt = $pdo->prepare("INSERT INTO product_sizes (product_id, size) VALUES (:product_id, :size)");
            $stmt->execute([
                'product_id' => $product_id,
                'size' => trim($size)
            ]);
        }

        $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = :product_id");
        $stmt->execute(['product_id' => $product_id]);

        foreach ($images as $image) {
            $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)");
            $stmt->execute([
                'product_id' => $product_id,
                'image_url' => trim($image)
            ]);
        }

        $pdo->commit();
        header('Location: admin_panel.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed to update product: " . $e->getMessage();
    }
} else {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT size FROM product_sizes WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $product_id]);
    $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $product_id]);
    $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>
<h2>Edit Product</h2>
<form method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
    <br>
    <label for="price">Price:</label>
    <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
    <br>
    <label for="category_id">Category ID:</label>
    <input type="number" id="category_id" name="category_id" value="<?php echo htmlspecialchars($product['category_id']); ?>" required>
    <br>
    <label for="sizes">Sizes (comma separated):</label>
    <input type="text" id="sizes" name="sizes" value="<?php echo htmlspecialchars(implode(', ', $sizes)); ?>" required>
    <br>
    <label for="images">Images (comma separated URLs):</label>
    <input type="text" id="images" name="images" value="<?php echo htmlspecialchars(implode(', ', $images)); ?>" required>
    <br>
    <button type="submit">Update Product</button>
</form>
<a href="admin_panel.php">Back to Admin Panel</a>
</body>
</html>
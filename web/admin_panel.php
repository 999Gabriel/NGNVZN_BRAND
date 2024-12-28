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

// Fetch products
$query = $pdo->query("SELECT * FROM products");
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch purchase data
$query = $pdo->query("SELECT * FROM orders");
$purchases = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<h2>Admin Panel</h2>
<h3>Products</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Category ID</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product['id']); ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td><?php echo htmlspecialchars($product['description']); ?></td>
            <td><?php echo htmlspecialchars($product['price']); ?></td>
            <td><?php echo htmlspecialchars($product['category_id']); ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo htmlspecialchars($product['id']); ?>">Edit</a>
                <a href="delete_product.php?id=<?php echo htmlspecialchars($product['id']); ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="add_product.php">Add New Product</a>

<h3>Purchases</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th>Purchase Date</th>
    </tr>
    <?php foreach ($purchases as $purchase): ?>
        <tr>
            <td><?php echo htmlspecialchars($purchase['id']); ?></td>
            <td><?php echo htmlspecialchars($purchase['product_id']); ?></td>
            <td><?php echo htmlspecialchars($purchase['quantity']); ?></td>
            <td><?php echo htmlspecialchars($purchase['total_price']); ?></td>
            <td><?php echo htmlspecialchars($purchase['purchase_date']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
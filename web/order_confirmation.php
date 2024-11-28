<?php
session_start();

// Retrieve cart items from the session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Check if the cart contains products
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Database connection
$host = 'db';
$dbname = 'ngnvzn_shop';
$username = 'root';
$password = 'macintosh';
$port = 3306;

try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Retrieve order details from the form
$name = isset($_POST['billing_name']) ? $_POST['billing_name'] : '';
$address = isset($_POST['billing_address']) ? $_POST['billing_address'] : '';
$city = isset($_POST['billing_city']) ? $_POST['billing_city'] : '';
$zip = isset($_POST['billing_zip']) ? $_POST['billing_zip'] : '';
$country = isset($_POST['billing_country']) ? $_POST['billing_country'] : '';
$card_number = isset($_POST['card_number']) ? $_POST['card_number'] : '';
$expiry_date = isset($_POST['card_expiry']) ? $_POST['card_expiry'] : '';
$cvv = isset($_POST['card_cvc']) ? $_POST['card_cvc'] : '';
$shipping = isset($_POST['shipping']) ? $_POST['shipping'] : 'standard'; // Default to standard shipping if not selected

// Process discount code
$discount_code = isset($_POST['discount_code']) ? $_POST['discount_code'] : '';
$valid_codes = [
    'SUMMER20' => 20,
    'WINTER10' => 10
];

$total = 0;
$shipping_cost = ($shipping === 'express') ? 15.00 : 5.00;

foreach ($cart_items as $item) {
    $productId = $item['product_id'];
    $sizeId = $item['size_id'];
    $quantity = $item['quantity'];

    $queryStr = "SELECT p.price FROM products p JOIN product_sizes ps ON ps.id = :size_id WHERE p.id = :product_id";
    $query = $pdo->prepare($queryStr);
    $query->execute(['product_id' => $productId, 'size_id' => $sizeId]);
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $item_total = $product['price'] * $quantity;
        $total += $item_total;
    }
}

// Apply discount code
if ($discount_code !== '' && array_key_exists($discount_code, $valid_codes)) {
    $discount_percentage = $valid_codes[$discount_code];
    $discount_amount = ($total * $discount_percentage) / 100;
    $total -= $discount_amount;
}

// Calculate total cost
$total += $shipping_cost;

// Save order in the database
try {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo 'Error: You must be logged in to place an order.';
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Save order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, order_date) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();

    // Save payment details
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, payment_date, amount, method, status) VALUES (?, NOW(), ?, 'credit_card', 'completed')");
    $stmt->execute([$order_id, $total]);

    foreach ($cart_items as $item) {
        $productId = $item['product_id'];
        $sizeId = $item['size_id'];
        $quantity = $item['quantity'];

        // Get the price for the product and size
        $queryStr = "SELECT p.price FROM products p JOIN product_sizes ps ON ps.id = :size_id WHERE p.id = :product_id";
        $query = $pdo->prepare($queryStr);
        $query->execute(['product_id' => $productId, 'size_id' => $sizeId]);
        $product = $query->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $price = $product['price']; // Get the product price
            // Ensure the size_id exists in the product_sizes table
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_size_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$order_id, $productId, $sizeId, $quantity, $price]);
        }
    }

    // Clear the cart
    unset($_SESSION['cart']);

    // Send email
    $to = '999gabriel.winkler@gmail.com'; // Use the actual customer's email address here
    $subject = 'Order Confirmation';
    $message = "Thank you for your order, $name!\n\n";
    $message .= "Your order #[{$order_id}] has been successfully placed.\n";
    $message .= "Total amount: €" . number_format($total, 2) . "\n\n";
    $message .= "Best regards,\nThe GOOD DON'T DIE Team";
    $headers = 'From: ceo.gooddontdie@gmail.com';

    mail($to, $subject, $message, $headers);

    echo "<p>Thank you for your order! Your order ID is: $order_id</p>";
} catch (PDOException $e) {
    echo 'Order error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href=css/styles.css>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="landing_page.php"><img src="img/logo.png" alt="Brand Logo"></a>
    </div>
    <div class="nav-links">
        <a href="landing_page.php">Home</a>
        <a href="agb.php">Terms</a>
        <a href="account.php">My Account</a>
    </div>
    <div class="search-cart">
        <div class="cart-icon">🛒</div>
    </div>
</nav>

<div class="container">
    <h1>Order Confirmation</h1>
    <?php echo $order_message; ?>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2023 GOOD DON'T DIE. All rights reserved.</p>
</div>
</body>
</html>

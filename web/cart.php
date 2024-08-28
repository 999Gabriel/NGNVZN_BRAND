<?php
session_start();

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Datenbankverbindung herstellen
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
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
    exit;
}

$product_ids = array_keys($cart_items);

if (count($product_ids) > 0) {
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $queryStr = "SELECT p.id, p.name, p.description, p.price, p.image_url, c.name AS category
                 FROM products p
                 JOIN categories c ON p.category_id = c.id
                 WHERE p.id IN ($placeholders)";
    $query = $pdo->prepare($queryStr);
    $query->execute($product_ids);
    $products = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = [];
}

$cart_total = 0;
foreach ($products as $product) {
    $cart_total += $product['price'] * $cart_items[$product['id']];
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warenkorb</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }

        .container {
            margin-top: 80px;
            width: 90%;
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #000;
            margin-bottom: 30px;
        }

        .cart-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 80px;
            height: auto;
            border-radius: 10px;
        }

        .item-details {
            flex-grow: 1;
            margin-left: 20px;
        }

        .item-name {
            font-size: 18px;
            font-weight: bold;
        }

        .item-price {
            margin-top: 5px;
            color: #333;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .quantity-controls button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }

        .item-total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
        }

        .cart-total {
            font-size: 22px;
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }

        .checkout-button {
            display: block;
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 20px;
            cursor: pointer;
            text-decoration: none;
        }
        .navbar {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            top: 0;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .navbar .logo img {
            height: 40px; /* Höhe des Logos */
            width: auto;
        }

        .navbar .nav-links {
            display: flex;
            gap: 15px;
        }

        .navbar .nav-links a {
            color: #000;
            text-decoration: none;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Warenkorb</h1>
    <div class="cart-grid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="item-details">
                        <p class="item-name"><?php echo htmlspecialchars($product['name']); ?></p>
                        <p class="item-price">€<?php echo htmlspecialchars($product['price']); ?> x <?php echo $cart_items[$product['id']]; ?></p>
                    </div>
                    <div class="quantity-controls">
                        <form method="post" action="remove_from_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit">-</button>
                        </form>
                        <form method="post" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit">+</button>
                        </form>
                    </div>
                    <div class="item-total">
                        €<?php echo htmlspecialchars($product['price'] * $cart_items[$product['id']]); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Ihr Warenkorb ist leer.</p>
        <?php endif; ?>
    </div>
    <?php if (count($products) > 0): ?>
        <div class="cart-total">
            Gesamtsumme: €<?php echo htmlspecialchars($cart_total); ?>
        </div>
        <a href="checkout.php" class="checkout-button">Zur Kasse</a>
    <?php endif; ?>
</div>
</body>
</html>
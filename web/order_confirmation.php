<?php
session_start();

// Warenkorb aus der Session abrufen
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Überprüfen, ob der Warenkorb Produkte enthält
if (empty($cart_items)) {
    echo "<p>Ihr Warenkorb ist leer.</p>";
    exit;
}

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

// Bestellungsdetails aus dem Formular erhalten
$name = isset($_POST['name']) ? $_POST['name'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$zip = isset($_POST['zip']) ? $_POST['zip'] : '';
$country = isset($_POST['country']) ? $_POST['country'] : '';
$card_name = isset($_POST['card-name']) ? $_POST['card-name'] : '';
$card_number = isset($_POST['card-number']) ? $_POST['card-number'] : '';
$expiry_date = isset($_POST['expiry-date']) ? $_POST['expiry-date'] : '';
$cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';
$shipping = isset($_POST['shipping']) ? $_POST['shipping'] : 'standard'; // Standardversand, falls nichts ausgewählt wurde

// Rabattcode verarbeiten
$discount_code = isset($_POST['discount-code']) ? $_POST['discount-code'] : '';
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

    $queryStr = "SELECT p.price FROM products p JOIN sizes s ON s.id = :size_id WHERE p.id = :product_id";
    $query = $pdo->prepare($queryStr);
    $query->execute(['product_id' => $productId, 'size_id' => $sizeId]);
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $item_total = $product['price'] * $quantity;
        $total += $item_total;
    }
}

// Rabattcode anwenden
if ($discount_code !== '' && array_key_exists($discount_code, $valid_codes)) {
    $discount_percentage = $valid_codes[$discount_code];
    $discount_amount = ($total * $discount_percentage) / 100;
    $total -= $discount_amount;
}

// Gesamtkosten berechnen
$total += $shipping_cost;

// Bestellung in der Datenbank speichern
try {
    $stmt = $pdo->prepare("INSERT INTO orders (name, address, city, zip, country, card_name, card_number, expiry_date, cvv, shipping, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $address, $city, $zip, $country, $card_name, $card_number, $expiry_date, $cvv, $shipping, $total]);

    $order_id = $pdo->lastInsertId();

    // Bestellpositionen speichern
    foreach ($cart_items as $item) {
        $productId = $item['product_id'];
        $sizeId = $item['size_id'];
        $quantity = $item['quantity'];

        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size_id, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $productId, $sizeId, $quantity]);
    }

    // Warenkorb leeren
    unset($_SESSION['cart']);

    echo "<p>Vielen Dank für Ihre Bestellung! Ihre Bestell-ID lautet: $order_id</p>";
} catch (PDOException $e) {
    echo 'Fehler bei der Bestellung: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellbestätigung</title>
    <style>
        /* Grundlegendes Styling für den gesamten Body */
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: #333;
        }

        /* Navbar-Styling */
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

        .navbar .nav-links a:hover {
            background-color: #000;
            color: #fff;
        }

        .navbar .search-cart {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar .search-cart input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 200px;
        }

        .navbar .search-cart .cart-icon {
            font-size: 24px;
            color: #000;
            cursor: pointer;
            position: relative;
        }

        .navbar .search-cart .cart-icon:hover {
            color: #333;
        }

        .navbar .search-cart .cart-icon .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #f00;
            color: #fff;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
        }

        /* Container für den Checkout-Bereich */
        .container {
            margin-top: 80px; /* Abstand für die feste Navbar */
            width: 90%;
            max-width: 800px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Überschrift für den Checkout-Bereich */
        h1 {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #000;
            margin-bottom: 30px;
        }

        /* Styling für Abschnitte */
        .section {
            margin-bottom: 20px;
        }

        /* Styling für Labels */
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Styling für Eingabefelder */
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Styling für den Bestellabschluss-Button */
        .checkout-button {
            display: block;
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 12px;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .checkout-button:hover {
            background-color: #333;
        }

        /* Styling für die Bestellübersicht */
        .order-summary ul {
            list-style-type: none;
            padding: 0;
        }

        .order-summary li {
            margin-bottom: 10px;
        }

        /* Responsive Design für kleinere Bildschirme */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 10px;
            }

            h1 {
                font-size: 24px;
            }

            .checkout-button {
                font-size: 16px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Vielen Dank für Ihre Bestellung!</h1>
    <p>Ihre Bestell-ID lautet: <?php echo htmlspecialchars($_GET['order_id']); ?></p>
    <p>Wir haben Ihre Bestellung erhalten und werden sie so schnell wie möglich bearbeiten.</p>
    <a href="index.php" class="checkout-button">Zurück zur Startseite</a>
</div>
</body>
</html>

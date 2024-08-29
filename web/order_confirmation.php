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

    $queryStr = "SELECT p.price FROM products p JOIN product_sizes ps ON ps.id = :size_id WHERE p.id = :product_id";
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
    // Bestellung speichern
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, shipping_address, shipping_city, shipping_postal_code, shipping_country, total_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([null, $address, $city, $zip, $country, $total, 'pending']);
    $order_id = $pdo->lastInsertId();

    // Zahlungsdaten speichern
    $stmt = $pdo->prepare("INSERT INTO payments (order_id, payment_date, amount, method, status) VALUES (?, NOW(), ?, 'credit_card', 'completed')");
    $stmt->execute([$order_id, $total]);

    foreach ($cart_items as $item) {
        $productId = $item['product_id'];
        $sizeId = $item['size_id'];
        $quantity = $item['quantity'];

        // Holen Sie sich den Preis für das Produkt und die Größe
        $queryStr = "SELECT p.price FROM products p JOIN product_sizes ps ON ps.id = :size_id WHERE p.id = :product_id";
        $query = $pdo->prepare($queryStr);
        $query->execute(['product_id' => $productId, 'size_id' => $sizeId]);
        $product = $query->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $price = $product['price']; // Holen Sie den Preis des Produkts
            // Sicherstellen, dass der size_id in der Tabelle `product_sizes` existiert
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_size_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$order_id, $productId, $sizeId, $quantity, $price]);
        }
    }

    // Warenkorb leeren
    unset($_SESSION['cart']);

    // E-Mail senden
    $to = '999gabriel.winkler@gmail.com'; // Hier sollte die tatsächliche E-Mail-Adresse des Kunden verwendet werden
    $subject = 'Bestellbestätigung';
    $message = "Vielen Dank für Ihre Bestellung, $name!\n\n";
    $message .= "Ihre Bestellung #[{$order_id}] wurde erfolgreich aufgegeben.\n";
    $message .= "Gesamtbetrag: €" . number_format($total, 2) . "\n\n";
    $message .= "Mit freundlichen Grüßen,\nDas Team von GOOD DON'T DIE";
    $headers = 'From: ceo.gooddontdie@gmail.com';

    mail($to, $subject, $message, $headers);

    echo "<p>Vielen Dank für Ihre Bestellung! Ihre Bestell-ID lautet: $order_id</p>";
} catch (PDOException $e)
{
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
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: #333;
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
            height: 40px;
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

        .container {
            margin-top: 80px;
            width: 90%;
            max-width: 800px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #fff;
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

        .section {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

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

        .order-summary ul {
            list-style-type: none;
            padding: 0;
        }

        .order-summary li {
            margin-bottom: 10px;
        }

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
<div class="navbar">
    <div class="logo">
        <a href="index.php"><img src="img/logo.png" alt="Logo"></a>
    </div>
    <div class="nav-links">
        <a href="index.php">Startseite</a>
        <a href="produkte.php">Produkte</a>
        <a href="about_us.php">Über uns</a>
        <a href="contact.php">Kontakt</a>
    </div>
</div>
<div class="container">
    <h1>Vielen Dank für Ihre Bestellung!</h1>
    <p>Ihre Bestell-ID lautet: <?php echo htmlspecialchars($order_id); ?></p>
    <p>Wir haben Ihre Bestellung erhalten und werden sie so schnell wie möglich bearbeiten.</p>
    <a href="index.php" class="checkout-button">Zurück zur Startseite</a>
</div>
</body>
</html>
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

// Bestellübersicht anzeigen
$total = 0;
$shipping_cost = 5.00; // Standardversandkosten

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
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
<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="index.php"><img src="img/logo.png" alt="Markenlogo"></a>
    </div>
    <div class="nav-links">
        <a href="index.php">Startseite</a>
        <a href="agb.php">AGBs</a>
        <a href="account.php">Mein Account</a>
    </div>
    <div class="search-cart">
        <div class="cart-icon">🛒</div>
    </div>
</nav>

<div class="container">
    <h1>Checkout</h1>

    <!-- Bestellübersicht -->
    <div class="order-summary">
        <h2>Bestellübersicht</h2>
        <ul>
            <?php
            foreach ($cart_items as $item) {
                $productId = $item['product_id'];
                $sizeId = $item['size_id'];
                $quantity = $item['quantity'];

                $queryStr = "SELECT p.name, p.price FROM products p JOIN sizes s ON s.id = :size_id WHERE p.id = :product_id";
                $query = $pdo->prepare($queryStr);
                $query->execute(['product_id' => $productId, 'size_id' => $sizeId]);
                $product = $query->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $item_total = $product['price'] * $quantity;
                    $total += $item_total;
                    echo "<li>{$product['name']} ({$quantity} Stück): €" . number_format($item_total, 2) . "</li>";
                }
            }

            // Rabattcode-Anwendung
            $valid_codes = [
                'SUMMER20' => 20,
                'WINTER10' => 10
            ];

            $discount_code = isset($_POST['discount-code']) ? trim($_POST['discount-code']) : '';

            if ($discount_code !== '' && array_key_exists($discount_code, $valid_codes)) {
                $discount_percentage = $valid_codes[$discount_code];
                $discount_amount = ($total * $discount_percentage) / 100;
                $total -= $discount_amount;
                echo "<li>Rabatt von $discount_percentage% angewendet. Neuer Gesamtbetrag: €" . number_format($total, 2) . "</li>";
            } else {
                echo "<li>Ungültiger Rabattcode.</li>";
            }

            $total += $shipping_cost; // Versandkosten zur Gesamtsumme hinzufügen
            ?>
            <li>Versand: €<?php echo number_format($shipping_cost, 2); ?></li>
            <li><strong>Gesamtsumme: €<?php echo number_format($total, 2); ?></strong></li>
        </ul>
    </div>
    <form id="checkout-form" action="order_confirmation.php" method="POST">
        <!-- Lieferdetails -->
        <div class="section">
            <h2>Lieferdetails</h2>
            <label for="name">Vollständiger Name</label>
            <input type="text" id="name" name="name" required>
            <label for="address">Adresse</label>
            <input type="text" id="address" name="address" required>

            <label for="city">Stadt</label>
            <input type="text" id="city" name="city" required>

            <label for="zip">Postleitzahl</label>
            <input type="text" id="plz" name="PLZ" required>
            <label for="country">Land</label>
            <input type="text" id="country" name="country" required>
        </div>

        <!-- Zahlungsdetails -->
        <div class="section">
            <h2>Zahlungsdetails</h2>
            <label for="card-name">Name auf der Karte</label>
            <input type="text" id="card-name" name="card-name" required>

            <label for="card-number">Kartennummer</label>
            <input type="text" id="card-number" name="card-number" required>

            <label for="expiry-date">Gültig bis</label>
            <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>

            <label for="cvv">CVV</label>
            <input type="text" id="cvv" name="cvv" required>
        </div>

        <!-- Rabattcode -->
        <div class="section">
            <label for="discount-code">Rabattcode</label>
            <input type="text" id="discount-code" name="discount-code">
            <button type="button" onclick="applyDiscount()">Anwenden</button>
        </div>

        <!-- Versandoptionen -->
        <div class="section">
            <h2>Versandoptionen</h2>
            <label><input type="radio" name="shipping" value="standard" checked> Standardversand (€5,00)</label><br>
            <label><input type="radio" name="shipping" value="express"> Expressversand (€15,00)</label>
        </div>

        <button type="submit" class="checkout-button" id="submit-button">Bestellung abschließen</button>
    </form>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('dein-stripe-publishable-key'); // Ersetze durch deinen echten Stripe-Publishable-Key

    document.getElementById('checkout-form').addEventListener('submit', function (event) {
        event.preventDefault();

        // Stripe Token erstellen
        stripe.createToken('card', {
            number: document.getElementById('card-number').value,
            exp_month: document.getElementById('expiry-date').value.split('/')[0],
            exp_year: document.getElementById('expiry-date').value.split('/')[1],
            cvc: document.getElementById('cvv').value
        }).then(function (result) {
            if (result.error) {
                alert('Fehler: ' + result.error.message);
            } else {
                // Token hinzufügen und Formular übermitteln
                const form = document.getElementById('checkout-form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'stripeToken';
                hiddenInput.value = result.token.id;
                form.appendChild(hiddenInput);

                // Formular senden
                form.submit();
            }
        });
    });

    function applyDiscount() {
        const discountCode = document.getElementById('discount-code').value;
        const validCodes = {
            'SUMMER20': 20,
            'WINTER10': 10
        };
        let total = parseFloat(document.querySelector('.order-summary strong').innerText.replace('Gesamtsumme: €', '').replace(',', '.'));
        if (validCodes[discountCode]) {
            const discount = validCodes[discountCode];
            const discountAmount = (total * discount) / 100;
            total -= discountAmount;
            document.querySelector('.order-summary strong').innerText = 'Gesamtsumme: €' + total.toFixed(2);
        } else {
            alert('Ungültiger Rabattcode.');
        }
    }
</script>
</body>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datenbankverbindung herstellen
$host = 'db';
$dbname = 'ngnvzn_shop'; // Neue Datenbank
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

// Produkte abrufen
$queryStr = "SELECT p.id, p.name, p.description, p.price, p.image_url, c.name AS category
             FROM products p
             JOIN categories c ON p.category_id = c.id";
$query = $pdo->prepare($queryStr);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Warenkorb aus der Session abrufen
session_start();
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = array_sum($cart_items); // Gesamtanzahl der Artikel im Warenkorb
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsere Produkte</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/good_dont_die.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background: url('img/ngnvzn.png') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar .logo a {
            font-size: 36px;
            font-weight: bold;
            color: #f8cdd3; /* Helles Pink */
            text-decoration: none; /* Entfernt Unterstreichung */
        }

        .navbar .nav-links {
            display: flex;
            gap: 15px;
            margin-right: 20px;
        }

        .navbar .nav-links a {
            color: #f8cdd3;
            text-decoration: none;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar .nav-links a:hover {
            background-color: #f8cdd3;
            color: #000;
        }

        .container {
            margin-top: 80px;
            width: 90%;
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #f8cdd3; /* Helles Pink */
            margin-bottom: 30px;
        }

        .products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product-card {
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            width: calc(50% - 20px); /* Zwei Produkte nebeneinander */
            text-align: center;
            padding: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
            box-sizing: border-box; /* Einschluss von Padding in der Breite */
        }

        .product-card img {
            width: 100%;
            height: 550px;
            transition: transform 0.3s;
        }


        .product-name {
            font-size: 22px;
            font-weight: bold;
            margin-top: 10px;
            color: #f8cdd3; /* Helles Pink */
        }

        .product-price {
            color: #f8cdd3; /* Helles Pink */
            font-size: 18px;
            margin-top: 5px;
        }

        .product-description {
            font-size: 16px;
            margin-top: 10px;
            color: #ddd; /* Helleres Grau für die Beschreibung */
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .product-card:hover img {
            transform: scale(1.1);
        }

        .add-to-cart {
            background-color: #f8cdd3; /* Helles Pink */
            border: none;
            color: #000;
            padding: 10px;
            font-size: 16px; /* Größe für den Button */
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #afafaf; /* Etwas dunkleres Pink beim Hover */
        }

        .footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: #f8cdd3; /* Helles Pink */
            text-align: center;
            padding: 15px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo"><a href="index.php">GDDIE</a></div>
    <div class="nav-links">
        <a href="index.php">Über uns</a>
        <a href="produkte.php">Unsere Produkte</a>
        <a href="agb.php">AGB</a>
    </div>
</div>

<!-- Hauptinhalt -->
<div class="container">
    <h1>Unsere Produkte</h1>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
                <h2 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="product-price">Preis: €<?php echo htmlspecialchars($product['price']); ?></p>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Kategorie:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                <button class="add-to-cart" data-id="<?php echo htmlspecialchars($product['id']); ?>">In den Warenkorb</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>© 2024 GDDIE. Alle Rechte vorbehalten. | <a href="agb.php" style="color: #fefeff; text-decoration: none;">Allgemeine Geschäftsbedingungen (AGB)</a></p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');

                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'product_id=' + productId
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Produkt zum Warenkorb hinzugefügt!');
                            // Hier kannst du auch den Warenkorb-Zähler aktualisieren, wenn nötig
                        }
                    });
            });
        });
    });
</script>

</body>
</html>
<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

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
    exit("Verbindung fehlgeschlagen: " . $e->getMessage());
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$queryStr = "SELECT p.id, p.name, p.description, p.price, c.name AS category,
                    GROUP_CONCAT(DISTINCT ps.size SEPARATOR ', ') AS product_sizes,
                    GROUP_CONCAT(DISTINCT pi.image_url SEPARATOR ', ') AS product_images
             FROM products p
             JOIN categories c ON p.category_id = c.id
             LEFT JOIN product_sizes ps ON p.id = ps.product_id
             LEFT JOIN product_images pi ON p.id = pi.product_id
             WHERE p.id = :product_id
             GROUP BY p.id";
$query = $pdo->prepare($queryStr);
$query->execute(['product_id' => $product_id]);
$product = $query->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    exit("Produkt nicht gefunden");
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product["name"]); ?></title>
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
            padding: 10px 0;
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
            height: 60px;
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

        .nav-links a:hover {
            background-color: #000;
            color: #fff;
        }

        .logo {
            text-align: center;
            flex: 1;
        }

        .search-cart {
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            margin-top: 80px;
            width: 100%;
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
        }

        .product-detail {
            display: flex;
            gap: 20px;
        }

        .image-grid {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .image-grid img {
            width: 100%;
            height: 600px;
            border-radius: 10px;
            object-fit: cover;
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .product-info h1 {
            font-size: 28px;
            font-weight: bold;
            color: #000;
        }

        .product-info p {
            font-size: 18px;
            color: #333;
        }

        .product-info .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }

        .product-info .product-description {
            font-size: 16px;
            color: #666;
        }

        .product-info .size-buttons {
            display: flex;
            gap: 10px;
        }

        .product-info .size-button {
            background-color: #fff;
            border: none;
            font-size: 16px;
            padding: 10px 15px;
            cursor: pointer;
            transition: border-color 0.3s, background-color 0.3s;
            position: relative;
        }

        .product-info .size-button::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #333;
            transition: width 0.3s;
        }

        .product-info .size-button:hover::after {
            width: 100%;
        }

        .product-info .size-button.selected::after {
            width: 100%;
        }

        .product-info .add-to-cart {
            background-color: #000;
            border: none;
            color: #fff;
            padding: 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .product-info .add-to-cart:hover {
            background-color: #333;
        }

        .footer {
            padding: 20px;
            text-align: center;
            background-color: #000;
            color: #fff;
        }

        .footer p {
            margin: 0;
            font-size: 16px;
        }

        .footer a {
            color: #fff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="nav-links">
        <a href="landing_page.php">Startseite</a>
        <a href="account.php">Mein Account</a>
        <a href="faq.php">FAQ</a>
    </div>
    <div class="logo">
        <a href="landing_page.php"><img src="img/logo.png" alt="Markenlogo"></a>
    </div>
    <div class="search-cart">
        <input type="text" placeholder="Suche...">
        <a href="#" class="cart-icon" id="cartIcon">🛒</a>
    </div>
</nav>
<div class="container">
    <div class="product-detail">
        <div class="image-grid">
            <?php foreach (explode(', ', $product['product_images']) as $image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product["name"]); ?>">
            <?php endforeach; ?>
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product["name"]); ?></h1>
            <p class="product-price">Preis: €<?php echo htmlspecialchars($product["price"]); ?></p>
            <p class="product-description"><?php echo htmlspecialchars($product["description"]); ?></p>
            <p><strong>Kategorie:</strong> <?php echo htmlspecialchars($product["category"]); ?></p>
            <div class="size-buttons">
                <?php foreach (explode(', ', $product["product_sizes"]) as $size): ?>
                    <button class="size-button" data-size="<?php echo htmlspecialchars($size); ?>"><?php echo htmlspecialchars($size); ?></button>
                <?php endforeach; ?>
            </div>
            <button class="add-to-cart" data-id="<?php echo htmlspecialchars($product["id"]); ?>">In den Warenkorb</button>
        </div>
    </div>
</div>
<!-- Footer -->
<footer class="footer">
    <p>© 2024 GOOD DON'T DIE. Alle Rechte vorbehalten. | <a href="agb.php">AGB</a> | <a href="kontakt.php">Kontakt</a></p>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedSize = '';

        document.querySelectorAll('.size-button').forEach(button => {
            button.addEventListener('click', function() {
                selectedSize = this.getAttribute('data-size');
                document.querySelectorAll('.size-button').forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        document.querySelector('.add-to-cart').addEventListener('click', function() {
            const productId = this.getAttribute('data-id');

            if (!selectedSize) {
                alert('Bitte wählen Sie eine Größe aus, bevor Sie das Produkt in den Warenkorb legen.');
                return;
            }

            fetch('/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}&size=${selectedSize}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produkt erfolgreich in den Warenkorb gelegt.');
                    } else {
                        alert('Fehler beim Hinzufügen des Produkts in den Warenkorb.');
                    }
                })
                .catch(error => {
                    console.error('Fehler beim Hinzufügen des Produkts in den Warenkorb:', error);
                });
        });
    });
</script>
</body>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Produkte abrufen
$queryStr = "SELECT p.id, p.name, p.description, p.price, p.image_url, c.name AS category
             FROM products p
             JOIN categories c ON p.category_id = c.id";
$query = $pdo->prepare($queryStr);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Größen abrufen
$sizes = []; // Initialisiere die Variable als leeres Array

try {
    $sizeQueryStr = "SELECT id, name FROM sizes";
    $sizeQuery = $pdo->prepare($sizeQueryStr);
    $sizeQuery->execute();
    $sizes = $sizeQuery->fetchAll(PDO::FETCH_ASSOC);

    // Überprüfen, ob die Abfrage ein Ergebnis zurückgegeben hat
    if (!$sizes) {
        $sizes = []; // Leeres Array, wenn keine Größen vorhanden sind
    }
} catch (PDOException $e) {
    echo 'Fehler beim Abrufen der Größen: ' . $e->getMessage();
}

// Warenkorb aus der Session abrufen
session_start();
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = array_sum(array_column($cart_items, 'quantity')); // Gesamtanzahl der Artikel im Warenkorb
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsere Produkte</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* Weißer Hintergrund für den gesamten Body */
            color: #000; /* Textfarbe Schwarz */
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

        .nav-links a:hover {
            background-color: #000;
            color: #fff;
        }

        .logo {
            text-align: center;
            flex: 1;
        }

        .logo img {
            max-height: 100px; /* Maximale Höhe des Logos */
            height: auto;
            vertical-align: middle; /* Ausrichtung des Logos */
        }

        .search-cart {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar .search-cart {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto; /* Positioniert die Suchleiste und den Warenkorb nach rechts */
        }

        .navbar .search-cart input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 200px; /* Breite der Suchleiste */
        }

        .navbar .search-cart .cart-icon {
            font-size: 24px;
            color: #000;
            cursor: pointer;
            position: relative; /* Wichtige Positionierung für den Zähler */
        }

        .navbar .search-cart .cart-icon:hover {
            color: #333; /* Etwas dunklerer Farbton bei Hover */
        }

        .navbar .search-cart .cart-icon .cart-count {
            position: relative;
            top: -10px;
            right: -10px;
            background-color: #f00; /* Hintergrundfarbe für die Anzahl im Warenkorb */
            color: #fff; /* Schriftfarbe der Anzahl */
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .container {
            margin-top: 80px;
            width: 90%;
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #f9f9f9; /* Heller Hintergrund für bessere Lesbarkeit */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sanfter Schatten für Tiefe */
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #000; /* Textfarbe Schwarz */
            margin-bottom: 30px;
        }

        .products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
        }

        .product-card {
            background-color: #fff; /* Weißer Hintergrund für die Produktkarte */
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            width: calc(50% - 20px); /* Zwei Produkte nebeneinander */
            text-align: center;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sanfter Schatten für Tiefe */
            margin-bottom: 20px;
            box-sizing: border-box; /* Einschluss von Padding in der Breite */
        }

        .product-card img {
            width: 100%;
            height: auto; /* Höhe automatisch anpassen */
            max-height: 400px; /* Maximale Höhe für Konsistenz */
            object-fit: cover; /* Bild zuschneiden, um den Container auszufüllen */
            transition: transform 0.3s;
        }

        .product-name {
            font-size: 22px;
            font-weight: bold;
            margin-top: 10px;
            color: #000; /* Textfarbe Schwarz */
        }

        .product-price {
            font-size: 18px;
            margin-top: 5px;
            color: #333; /* Dunkelgrau für bessere Lesbarkeit */
        }

        .product-description {
            font-size: 16px;
            margin-top: 10px;
            color: #666; /* Helleres Grau für die Beschreibung */
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stärkerer Schatten bei Hover */
        }

        .product-card:hover img {
            transform: scale(1.1);
        }

        .add-to-cart {
            background-color: #000; /* Schwarzer Hintergrund für den Button */
            border: none;
            color: #fff; /* Weißer Text */
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #333; /* Etwas dunkleres Schwarz beim Hover */
        }

        /* Container für die Auswahl der Größen */
        .size-selector-container {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Styling für das Dropdown-Menü */
        .product-size {
            appearance: none; /* Entfernt die Standard-Dropdown-Pfeile */
            background-color: #fff; /* Weißer Hintergrund für das Dropdown-Menü */
            border: 1px solid #ddd; /* Grauer Rand für das Dropdown-Menü */
            border-radius: 5px; /* Abgerundete Ecken */
            font-size: 16px; /* Schriftgröße */
            padding: 10px 15px; /* Innenabstand */
            width: 100%; /* Vollständige Breite der Produktkarte nutzen */
            box-sizing: border-box; /* Einschluss von Padding und Border in der Breite */
            cursor: pointer; /* Zeiger-Cursor */
            transition: border-color 0.3s, background-color 0.3s; /* Sanfte Übergänge */
        }

        /* Styling für das Dropdown-Menü bei Hover */
        .product-size:hover {
            border-color: #333; /* Dunklerer Rand bei Hover */
        }

        /* Styling für die Pfeilspitze im Dropdown-Menü */
        .product-size::after {
            content: "▼"; /* Pfeilsymbol */
            font-size: 16px;
            color: #999; /* Graue Farbe für den Pfeil */
            position: absolute; /* Positionierung des Pfeils */
            top: 50%;
            right: 15px;
            transform: translateY(-50%); /* Zentriert den Pfeil vertikal */
            pointer-events: none; /* Pfeil ist nicht anklickbar */
        }

        /* Container für die Größe-Auswahl */
        .size-selector-container {
            position: relative; /* Position für den Pfeil */
        }

        .size-selector-container .product-size {
            padding-right: 35px; /* Platz für den Pfeil */
        }

        /* Optionen im Dropdown-Menü */
        .product-size option {
            padding: 10px;
            font-size: 16px;
        }

        /* Styling für eine besser lesbare Auswahl */
        .size-option {
            background-color: #f5f5f5; /* Heller Hintergrund für Optionen */
            border-bottom: 1px solid #ddd; /* Trennlinie zwischen Optionen */
        }

        /* Styling für die erste Option (Standardwert) */
        .product-size option:first-child {
            color: #aaa; /* Graue Farbe für die Standardoption */
        }

        /* Entfernen der Standard-Schattierung des Dropdowns in einigen Browsern */
        .product-size::-ms-expand {
            display: none; /* IE10-12 */
        }
        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            background-color: #000;
            /* Schwarzer Footer */
            color: #fff; / Weißer Text */
        }
        .footer p {
            margin: 0;
            font-size: 16px;
        }

        .footer a {
            color: #fff; /* Weißer Text für Links */
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .footer .newsletter-form input[type="email"], .footer .newsletter-form button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin: 5px;
            transition: transform 0.3s ease;
        }

        .footer .newsletter-form input[type="email"]:focus, .footer .newsletter-form button:hover {
            transform: scale(1.05);
        }

        .footer .newsletter-form input[type="email"] {
            width: 60%;
        }

        .footer .newsletter-form button {
            background-color: #fff;
            color: #000;
        }

        .footer .feature {
            display: inline-block;
            width: 30%;
            padding: 20px;
            box-sizing: border-box;
            color: #fff; /* Weißer Text für die Stärken */
        }

        .footer .feature img {
            width: 80px; /* Kleinere Bildgröße für mehr Konsistenz */
            height: 80px; /* Kleinere Bildgröße für mehr Konsistenz */
            object-fit: cover; /* Stellt sicher, dass das Bild die Box vollständig ausfüllt */
            margin-bottom: 10px;
        }

        .footer .feature h3 {
            font-size: 20px;
            margin: 10px 0;
        }

        .footer .feature p {
            font-size: 14px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            color: #000; /* Textfarbe Schwarz */
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 0 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #000;
            color: #fff; /* Weißer Text bei Hover */
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
        /* Hinzufügen von CSS für das Warenkorb-Overlay */
        .cart-panel {
            position: fixed;
            right: -100%; /* Startet außerhalb des Bildschirms */
            top: 0;
            width: 400px;
            height: 100%;
            background-color: #fff; /* Weißer Hintergrund für das Overlay */
            box-shadow: -4px 0 8px rgba(0, 0, 0, 0.1); /* Schatten für Tiefe */
            transition: right 0.3s ease-in-out; /* Weiche Übergangsanimation */
            z-index: 1001; /* Über dem Navbar, um sicherzustellen, dass es sichtbar ist */
            padding: 20px;
            overflow-y: auto; /* Scrollen aktivieren, wenn Inhalt zu lang ist */
        }

        .cart-panel.open {
            right: 0; /* Schiebt das Overlay in den sichtbaren Bereich */
        }

        .close-btn {
            background-color: transparent;
            border: none;
            color: #000; /* Schwarze Farbe für das Schließen-Symbol */
            font-size: 24px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            transition: color 0.3s ease-in-out;
        }

        .close-btn:hover {
            color: #f00; /* Rote Farbe beim Hover */
        }

        .cart-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .cart-item img {
            width: 100px;
            height: auto;
            object-fit: cover;
            border-radius: 5px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .item-price,
        .item-quantity,
        .item-size {
            font-size: 16px;
            color: #666;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            background-color: #000; /* Schwarzer Hintergrund */
            color: #fff; /* Weißer Text */
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s ease-in-out;
        }

        .checkout-btn:hover {
            background-color: #333; /* Dunkleres Schwarz beim Hover */
        }

    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="nav-links">
        <a href="index.php">Startseite</a>
        <a href="account.php">Mein Account</a>
        <a href="faq.php">FAQ</a>
    </div>
    <div class="logo">
        <a href="index.php"><img src="img/logo.png" alt="Markenlogo"></a>
    </div>
    <div class="search-cart">
        <input type="text" placeholder="Suche...">
        <a href="#" class="cart-icon" id="cartIcon">🛒 <?php echo $cart_count > 0 ? "($cart_count)" : ""; ?></a> <!-- Link zum Warenkorb-Icon -->
    </div>
</nav>
<!-- Warenkorb Modal -->
<div class="cart-panel" id="cartPanel">
    <button class="close-btn" id="closeCartPanel">✖</button>
    <h2>Warenkorb</h2>
    <div id="cartItems">
        <!-- Hier werden die Warenkorb-Artikel geladen -->
    </div>
    <a href="checkout.php" class="checkout-btn">Zur Kasse</a>
</div>
<div class="container">
    <h1>Unsere Produkte</h1>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h2 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="product-price">Preis: €<?php echo htmlspecialchars($product['price']); ?></p>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Kategorie:</strong> <?php echo htmlspecialchars($product['category']); ?></p>

                <!-- Größe auswählen -->
                <select class="product-size" data-id="<?php echo htmlspecialchars($product['id']); ?>">
                    <option value="">Größe wählen</option>
                    <?php foreach ($sizes as $size): ?>
                        <option value="<?php echo htmlspecialchars($size['id']); ?>"><?php echo htmlspecialchars($size['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <button class="add-to-cart" data-id="<?php echo htmlspecialchars($product['id']); ?>">In den Warenkorb</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>© 2024 GOOD DON'T DIE. Alle Rechte vorbehalten. | <a href="agb.php">AGB</a> | <a href="kontakt.php">Kontakt</a></p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        const cartIcon = document.getElementById('cartIcon');
        const cartPanel = document.getElementById('cartPanel');
        const closeCartPanel = document.getElementById('closeCartPanel');
        const cartItemsContainer = document.getElementById('cartItems');

        function updateCartPanel() {
            fetch('get_cart_items.php')
                .then(response => response.json())
                .then(data => {
                    if (data.cartItems) {
                        cartItemsContainer.innerHTML = ''; // Lösche vorherige Einträge
                        data.cartItems.forEach(item => {
                            const itemElement = document.createElement('div');
                            itemElement.className = 'cart-item';
                            itemElement.innerHTML = `
                            <div class="cart-item-image">
                                <img src="${item.image_url}" alt="${item.name}">
                            </div>
                            <div class="cart-item-details">
                                <p class="item-name">${item.name}</p>
                                <p class="item-price">Preis: €${item.price}</p>
                                <p class="item-quantity">Menge: ${item.quantity}</p>
                                <p class="item-size">Größe: ${item.size}</p>
                            </div>
                        `;
                            cartItemsContainer.appendChild(itemElement);
                        });
                    } else {
                        cartItemsContainer.innerHTML = '<p>Ihr Warenkorb ist leer.</p>';
                    }
                })
                .catch(error => {
                    console.error('Fehler beim Abrufen der Warenkorb-Daten:', error);
                });
        }

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const sizeSelect = this.previousElementSibling;
                const sizeId = sizeSelect ? sizeSelect.value : '';

                if (!sizeId) {
                    alert('Bitte wählen Sie eine Größe aus, bevor Sie das Produkt in den Warenkorb legen.');
                    return;
                }

                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `product_id=${productId}&size_id=${sizeId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCartPanel(); // Aktualisiere das Warenkorb-Modal
                            cartPanel.classList.add('open'); // Öffne das Warenkorb-Modal
                        }
                    });
            });
        });

        cartIcon.addEventListener('click', function() {
            updateCartPanel(); // Aktualisiere das Warenkorb-Modal beim Öffnen
            cartPanel.classList.toggle('open');
        });

        closeCartPanel.addEventListener('click', function() {
            cartPanel.classList.remove('open');
        });
    });
</script>
</body>
</html>
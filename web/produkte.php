<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Datenbankverbindung herstellen
$host = "db";
$dbname = "ngnvzn_shop";
$username = "root";
$password = "macintosh";
$port = 3306;

try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Database connection successful");
} catch (PDOException $e) {
    error_log("Verbindung fehlgeschlagen: " . $e->getMessage());
    exit("Verbindung fehlgeschlagen: " . $e->getMessage());
}

// Produkte abrufen
$queryStr = "SELECT p.id, p.name, p.description, p.price, c.name AS category,
                    GROUP_CONCAT(DISTINCT ps.size SEPARATOR ', ') AS product_sizes,
                    GROUP_CONCAT(DISTINCT pi.image_url SEPARATOR ', ') AS product_images
             FROM products p
             JOIN categories c ON p.category_id = c.id
             LEFT JOIN product_sizes ps ON p.id = ps.product_id
             LEFT JOIN product_images pi ON p.id = pi.product_id
             GROUP BY p.id";
$query = $pdo->prepare($queryStr);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Log the fetched products
error_log("Fetched products: " . print_r($products, true));

// Warenkorb aus der Session abrufen
session_start();
$cart_items = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];
$cart_count = array_sum(array_column($cart_items, "quantity"));

// Gesamtanzahl der Artikel im Warenkorb
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
            margin-right: 0;
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
            height: 60px; /* Höhe des Logos */
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
            justify-content: flex-end; /* Stellen Sie sicher, dass der Inhalt am rechten Rand ausgerichtet ist */
            padding-right: 0; /* Entfernt überflüssige Abstände auf der rechten Seite */
            margin-right: 0; /* Entfernt überflüssige Abstände auf der rechten Seite */
        }

        .navbar .search-cart .cart-icon {
            font-size: 24px;
            color: #000;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px; /* Abstand zwischen Suchleiste und Warenkorb-Icon */
            margin-right: 0; /* Entferne oder reduziere den rechten Abstand */
        }

        .navbar .search-cart input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 200px; /* Breite der Suchleiste */
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
            width: 100%;
            max-width: 1200px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #fff; /* Entfernt den grauen Hintergrund */
            border-radius: 10px;
            box-shadow: none; /* Entfernt den Schatten */
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #000; /* Textfarbe Schwarz */
            margin-bottom: 30px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Drei Spalten mit gleicher Breite */
            gap: 20px;
            justify-content: center;
        }

        .product-card {
            background-color: #fff; /* Weißer Hintergrund für die Produktkarte */
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-align: center;
            padding: 15px;
            /*box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sanfter Schatten für Tiefe */
            box-sizing: border-box; /* Einschluss von Padding in der Breite */
        }

        .product-card img {
            width: 100%;
            height: auto; /* Höhe automatisch anpassen */
            max-height: 300px; /* Maximale Höhe für Konsistenz */
            object-fit: cover; /* Bild zuschneiden, um den Container auszufüllen */
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: scale(1.05);
            /*box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stärkerer Schatten bei Hover */
        }

        .product-card:hover img {
            transform: scale(1.1);
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

        .slideshow-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            margin: auto;
        }

        .slides {
            display: none;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
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
        <a href="#" class="cart-icon" id="cartIcon">🛒 <?php echo $cart_count > 0 ? "($cart_count)" : ""; ?></a>
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
            <div class="product-card" data-id="<?php echo htmlspecialchars($product["id"]); ?>">
                <div class="slideshow-container">
                    <?php foreach (explode(', ', $product['product_images']) as $index => $image): ?>
                        <div class="slides">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product["name"]); ?>" style="width:100%">
                        </div>
                    <?php endforeach; ?>
                    <a class="prev" onclick="plusSlides(-1, <?php echo $product['id']; ?>)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1, <?php echo $product['id']; ?>)">&#10095;</a>
                </div>
                <h2 class="product-name"><?php echo htmlspecialchars($product["name"]); ?></h2>
                <p class="product-price">Preis: €<?php echo htmlspecialchars($product["price"]); ?></p>
                <p class="product-description"><?php echo htmlspecialchars($product["description"]); ?></p>
                <p><strong>Kategorie:</strong> <?php echo htmlspecialchars($product["category"]); ?></p>
                <!-- Größe auswählen -->
                <select class="product-size" data-id="<?php echo htmlspecialchars($product["id"]); ?>">
                    <option value="">Größe wählen</option>
                    <?php foreach (explode(', ', $product["product_sizes"]) as $size): ?>
                        <option value="<?php echo htmlspecialchars($size); ?>"><?php echo htmlspecialchars($size); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="add-to-cart" data-id="<?php echo htmlspecialchars($product["id"]); ?>">In den Warenkorb</button>
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

        let slideIndices = {};

        function showSlides(n, productId) {
            let i;
            let slides = document.querySelectorAll(`.product-card[data-id="${productId}"] .slides`);
            if (!slideIndices[productId]) {
                slideIndices[productId] = 1;
            }
            if (n > slides.length) { slideIndices[productId] = 1 }
            if (n < 1) { slideIndices[productId] = slides.length }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndices[productId] - 1].style.display = "block";
        }

        window.plusSlides = function(n, productId) {
            showSlides(slideIndices[productId] += n, productId);
        }

        document.querySelectorAll('.product-card').forEach(card => {
            const productId = card.getAttribute('data-id');
            showSlides(1, productId);
            card.querySelector('.prev').addEventListener('click', function() {
                plusSlides(-1, productId);
            });
            card.querySelector('.next').addEventListener('click', function() {
                plusSlides(1, productId);
            });
        });

        function updateCartPanel() {
            const url = '/get_cart_items.php'; // Ensure this URL is correct
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // Get the response as JSON
                })
                .then(data => {
                    console.log('Cart items:', data);
                    if (Array.isArray(data.cartItems)) {
                        cartItemsContainer.innerHTML = '';
                        data.cartItems.forEach(item => {
                            const itemElement = document.createElement('div');
                            itemElement.classList.add('cart-item');
                            const imagesHtml = item.images.map(imageUrl => `<img src="${imageUrl}" alt="${item.name || 'No Image'}" style="width:50px;height:auto;">`).join('');
                            itemElement.innerHTML = `
                            <div class="item-images">${imagesHtml}</div>
                            <div class="item-details">
                                <p class="item-name">${item.name || 'No Name'}</p>
                                <p class="item-price">€${item.price || '0.00'}</p>
                                <p class="item-quantity">Quantity: ${item.quantity || '0'}</p>
                                <p class="item-size">Size: ${item.size || 'N/A'}</p>
                            </div>
                        `;
                            cartItemsContainer.appendChild(itemElement);
                        });
                    } else {
                        console.error('Error fetching cart items:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error fetching cart data:', error);
                });
        }

        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                const sizeSelect = this.previousElementSibling;
                const size = sizeSelect ? sizeSelect.value : '';

                if (!size) {
                    alert('Please select a size before adding the product to the cart.');
                    return;
                }

                console.log(`Adding product ${productId} with size ${size} to cart`);

                fetch('/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `product_id=${productId}&size=${size}`
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            console.log('Product added to cart successfully');
                            updateCartPanel(); // Update the cart panel after adding a product
                        } else {
                            console.error('Error adding product to cart:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error adding product to cart:', error);
                    });
            });
        });

        cartIcon.addEventListener('click', function() {
            updateCartPanel(); // Update the cart panel when opening
            cartPanel.classList.toggle('open');
        });

        closeCartPanel.addEventListener('click', function() {
            cartPanel.classList.remove('open');
        });
    });
</script>
</body>
</html>
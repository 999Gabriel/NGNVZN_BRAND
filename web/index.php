<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startseite</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/ngnvzn.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            color: #fff;
            overflow-x: hidden; /* Verhindert horizontales Scrollen */
            background: url('img/ngnvzn.png') no-repeat center center fixed; /* Hintergrundbild für die gesamte Seite */
            background-size: cover;
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
            color: #f8cdd3;
            text-decoration: none;
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

        /* Hero Section */
        .hero {
            padding: 100px 20px 50px; /* Anpassung für den Hero-Bereich */
            text-align: center;
            color: #fff;
        }

        .hero h1, .hero p, .hero .cta-button {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .hero h1 {
            animation: fadeInUp 1s forwards;
            animation-delay: 0.5s;
        }

        .hero p {
            animation: fadeInUp 1s forwards;
            animation-delay: 1s;
        }

        .hero .cta-button {
            display: inline-block;
            background-color: #f8cdd3;
            color: #000;
            padding: 15px 30px;
            font-size: 18px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            animation: fadeInUp 1s forwards;
            animation-delay: 1.5s;
        }

        .hero .cta-button:hover {
            background-color: #e3b9c7;
            transform: scale(1.05);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Carousel Section */
        .carousel {
            padding: 50px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            margin: 20px 0;
            border-radius: 10px;
        }

        .carousel-items {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            gap: 20px;
            padding: 10px 0;
        }

        .carousel-item {
            flex: 0 0 auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
        }

        .carousel-item img {
            width: 400px; /* Feste Breite für alle Bilder */
            height: 400px; /* Feste Höhe für alle Bilder */
            object-fit: cover; /* Stellt sicher, dass das Bild die Box vollständig ausfüllt */
            border-radius: 10px;
        }

        .carousel-item:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        /* Testimonial Section */
        .testimonial-section {
            padding: 50px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            margin: 20px 0;
            border-radius: 10px;
        }

        .testimonial {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .testimonial:nth-child(even) {
            animation: fadeInUp 1s forwards;
            animation-delay: 0.5s;
        }

        .testimonial:nth-child(odd) {
            animation: fadeInUp 1s forwards;
            animation-delay: 1s;
        }

        /* Newsletter Section */
        .newsletter {
            padding: 50px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            margin: 20px 0;
            border-radius: 10px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            animation: fadeInUp 1s forwards;
            animation-delay: 1s;
        }

        .newsletter input[type="email"], .newsletter button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin: 5px;
            transition: transform 0.3s ease;
        }

        .newsletter input[type="email"]:focus, .newsletter button:hover {
            transform: scale(1.05);
        }

        .newsletter input[type="email"] {
            width: 60%;
        }

        .newsletter button {
            background-color: #f8cdd3;
            color: #000;
        }

        /* Feature Section */
        .feature-section {
            padding: 50px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            margin: 20px 0;
            border-radius: 10px;
        }

        .feature-section h2 {
            font-size: 36px;
            color: #f8cdd3;
            margin-bottom: 20px;
        }

        .feature-section .feature {
            display: inline-block;
            width: 30%;
            padding: 20px;
            box-sizing: border-box;
            color: #ddd;
        }

        .feature-section .feature img {
            width: 100px; /* Feste Breite für alle Bilder */
            height: 100px; /* Feste Höhe für alle Bilder */
            object-fit: cover; /* Stellt sicher, dass das Bild die Box vollständig ausfüllt */
            margin-bottom: 10px;
        }

        .feature-section .feature h3 {
            font-size: 24px;
            margin: 10px 0;
        }

        .feature-section .feature p {
            font-size: 16px;
        }

        /* Countdown Section */
        .countdown-section {
            padding: 50px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            margin: 20px 0;
            border-radius: 10px;
        }

        .countdown {
            font-size: 24px;
            color: #f8cdd3;
        }
        /* Angebote Sektion */
        .offers-section {
            padding: 50px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            margin: 20px 0;
            border-radius: 10px;
        }

        .offers-section h2 {
            font-size: 36px;
            color: #f8cdd3;
            margin-bottom: 20px;
        }

        .offer {
            display: inline-block;
            width: 45%;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            box-sizing: border-box;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .offer img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .offer h3 {
            font-size: 24px;
            color: #f8cdd3;
            margin-bottom: 10px;
        }

        .offer p {
            font-size: 16px;
            color: #ddd;
            line-height: 1.6;
        }

        .offer:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        /* Footer */
        .footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: #f8cdd3;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .footer a {
            color: #f8cdd3;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo"><a href="index.php">NGNVZN</a></div>
    <div class="nav-links">
        <a href="index.php">Startseite</a>
        <a href="produkte.php">Unsere Produkte</a>
        <a href="agb.php">AGB</a>
    </div>
</div>

<!-- Hero-Bereich -->
<div class="hero">
    <h1>Willkommen bei NGNVZN</h1>
    <p>Entdecken Sie unsere exklusiven Produkte und Angebote.</p>
    <a href="produkte.php" class="cta-button">Jetzt einkaufen</a>
</div>
<!-- Featured Products Carousel -->
<div class="carousel">
    <h2>Unsere Highlights</h2>
    <!-- Hier kannst du ein Karussell oder Slider-Plugin integrieren -->
    <div class="carousel-items">
        <div class="carousel-item"><img src="img/ocean_view.avif" alt="Produkt 1"></div>
        <div class="carousel-item"><img src="img/hand_hoodie.webp" alt="Produkt 2"></div>
        <div class="carousel-item"><img src="img/pray_the_lord.jpg" alt="Produkt 3"></div>
        <div class="carousel-item"><img src="img/champion.avif" alt="Produkt 4"></div>
    </div>
</div>

<!-- Angebote Sektion -->
<div class="offers-section">
    <h2>Unsere Angebote</h2>
    <div class="offer">
        <img src="img/travis.webp" alt="Angebot 1">
        <h3>30% Rabatt auf alle Artikel</h3>
        <p>Nutzen Sie unseren aktuellen Rabatt auf die gesamte Kollektion!</p>
    </div>
    <div class="offer">
        <img src="img/money_for_porsche.webp" alt="Angebot 2">
        <h3>Gratis Versand bei Bestellungen über 50€</h3>
        <p>Jetzt profitieren und Versandkosten sparen.</p>
    </div>
</div>

<!-- Testimonials Section -->
<div class="testimonial-section">
    <h2>Was unsere Kunden sagen</h2>
    <div class="testimonial">
        <p>"Ein fantastisches Einkaufserlebnis! Die Produkte sind von höchster Qualität." - Max M.</p>
    </div>
    <div class="testimonial">
        <p>"Der Kundenservice war erstklassig. Sehr empfehlenswert!" - Anna K.</p>
    </div>
</div>

<!-- Newsletter Signup -->
<div class="newsletter">
    <h2>Bleiben Sie informiert</h2>
    <p>Abonnieren Sie unseren Newsletter für die neuesten Updates und Angebote.</p>
    <form action="subscribe.php" method="post">
        <input type="email" name="email" placeholder="Ihre E-Mail-Adresse" required>
        <button type="submit">Abonnieren</button>
    </form>
</div>

<!-- Feature Section -->
<div class="feature-section">
    <h2>Unsere Stärken</h2>
    <div class="feature">
        <img src="img/cart.png" alt="Feature 1">
        <h3>Hochwertige Produkte</h3>
        <p>Wir bieten nur die besten Produkte auf dem Markt.</p>
    </div>
    <div class="feature">
        <img src="img/chat.png" alt="Feature 2">
        <h3>Exzellenter Service</h3>
        <p>Unser Kundenservice ist immer für Sie da.</p>
    </div>
    <div class="feature">
        <img src="img/thumb.png" alt="Feature 3">
        <h3>100% Zufriedenheit</h3>
        <p>Wir garantieren Ihre Zufriedenheit.</p>
    </div>
</div>

<!-- Countdown Section -->
<div class="countdown-section">
    <h2>Besondere Angebote</h2>
    <p>Nur noch <span class="countdown" id="countdown-timer">00:00:00</span> bis zu unserem großen Angebot!</p>
    <!-- Hier kannst du einen Countdown-Timer hinzufügen -->
</div>

<!-- Footer -->
<footer class="footer">
    <p>© 2024 NGNVZN. Alle Rechte vorbehalten. | <a href="agb.php" style="color: #f8cdd3; text-decoration: none;">Allgemeine Geschäftsbedingungen (AGB)</a></p>
</footer>

<script>
    // Countdown-Timer Script
    function startCountdown() {
        const endDate = new Date("2024-12-31T23:59:59").getTime();
        const timerElement = document.getElementById('countdown-timer');

        const interval = setInterval(function() {
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance <= 0) {
                clearInterval(interval);
                timerElement.textContent = "EXPIRED";
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timerElement.textContent = `${hours}:${minutes}:${seconds}`;
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', startCountdown);
</script>

</body>
</html>
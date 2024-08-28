<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startseite</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            color: #000; /* Textfarbe Schwarz */
            background-color: #fff; /* Hintergrundfarbe Weiß für den gesamten Body */
            overflow-x: hidden; /* Verhindert horizontales Scrollen */
        }

        /* Container für allgemeines Layout */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Basis-Stile für die Navbar */
        .navbar {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten für die Navigation */
        }

        /* Transparente Navbar für den Hero-Bereich */
        .transparent {
            background-color: transparent;
        }

        /* Weiße Navbar nach dem Hero-Bereich */
        .solid {
            background-color: #fff; /* Hintergrundfarbe Weiß */
            color: #000; /* Textfarbe Schwarz */
        }

        .navbar .logo a {
            font-size: 36px;
            font-weight: bold;
            color: #000; /* Schriftfarbe Schwarz */
            text-decoration: none;
        }

        .navbar .nav-links {
            display: flex;
            gap: 15px;
            margin-right: 20px;
        }

        .navbar .nav-links a {
            color: #000; /* Schriftfarbe Schwarz */
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

        .hero {
            background: url('img/future.jpg') no-repeat center center fixed; /* Hintergrundbild */
            background-size: cover;
            padding: 600px 20px 50px;
            text-align: center;
            color: white;
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
            background-color: #fff;
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
            background-color: #000;
            color: #fff;
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
            background-color: #fff; /* Weißer Hintergrund für Carousel */
            margin: 20px 0;
            border-radius: 10px;
        }

        .carousel-items {
            display: inline-flex;
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
            width: 300px; /* Anpassung der Bildgröße für mehr Flexibilität */
            height: 300px; /* Feste Höhe für alle Bilder */
            object-fit: cover; /* Stellt sicher, dass das Bild die Box vollständig ausfüllt */
            border-radius: 10px;
        }

        .carousel-item:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        /* Testimonial Section */
        .testimonial-section {
            padding: 50px 20px;
            text-align: center;
            background-color: #fff; /* Weißer Hintergrund für Testimonials */
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

        /* Feature Section */
        .feature-section {
            padding: 50px 20px;
            text-align: center;
            background-color: #fff; /* Weißer Hintergrund für Feature Section */
            margin: 20px 0;
            border-radius: 10px;
        }

        .feature-section h2 {
            font-size: 36px;
            color: #000; /* Textfarbe Schwarz */
            margin-bottom: 20px;
        }

        .feature-section .feature {
            display: inline-block;
            width: 30%;
            padding: 20px;
            box-sizing: border-box;
            color: #333; /* Textfarbe Dunkelgrau */
        }

        .feature-section .feature img {
            width: 80px; /* Kleinere Bildgröße für mehr Konsistenz */
            height: 80px; /* Kleinere Bildgröße für mehr Konsistenz */
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
            background: url('img/wave.jpg') no-repeat center center fixed; /* Hintergrundbild */
            background-size: cover;
            padding: 500px 50px 90px;
            text-align: center;
            color: red;
            margin: 30px 0;
            border-radius: 10px;
        }

        .countdown {
            font-size: 50px;
            color: red; /* Textfarbe Schwarz */
        }

        /* Angebote Sektion */
        .offers-section {
            padding: 50px 20px;
            text-align: center;
            background-color: #f9f9f9; /* Heller Hintergrund für bessere Lesbarkeit */
            margin: 20px 0;
            border-radius: 10px;
        }

        .offers-section h2 {
            font-size: 36px;
            color: black; /* Dunkelgraue Farbe für besseren Kontrast */
            margin-bottom: 20px;
        }

        .offer {
            display: inline-block;
            width: 100%;
            max-width: 540px;
            background-color: #fff; /* Weißer Hintergrund für bessere Lesbarkeit */
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            box-sizing: border-box;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sanfter Schatten für Tiefe */
            position: relative;
        }

        .offer img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .offer h3 {
            font-size: 24px;
            color: #000; /* Schwarze Farbe für die Titel */
            margin-bottom: 10px;
        }

        .offer p {
            font-size: 16px;
            color: #666; /* Hellerer Text für den Beschreibungstext */
        }

        .offer:hover {
            transform: scale(1.03); /* Subtiles Zoomen bei Hover */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stärkerer Schatten bei Hover */
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

    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar">
    <div class="logo">
        <a href="index.php">GOOD DON'T DIE</a>
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="produkte.php">Produkte</a>
        <a href="agb.php">Über Uns</a>
        <a href="#">Kontakt</a>
        <a href="account.php">Mein Account/Login</a>
    </div>
</nav>
<!-- Hero Section -->
<header class="hero">
    <h1>GOOD DON'T DIE</h1>
    <p>Checke unsere erste Kollektion aus</p>
    <a href="produkte.php" class="cta-button">Jetzt einkaufen</a>
</header>
<!-- Carousel Section -->
<section class="carousel">
    <h2>Unsere neuesten Produkte</h2>
    <div class="carousel-items">
        <div class="carousel-item"><img src="img/money_for_porsche.webp" alt="Produkt 1"></div>
        <div class="carousel-item"><img src="img/A$AP.png" alt="Produkt 2"></div>
        <div class="carousel-item"><img src="img/ocean_view.avif" alt="Produkt 3"></div>
    </div>
</section>
<!-- Testimonial Section -->
<section class="testimonial-section">
    <h2>Kundenstimmen</h2>
    <div class="testimonial">
        <p>"Einfach großartig! Die Qualität ist unübertroffen und der Kundenservice ist hervorragend."</p>
        <span>- Maria H.</span>
    </div>
    <div class="testimonial">
        <p>"Ich bin begeistert von den innovativen Produkten. Absolut empfehlenswert!"</p>
        <span>- Jan S.</span>
    </div>
</section>
<!-- Feature Section -->
<section class="feature-section">
    <h2>Unsere Merkmale</h2>
    <div class="feature">
        <img src="img/technologie.png" alt="Merkmal 1">
        <h3>Innovative Technologie</h3>
        <p>Wir setzen auf modernste Technologie, um dir das Beste zu bieten.</p>
    </div>
    <div class="feature">
        <img src="img/qualität.png" alt="Merkmal 2">
        <h3>Höchste Qualität</h3>
        <p>Unsere Produkte durchlaufen strenge Qualitätskontrollen.</p>
    </div>
    <div class="feature">
        <img src="img/support.png" alt="Merkmal 3">
        <h3>Exzellenter Support</h3>
        <p>Unser Team ist jederzeit für dich da, um deine Fragen zu beantworten.</p>
    </div>
</section>
<!-- Countdown Section -->
<section class="countdown-section">
    <h2>Nächster großer Sale</h2>
    <div class="countdown" id="countdown">
        00 Tage 00 Stunden 00 Minuten 00 Sekunden
    </div>
</section>
<!-- Offers Section -->
<section class="offers-section">
    <h2>Aktuelle Angebote</h2>
    <div class="offer">
        <img src="img/king.webp" alt="Angebot 1">
        <h3>50% Rabatt auf ausgewählte Artikel</h3>
        <p>Verpasse nicht unsere exklusiven Rabatte auf Top-Produkte.</p>
    </div>
    <div class="offer">
        <img src="img/hand_hoodie.webp" alt="Angebot 2">
        <h3>Sommer-Special</h3>
        <p>Erhalte einen kostenlosen Versand bei Bestellungen über 50€.</p>
    </div>
</section>
<!-- Footer Section -->
<footer class="footer">
    <div class="newsletter-form">
        <h2>Abonniere unseren Newsletter</h2>
        <p>Erhalte die neuesten Updates und Angebote direkt in deinem Posteingang.</p>
        <form action="#">
            <input type="email" placeholder="Deine E-Mail-Adresse">
            <button type="submit">Abonnieren</button>
        </form>
    </div>
    <div class="feature">
        <img src="img/technologie.png" alt="Merkmal 1">
        <h3>Innovative Technologie</h3>
        <p>Wir setzen auf modernste Technologie, um dir das Beste zu bieten.</p>
    </div>
    <div class="feature">
        <img src="img/qualität.png" alt="Merkmal 2">
        <h3>Höchste Qualität</h3>
        <p>Unsere Produkte durchlaufen strenge Qualitätskontrollen.</p>
    </div>
    <div class="feature">
        <img src="img/support.png" alt="Merkmal 3">
        <h3>Exzellenter Support</h3>
        <p>Unser Team ist jederzeit für dich da, um deine Fragen zu beantworten.</p>
    </div>

    <p>&copy; 2024 GOOD DON'T DIE. Alle Rechte vorbehalten.</p>
    <p><a href="agb.php">Datenschutz</a> | <a href="#">Impressum</a></p>
</footer>
<script>
    // Countdown Timer Script
    const countdown = document.getElementById('countdown');
    const targetDate = new Date('2024-12-31T23:59:59').getTime();

    const interval = setInterval(() => {
        const now = new Date().getTime();
        const distance = targetDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        countdown.innerHTML = `${days} Tage ${hours} Stunden ${minutes} Minuten ${seconds} Sekunden`;

        if (distance < 0) {
            clearInterval(interval);
            countdown.innerHTML = "Sale ist gestartet!";
        }
    }, 1000);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.querySelector('.navbar');
        const heroSectionHeight = document.querySelector('.hero').offsetHeight; // Höhe des Hero-Bereichs

        function onScroll() {
            if (window.scrollY > heroSectionHeight) {
                navbar.classList.remove('transparent');
                navbar.classList.add('solid');
            } else {
                navbar.classList.remove('solid');
                navbar.classList.add('transparent');
            }
        }

        window.addEventListener('scroll', onScroll);
        onScroll(); // Initiale Überprüfung
    });
</script>
</body>
</html>
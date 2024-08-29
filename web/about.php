<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Über uns - GOOD DON'T DIE</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .box-shadow {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2, p {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .rounded {
            border-radius: 10px;
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

        h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 15px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .team-member {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .team-member img {
            border-radius: 50%;
            margin-right: 15px;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .team-member h3 {
            margin: 0;
            font-size: 20px;
            color: #555;
        }

        .team-member p {
            margin: 5px 0;
            color: #777;
        }

        .highlight {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .highlight h2 {
            margin-top: 0;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 10px;
            }

            .team-member img {
                width: 80px;
                height: 80px;
            }

            h1 {
                font-size: 24px;
            }
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
<div class="navbar">
    <div class="logo">
        <a href="index.php"><img src="img/logo.png" alt="GOOD DON'T DIE Logo"></a>
    </div>
    <div class="nav-links">
        <a href="index.php">Startseite</a>
        <a href="produkte.php">Shop</a>
        <a href="about.php">Über uns</a>
        <a href="contact.php">Kontakt</a>
    </div>
</div>
<div class="container">
    <h1>Über uns</h1>
    <p>Willkommen bei GOOD DON'T DIE! Wir sind ein leidenschaftliches Team von Mode-Enthusiasten, die sich darauf spezialisiert haben, hochwertige und einzigartige Bekleidung anzubieten. Unsere Mission ist es, stilvolle Mode zu erschwinglichen Preisen anzubieten und dabei stets hohe Qualitätsstandards zu wahren.</p>

    <div class="highlight">
        <h2>Unsere Geschichte</h2>
        <p>GOOD DON'T DIE wurde 2024 gegründet, um den Bedürfnissen von modebewussten Menschen gerecht zu werden, die sowohl Trends folgen als auch nachhaltige Mode unterstützen möchten. Unser Name spiegelt unser Engagement wider, zeitlose und langlebige Mode zu schaffen, die den Test der Zeit besteht.</p>
    </div>

    <h2>Unser Team</h2>
    <div class="team-member">
        <img src="img/59A6CC45-EF8B-431F-AE59-5CD12D8EBA9A.jpg" alt="Gabriel Winkler">
        <div>
            <h3>Gabriel Winkler</h3>
            <p>Founder and CEO</p>
            <p>Gabriel ist der Gründer der Brand. Er ist außerdem für die gesamte IT und Business Seite zuständig, als auch hat er das Marketing völlig im Griff und gibt 100% jeden Tag um die Firma groß zu machen.</p>
        </div>
    </div>
    <div class="team-member">
        <img src="img/IMG_3ADB64B73363-1.jpeg" alt="Jonas Bishop">
        <div>
            <h3>Jonas Bishop</h3>
            <p>Co-founder and Head of Design</p>
            <p>Jonas ist der kreative Kopf hinter den Designs und designt alles selber. Er ist immer Up to Date mit den neuesten Trends und ist daher immer perfekt Vorbereitet.</p>
        </div>
    </div>
    </div>

    <div class="highlight">
        <h2>Unsere Werte</h2>
        <p>Wir glauben an Transparenz, Qualität und Nachhaltigkeit. Jeder Schritt in unserer Produktionskette wird sorgfältig überwacht, um sicherzustellen, dass wir nur das Beste für unsere Kunden liefern. Von der Auswahl der Stoffe bis zur Verpackung - unser Ziel ist es, Produkte anzubieten, die Sie lieben werden.</p>
    </div>

    <p>Vielen Dank für Ihren Besuch auf unserer Website und Ihr Interesse an GOOD DON'T DIE. Wenn Sie Fragen haben oder mehr über uns erfahren möchten, zögern Sie nicht, uns zu kontaktieren!</p>
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
    <p><a href="agb.php">Datenschutz</a></p>
</footer>
</body>
</html>
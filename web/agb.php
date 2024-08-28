<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGB</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* Weißer Hintergrund */
            color: #000; /* Schwarzer Text */
        }

        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* Weißer Hintergrund für den gesamten Body */
            color: #000; /* Textfarbe Schwarz */
        }

        /* Navbar Styling */
        .navbar {
            background-color: #fff; /* Hintergrundfarbe der Navigation */
            padding: 10px 20px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten für die Navigation */
        }

        .navbar .nav-links {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
            position: absolute;
            left: 20px; /* Abstand von der linken Seite */
        }

        .nav-links a {
            color: #000; /* Schriftfarbe Schwarz */
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
            flex: 1;
            display: flex;
            justify-content: center; /* Zentriert das Logo innerhalb des Containers */
        }

        .logo img {
            max-height: 100px; /* Maximale Höhe des Logos */
            height: auto;
        }

        .container {
            margin-top: 80px; /* Abstand für die fixe Navbar */
            width: 80%;
            max-width: 1200px; /* Maximalbreite für größere Bildschirme */
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #fff; /* Weißer Hintergrund für Container */
            color: #000; /* Schwarzer Text für Container */
            border: 1px solid #ccc; /* Grauer Rand */
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); /* Sanfte Schatten */
            transition: box-shadow 0.3s; /* Animation für Box-Schatten */
        }

        .container:hover {
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3); /* Stärkerer Schatten bei Hover */
        }

        h1 {
            font-size: 28px; /* Größe für den Titel */
            font-weight: bold;
            text-align: center;
            color: #000; /* Schwarzer Text für Titel */
            margin-bottom: 20px;
        }

        h2 {
            font-size: 20px; /* Größe für Unterüberschriften */
            font-weight: bold;
            color: #000; /* Schwarzer Text für Unterüberschriften */
            margin-top: 20px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px; /* Textgröße */
            margin-bottom: 15px;
            line-height: 1.6;
        }

        a {
            color: #000; /* Schwarzer Text für Links */
            text-decoration: underline;
            transition: color 0.3s; /* Animation für Textfarbe */
        }

        a:hover {
            color: #555; /* Grauton bei Hover */
        }

        .footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff; /* Weißer Text für Footer */
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
<nav class="navbar">
    <div class="nav-links">
        <a href="index.php">Über uns</a>
        <a href="produkte.php">Unsere Produkte</a>
        <a href="agb.php">AGB</a>
    </div>
    <div class="logo">
        <a href="index.php"><img src="img/logo.png" alt="Markenlogo"></a> <!-- Hier das Logo einfügen -->
    </div>
</nav>

<!-- Hauptinhalt -->
<div class="container">
    <h1>Allgemeine Geschäftsbedingungen (AGB)</h1>
    <p>Willkommen zu den Allgemeinen Geschäftsbedingungen (AGB) von NGNVZN. Hier finden Sie alle relevanten Informationen und Bedingungen, die für die Nutzung unserer Website und den Kauf unserer Produkte gelten.</p>

    <h2>1. Geltungsbereich</h2>
    <p>1.1 Diese Allgemeinen Geschäftsbedingungen (AGB) gelten für alle Bestellungen, die über unseren Online-Shop auf <a href="http://www.dein-shop.de" style="color: #000000;">www.dein-shop.de</a> (nachfolgend „Online-Shop“ genannt) getätigt werden.</p>
    <p>1.2 Verbraucher im Sinne dieser AGB sind natürliche Personen, die ein Rechtsgeschäft zu einem Zweck abschließen, der weder ihrer gewerblichen noch selbständigen beruflichen Tätigkeit zugerechnet werden kann.</p>

    <h2>2. Vertragspartner</h2>
    <p>2.1 Der Kaufvertrag kommt zustande mit:</p>
    <p>NGNVZN<br>
        Auweg 33<br>
        6112 Wattens<br>
        Österreich<br>
        E-Mail: <a href="mailto:info@ngnvzn.com" style="color: black;">info@Ggooddontdie.com</a><br>
        Telefon: +43 06605022009</p>

    <h2>3. Angebot und Vertragsschluss</h2>
    <p>3.1 Die Darstellung der Produkte im Online-Shop stellt kein rechtlich verbindliches Angebot, sondern eine Aufforderung zur Bestellung dar.</p>
    <p>3.2 Durch das Absenden einer Bestellung im Online-Shop geben Sie ein verbindliches Angebot zum Abschluss eines Kaufvertrages ab. Wir werden den Zugang Ihrer Bestellung unverzüglich per E-Mail bestätigen. Diese Bestellbestätigung stellt noch keine verbindliche Annahme des Angebots dar.</p>
    <p>3.3 Der Kaufvertrag kommt erst durch eine ausdrückliche Bestätigung der Bestellung oder durch die Lieferung der Ware zustande.</p>

    <h2>4. Preise und Versandkosten</h2>
    <p>4.1 Alle Preise sind in Euro angegeben und enthalten die gesetzliche Mehrwertsteuer.</p>
    <p>4.2 Zusätzlich zu den angegebenen Preisen fallen Versandkosten an. Diese werden Ihnen vor Abgabe Ihrer verbindlichen Bestellung deutlich mitgeteilt.</p>

    <h2>5. Lieferung und Versand</h2>
    <p>5.1 Die Lieferung erfolgt an die von Ihnen angegebene Lieferadresse.</p>
    <p>5.2 Die Lieferzeit beträgt, sofern nicht anders angegeben, 3-5 Werktage. Auf eventuell abweichende Lieferzeiten weisen wir auf der jeweiligen Produktseite hin.</p>
    <p>5.3 Sollte die Lieferung der Ware nicht möglich sein, weil das Produkt nicht vorrätig ist oder die Lieferadresse falsch ist, behalten wir uns vor, den Vertrag zu widerrufen.</p>

    <h2>6. Zahlung</h2>
    <p>6.1 Die Zahlung erfolgt wahlweise per Vorkasse, Kreditkarte, PayPal oder anderen angebotenen Zahlungsmethoden.</p>
    <p>6.2 Bei Auswahl der Zahlungsmethode „Vorkasse“ nennen wir Ihnen unsere Bankverbindung in der Bestellbestätigung. Der Rechnungsbetrag ist innerhalb von 7 Tagen auf unser Konto zu überweisen.</p>

    <h2>7. Widerrufsrecht</h2>
    <p>7.1 Verbraucher haben das Recht, binnen 14 Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen.</p>
    <p>7.2 Die Widerrufsfrist beträgt 14 Tage ab dem Tag, an dem Sie oder ein von Ihnen benannter Dritter die Ware in Besitz genommen haben.</p>
    <p>7.3 Um Ihr Widerrufsrecht auszuüben, müssen Sie uns (NGNVZN, Musterstraße 123, 12345 Musterstadt, Deutschland, E-Mail: <a href="mailto:info@dein-shop.de" style="color: black;">info@gooddontdie.com</a>, Telefon: +49 123 456789) mittels einer eindeutigen Erklärung (z. B. ein mit der Post versandter Brief oder E-Mail) über Ihren Entschluss, diesen Vertrag zu widerrufen, informieren.</p>
    <p>7.4 Im Falle eines Widerrufs haben wir Ihnen alle Zahlungen, die wir von Ihnen erhalten haben, einschließlich der Lieferkosten (mit Ausnahme der zusätzlichen Kosten, die sich daraus ergeben, dass Sie eine andere Art der Lieferung als die von uns angebotene Standardlieferung gewählt haben), unverzüglich und spätestens binnen 14 Tagen ab dem Tag zurückzuzahlen, an dem die Mitteilung über Ihren Widerruf dieses Vertrags bei uns eingegangen ist.</p>

    <h2>8. Mängelhaftung</h2>
    <p>8.1 Es gelten die gesetzlichen Gewährleistungsrechte. Sollte ein Mangel an der Ware vorliegen, haben Sie die Wahl zwischen Nachbesserung oder Ersatzlieferung. Bei Fehlschlagen der Nachbesserung oder Ersatzlieferung haben Sie die Möglichkeit, den Kaufpreis zu mindern oder vom Vertrag zurückzutreten.</p>

    <h2>9. Haftung</h2>
    <p>9.1 Unsere Haftung für Schäden, die auf einer Verletzung des Lebens, des Körpers oder der Gesundheit beruhen, sowie für Schäden, die durch grobe Fahrlässigkeit oder Vorsatz entstanden sind, bleibt unberührt.</p>
    <p>9.2 Für andere Schäden haften wir nur bei vorsätzlicher oder grob fahrlässiger Pflichtverletzung, die auf einer Verletzung wes<h2>10. Datenschutz</h2>
    <p>10.1 Ihre Daten werden von uns nur zur Abwicklung Ihrer Bestellung verwendet und in Übereinstimmung mit den gesetzlichen Datenschutzbestimmungen behandelt.</p>
    <p>10.2 Weitere Informationen finden Sie in unserer Datenschutzerklärung.</p>

    <h2>11. Schlussbestimmungen</h2>
    <p>11.1 Es gilt das Recht der Bundesrepublik Deutschland unter Ausschluss des UN-Kaufrechts.</p>
    <p>11.2 Bei Streitigkeiten, die im Zusammenhang mit der Nutzung unseres Online-Shops entstehen, können Sie sich an die für Ihr Wohnsitzland zuständige Verbraucherschlichtungsstelle wenden.</p>
    <p>11.3 Sollten einzelne Bestimmungen dieser AGB unwirksam sein, bleibt die Wirksamkeit der übrigen Bestimmungen unberührt.</p>
</div>
<!-- Footer -->
<footer class="footer">
    <p>© 2024 GOOD DON'T DIE. Alle Rechte vorbehalten.</p>
</footer>
</body>
</html>
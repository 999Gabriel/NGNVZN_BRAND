<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

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

$user_id = $_SESSION['user_id'];

// User-Daten abrufen
$sql = "SELECT username, email FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Bestellungen abrufen
// Bestellungen abrufen
$sql_orders = "SELECT o.id, o.order_date, o.total AS total_amount
               FROM orders o
               WHERE o.user_id = :user_id
               ORDER BY o.order_date DESC";
$stmt_orders = $pdo->prepare($sql_orders);
$stmt_orders->execute(['user_id' => $user_id]);
$orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mein Konto - GOOD DON'T DIE</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        /* Basis-Stile für die Seite */
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            color: #000;
            background-color: #f9f9f9;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            padding: 10px 10px;
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

        .navbar .nav-links a:hover {
            background-color: #000;
            color: #fff;
        }

        /* Stile für das Konto-Dashboard */
        .account-page {
            padding: 80px 20px 20px; /* Padding oben für die Navbar */
            background-color: #fff;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%; /* Die Breite auf 100% setzen */
        }

        .account-page h1 {
            font-size: 28px;
            color: #000;
            margin-bottom: 20px;
            text-align: center;
        }

        .account-page p {
            font-size: 18px;
            color: #555;
            text-align: center;
            margin-bottom: 30px;
        }

        .account-page ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .account-page li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .account-page li:last-child {
            border-bottom: none;
        }

        .account-page a {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: #000000;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .account-page a:hover {
            background-color: #000000;
            transform: scale(1.05);
        }

        /* Tabelle für Bestellungen */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .order-table th, .order-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-table th {
            background-color: #f4f4f4;
        }

        .order-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            background-color: #000;
            color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
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

        @media (max-width: 768px) {
            .navbar .nav-links {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                position: static;
            }

            .navbar .nav-links a {
                font-size: 16px;
            }

            .account-page h1 {
                font-size: 24px;
            }

            .account-page p {
                font-size: 16px;
            }

            .account-page ul {
                font-size: 16px;
            }

            .account-page a {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="landing_page.php">
            <img src="img/logo.png" alt="GOOD DON'T DIE">
        </a>
    </div>
    <div class="nav-links">
        <a href="landing_page.php">Startseite</a>
        <a href="produkte.php">Produkte</a>
        <a href="agb.php">AGBs</a>
    </div>
</nav>

<!-- Account Page -->
<div class="account-page">
    <h1>Willkommen, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>Hier können Sie Ihre Kontoinformationen verwalten.</p>

    <!-- User Info -->
    <div class="user-info">
        <ul>
            <li><strong>Benutzername:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
            <li><strong>E-Mail:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
        </ul>
    </div>

    <!-- Orders -->
    <div class="orders">
        <h2>Ihre Bestellungen</h2>
        <?php if (count($orders) > 0): ?>
            <table class="order-table">
                <thead>
                <tr>
                    <th>Bestell-ID</th>
                    <th>Bestelldatum</th>
                    <th>Status</th>
                    <th>Gesamtbetrag</th>
                    <th>Lieferadresse</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_amount']); ?> EUR</td>
                        <td>
                            <?php echo htmlspecialchars($order['shipping_address']); ?><br>
                            <?php echo htmlspecialchars($order['shipping_city']); ?><br>
                            <?php echo htmlspecialchars($order['shipping_postal_code']); ?><br>
                            <?php echo htmlspecialchars($order['shipping_country']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Sie haben noch keine Bestellungen.</p>
        <?php endif; ?>
    </div>

    <!-- Logout Button -->
    <a href="logout.php">Ausloggen</a>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 GOOD DON'T DIE. Alle Rechte vorbehalten.</p>
</footer>
</body>
</html>
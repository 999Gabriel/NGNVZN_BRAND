<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "Alle Felder sind erforderlich.";
    } else {
        $sql = "SELECT id, password_hash FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: account.php');
            exit;
        } else {
            echo "Benutzername oder Passwort ist falsch.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        /* Basis-Stile für die Seite */
        body {
            font-family: 'Lora', serif;
            background-color: #f9f9f9; /* Heller Hintergrund für bessere Lesbarkeit */
            color: #333; /* Dunkelgraue Textfarbe */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Vollständige Höhe des Viewports */
        }

        /* Navbar */
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

        /* Container für das Formular */
        .form-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: #fff; /* Weißer Hintergrund für das Formular */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sanfter Schatten für Tiefe */
            text-align: center; /* Zentriert den Inhalt */
            margin: 100px auto 20px; /* Abstand oben für die Navbar und Abstand unten */
        }

        h1 {
            font-size: 24px;
            color: #000; /* Schwarze Textfarbe für den Titel */
            margin-bottom: 20px;
        }

        /* Formularfelder */
        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
            color: #000; /* Schwarze Textfarbe für Labels */
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 22px); /* Breite anpassen, um das Padding und die Border zu berücksichtigen */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        /* Submit-Button */
        button {
            width: 100%;
            padding: 15px;
            background-color: #000000; /* Schwarz für den Button-Hintergrund */
            border: none;
            border-radius: 5px;
            color: #fff; /* Weißer Text */
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: rgba(30, 29, 29, 0.8); /* Dunkleres Schwarz beim Hover */
            transform: scale(1.05); /* Subtiles Zoomen beim Hover */
        }

        /* Links */
        a {
            color: #000000; /* Schwarz für Links */
            text-decoration: none;
            display: block;
            margin-top: 15px;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            background-color: #000;
            color: #fff;
            margin-top: auto; /* Positioniert den Footer am unteren Ende */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar .nav-links {
                flex-direction: column;
                align-items: center;
            }

            .navbar .nav-links a {
                font-size: 16px;
            }

            .form-container {
                padding: 15px;
                margin: 80px auto 20px; /* Angepasster Abstand oben für kleinere Bildschirme */
            }

            button {
                font-size: 14px;
            }

            a {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="index.php">
            <img src="img/logo.png" alt="Logo">
        </a>
    </div>
    <div class="nav-links">
        <a href="index.php">Startseite</a>
        <a href="produkte.php">Produkte</a>
        <a href="account.php">Mein Konto</a>
        <a href="logout.php">Ausloggen</a>
    </div>
</nav>

<!-- Login Form -->
<div class="form-container">
    <h1>Anmeldung</h1>
    <form action="login.php" method="POST">
        <label for="username">Benutzername:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Einloggen</button>
    </form>
    <a href="register.php">Noch keinen Account? Hier registrieren.</a>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 MeinShop. Alle Rechte vorbehalten.</p>
</footer>
</body>
</html>
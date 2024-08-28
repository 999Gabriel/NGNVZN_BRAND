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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        echo "Alle Felder sind erforderlich.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => $password_hash
            ]);
            echo "Registrierung erfolgreich. <a href='login.php'>Jetzt einloggen</a>";
        } catch (PDOException $e) {
            echo 'Fehler bei der Registrierung: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung</title>
    <style>
        /* Basis-Stile für die Seite */
        body {
            font-family: 'Lora', 'serif';
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Volle Höhe des Viewports */
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
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 100px auto 20px; /* Abstand oben für die Navbar und Abstand unten */
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Formularfelder */
        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 22px); /* Breite anpassen, um das Padding und die Border zu berücksichtigen */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #000000;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: rgba(30, 29, 29, 0.8);
        }

        /* Links */
        a {
            color: #000000;
            text-decoration: none;
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

<!-- Registrierung Formular -->
<div class="form-container">
    <h1>Registrierung</h1>
    <form action="register.php" method="POST">
        <label for="username">Benutzername:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">E-Mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Registrieren</button>
    </form>
    <a href="login.php">Bereits registriert? Hier einloggen.</a>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 MeinShop. Alle Rechte vorbehalten.</p>
</footer>
</body>
</html>
<?php
$servername = "db";
$username = "root";
$password = "macintosh";
$database = "ngnvzn_store";

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $database);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

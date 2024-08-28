<?php
global $total, $order_id;
require 'vendor/autoload.php'; // Stripe-Bibliothek einbinden

\Stripe\Stripe::setApiKey('dein-stripe-secret-key');

$token = $_POST['stripeToken'];

try {
    $charge = \Stripe\Charge::create([
        'amount' => $total * 100, // Betrag in Cent
        'currency' => 'eur',
        'description' => 'Bezahlung für Bestellung',
        'source' => $token,
    ]);

    // Erfolgreiche Zahlung
    header('Location: order_confirmation.php?order_id=' . $order_id);

} catch (\Stripe\Exception\CardException $e) {
    // Fehler bei der Zahlung
    echo 'Fehler: ' . $e->getError()->message;
}
?>
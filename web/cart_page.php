<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warenkorb</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="cart-container">
    <h1>Ihr Warenkorb</h1>

    <?php if (count($cart) > 0): ?>
        <table class="cart-table">
            <thead>
            <tr>
                <th>Produkt</th>
                <th>Preis</th>
                <th>Menge</th>
                <th>Gesamt</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            foreach ($cart as $item):
                $total += $item['price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>€<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>€<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Gesamtsumme</td>
                <td>€<?php echo number_format($total, 2); ?></td>
            </tr>
            </tbody>
        </table>
        <a href="checkout.php" class="checkout-btn">Zur Kasse</a>
    <?php else: ?>
        <p>Ihr Warenkorb ist leer.</p>
    <?php endif; ?>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <style>
        body {
            font-family: 'Lora', serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
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

        .navbar .search-cart {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar .search-cart .cart-icon {
            font-size: 24px;
            color: #000;
            cursor: pointer;
            position: relative;
        }

        .navbar .search-cart .cart-icon:hover {
            color: #333;
        }

        .navbar .search-cart .cart-icon .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #f00;
            color: #fff;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .container {
            margin-top: 80px;
            width: 90%;
            max-width: 800px;
            margin: 80px auto 20px;
            padding: 20px;
            background-color: #fff; /* Changed to white */
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

        .section {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="email"], input[type="tel"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .checkout-button {
            display: block;
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 12px;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 20px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .checkout-button:hover {
            background-color: #333;
            transform: scale(1.05);
        }

        .checkout-button:active {
            transform: scale(0.95);
        }

        .order-summary ul {
            list-style-type: none;
            padding: 0;
        }

        .order-summary li {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 10px;
            }
            h1 {
                font-size: 24px;
            }
            .checkout-button {
                font-size: 16px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="logo">
        <a href="landing_page.php"><img src="img/logo.png" alt="Brand Logo"></a>
    </div>
    <div class="nav-links">
        <a href="landing_page.php">Home</a>
        <a href="agb.php">Terms</a>
        <a href="account.php">My Account</a>
    </div>
    <div class="search-cart">
        <div class="cart-icon">🛒</div>
    </div>
</nav>

<div class="container">
    <h1>Checkout</h1>

    <!-- Order Summary -->
    <div class="order-summary">
        <h2>Order Summary</h2>
        <ul id="cart-items-list">
            <!-- Cart items will be displayed here -->
        </ul>
    </div>
    <form action="order_confirmation.php" method="post">
        <div class="section">
            <h2>Billing Details</h2>
            <label for="billing-name">Name</label>
            <input type="text" id="billing-name" name="billing_name" required>

            <label for="billing-email">Email</label>
            <input type="email" id="billing-email" name="billing_email" required>

            <label for="billing-phone">Phone</label>
            <input type="tel" id="billing-phone" name="billing_phone" required>

            <label for="billing-address">Address</label>
            <textarea id="billing-address" name="billing_address" rows="3" required></textarea>

            <label for="billing-city">City</label>
            <input type="text" id="billing-city" name="billing_city" required>

            <label for="billing-zip">ZIP Code</label>
            <input type="text" id="billing-zip" name="billing_zip" required>

            <label for="billing-country">Country</label>
            <input type="text" id="billing-country" name="billing_country" required>
        </div>

        <div class="section">
            <h2>Shipping Details</h2>
            <label for="shipping-name">Name</label>
            <input type="text" id="shipping-name" name="shipping_name" required>

            <label for="shipping-address">Address</label>
            <textarea id="shipping-address" name="shipping_address" rows="3" required></textarea>

            <label for="shipping-city">City</label>
            <input type="text" id="shipping-city" name="shipping_city" required>

            <label for="shipping-zip">ZIP Code</label>
            <input type="text" id="shipping-zip" name="shipping_zip" required>

            <label for="shipping-country">Country</label>
            <input type="text" id="shipping-country" name="shipping_country" required>
        </div>

        <div class="section">
            <h2>Payment Details</h2>
            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card_number" required>

            <label for="card-expiry">Expiry Date</label>
            <input type="text" id="card-expiry" name="card_expiry" required>

            <label for="card-cvc">CVC</label>
            <input type="text" id="card-cvc" name="card_cvc" required>
        </div>

        <input type="submit" class="checkout-button" value="Place Order">
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('get_cart_items.php')
            .then(response => response.json())
            .then(data => {
                if (data.cartItems) {
                    const cartItemsList = document.getElementById('cart-items-list');
                    let total = 0;

                    data.cartItems.forEach(item => {
                        const itemTotal = item.price * item.quantity;
                        total += itemTotal;

                        const listItem = document.createElement('li');
                        const imagesHtml = item.images.map(imageUrl => `<img src="${imageUrl}" alt="${item.name}" style="width:50px;height:auto;">`).join(' ');
                        listItem.innerHTML = `${imagesHtml} ${item.name} (${item.quantity} pcs): €${itemTotal.toFixed(2)}`;
                        cartItemsList.appendChild(listItem);
                    });

                    const totalItem = document.createElement('li');
                    totalItem.innerHTML = `<strong>Total: €${total.toFixed(2)}</strong>`;
                    cartItemsList.appendChild(totalItem);
                } else {
                    console.error('Error fetching cart items:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
</body>
</html>
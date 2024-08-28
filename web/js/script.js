document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartIcon = document.getElementById('cartIcon');
    const cartPanel = document.getElementById('cartPanel');
    const closeCartPanel = document.getElementById('closeCartPanel');
    const cartItemsContainer = document.getElementById('cartItems');

    function updateCartPanel() {
        fetch('get_cart_items.php')
            .then(response => response.json())
            .then(data => {
                if (data.cartItems) {
                    cartItemsContainer.innerHTML = ''; // Lösche vorherige Einträge
                    data.cartItems.forEach(item => {
                        const itemElement = document.createElement('div');
                        itemElement.className = 'cart-item';
                        itemElement.innerHTML = `
                        <div class="cart-item-image">
                            <img src="${item.image_url}" alt="${item.name}">
                        </div>
                        <div class="cart-item-details">
                            <p class="item-name">${item.name}</p>
                            <p class="item-price">Preis: €${item.price}</p>
                            <p class="item-quantity">Menge: ${item.quantity}</p>
                            <p class="item-size">Größe: ${item.size}</p>
                        </div>
                    `;
                        cartItemsContainer.appendChild(itemElement);
                    });
                } else {
                    cartItemsContainer.innerHTML = '<p>Ihr Warenkorb ist leer.</p>';
                }
            })
            .catch(error => {
                console.error('Fehler beim Abrufen der Warenkorb-Daten:', error);
            });
    }

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const sizeSelect = this.previousElementSibling;
            const sizeId = sizeSelect ? sizeSelect.value : '';

            if (!sizeId) {
                alert('Bitte wählen Sie eine Größe aus, bevor Sie das Produkt in den Warenkorb legen.');
                return;
            }

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}&size_id=${sizeId}`
            })
                .then(response => response.json())
                .then(data =>{
                    if (data.success) {
                        alert('Artikel wurde zum Warenkorb hinzugefügt.');
                        updateCartPanel();
                    } else {
                        alert('Fehler beim Hinzufügen des Artikels.');
                    }
                })
                .catch(error => {
                    console.error('Fehler beim Hinzufügen zum Warenkorb:', error);
                });
        });
    });
    cartIcon.addEventListener('click', function() {
        cartPanel.style.display = cartPanel.style.display === 'none' ? 'block' : 'none';
        updateCartPanel();
    });

    closeCartPanel.addEventListener('click', function() {
        cartPanel.style.display = 'none';
    });
}
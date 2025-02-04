document.addEventListener("DOMContentLoaded", function () {
    // Обробка навігаційних лінків
    const links = document.querySelectorAll(".nav-list-link");

    links.forEach(link => {
        link.addEventListener("click", function (event) {
            const targetId = this.getAttribute("href");
            if (targetId.startsWith("#")) {
                event.preventDefault();
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 50,
                        behavior: "smooth"
                    });
                }
            }
        });
    });

    // Обробка кнопок "Купити"
    const buyButtons = document.querySelectorAll(".buy-btn");

    buyButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            const shoeCard = this.closest(".shoe-card"); // Знаходимо батьківський контейнер товару
            const sizeSelect = shoeCard.querySelector(".size-select"); // Знаходимо селект розміру
            const selectedSize = sizeSelect.value; // Отримуємо вибраний розмір

            if (!selectedSize) {
                alert("Будь ласка, виберіть розмір перед покупкою!");
                event.preventDefault(); // Зупиняємо перехід на сторінку оформлення
                return;
            }

            // Зберігаємо вибраний розмір у URL для передачі на checkout.html
            const shoeName = shoeCard.querySelector("h3").innerText; // Назва товару
            const shoePrice = shoeCard.querySelector(".price").innerText.replace('₴', '').trim(); // Видаляємо ₴ із ціни

            window.location.href = `checkout.html?shoe=${encodeURIComponent(shoeName)}&size=${encodeURIComponent(selectedSize)}&price=${encodeURIComponent(shoePrice)}`;
        });
    });

    // Додавання товару в кошик
    const addToCartButtons = document.querySelectorAll(".add-to-cart-btn");

    addToCartButtons.forEach(button => {
        button.addEventListener("click", function() {
            const shoeCard = this.closest(".shoe-card"); 
            const sizeSelect = shoeCard.querySelector(".size-select");
            const selectedSize = sizeSelect.value; 

            if (!selectedSize) {
                alert("Будь ласка, виберіть розмір перед додаванням у кошик.");
                return;
            }

            const shoeName = shoeCard.querySelector("h3").textContent;
            const shoePrice = parseFloat(shoeCard.querySelector(".price").textContent.replace('₴', '').trim());

            // Отримуємо кошик з localStorage або створюємо новий масив
            let cartItems = JSON.parse(localStorage.getItem("cart")) || [];

            // Перевіряємо, чи такий товар уже є в кошику
            const existingItem = cartItems.find(item => item.name === shoeName && item.size === selectedSize);

            if (existingItem) {
                existingItem.quantity += 1; // Збільшуємо кількість, якщо товар вже є
            } else {
                cartItems.push({ name: shoeName, size: selectedSize, price: shoePrice, quantity: 1 });
            }

            // Зберігаємо оновлений кошик у localStorage
            localStorage.setItem("cart", JSON.stringify(cartItems));

            alert(`${shoeName} - Розмір ${selectedSize} додано в кошик.`);
            updateCart(); // Оновлюємо відображення кошика
        });
    });

    // Створення елементів кошика для відображення
    const cartItemsContainer = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');
    const checkoutBtn = document.getElementById('checkout-btn');

    // Функція для оновлення кошика
    function updateCart() {
        if (!cartItemsContainer || !totalPriceElement || !checkoutBtn) return; // Перевіряємо, чи є елементи

        cartItemsContainer.innerHTML = ''; 
        let totalPrice = 0;

        const cartItems = JSON.parse(localStorage.getItem("cart")) || [];

        cartItems.forEach(item => {
            const cartItemDiv = document.createElement('div');
            cartItemDiv.classList.add('cart-item');
            cartItemDiv.innerHTML = `
                <span>${item.name} (Розмір: ${item.size}) x ${item.quantity}</span>
                <span>${item.price * item.quantity} грн</span>
            `;
            cartItemsContainer.appendChild(cartItemDiv);

            totalPrice += item.price * item.quantity;
        });

        totalPriceElement.textContent = totalPrice;
    }

    // Обробка кнопки "Оформити замовлення"
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            alert('Замовлення оформлено!');
            localStorage.removeItem("cart"); // Очищаємо кошик після оформлення
            updateCart(); // Оновлюємо відображення
        });
    }

    // Оновлення кошика при завантаженні сторінки
    updateCart();
});

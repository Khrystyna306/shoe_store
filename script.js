document.addEventListener("DOMContentLoaded", function () {
    // Обробка кнопок "Купити"
    document.querySelectorAll(".buy-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            const shoeCard = this.closest(".shoe-card");
            const sizeSelect = shoeCard.querySelector(".size-select");
            const selectedSize = sizeSelect.value;

            if (!selectedSize) {
                alert("Будь ласка, виберіть розмір перед покупкою!");
                event.preventDefault();
                return;
            }

            const shoeName = shoeCard.querySelector("h3").innerText;
            const shoePrice = shoeCard.querySelector(".price").innerText.replace('₴', '').trim();

            // Перенаправлення на checkout.html з параметрами
            window.location.href = `checkout.php?shoe=${encodeURIComponent(shoeName)}&size=${encodeURIComponent(selectedSize)}&price=${encodeURIComponent(shoePrice)}`;
        });
    });

    // Обробка кнопок для додавання товару в кошик
    document.querySelectorAll(".add-to-cart-btn").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");
            let shoeCard = this.closest(".shoe-card");
            let sizeSelect = shoeCard.querySelector(".size-select");
            let selectedSize = sizeSelect.value; // Отримуємо вибраний розмір

            if (!selectedSize) {
                alert("Будь ласка, виберіть розмір перед додаванням у кошик!");
                return;
            }

            // Відправка даних на сервер
            fetch("add_to_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `product_id=${productId}&size=${encodeURIComponent(selectedSize)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Товар додано в кошик!");
                    updateCartCount();
                } else {
                    alert("Помилка: " + data.message);
                }
            })
            .catch(error => {
                console.error("Помилка при додаванні товару в кошик:", error);
                alert("Сталася помилка при додаванні товару.");
            });
        });
    });

    // Функція оновлення кількості товарів у кошику
    function updateCartCount() {
        fetch("cart_count.php")
        .then(response => response.text())
        .then(count => {
            let cartCountElement = document.getElementById("cart-count");

            // Якщо елемент відсутній, створюємо його
            if (!cartCountElement) {
                cartCountElement = document.createElement("span");
                cartCountElement.id = "cart-count";
                cartCountElement.textContent = count;
                
                // Додаємо у шапку або інше місце
                const header = document.querySelector(".header") || document.body;
                header.appendChild(cartCountElement);
            }

            cartCountElement.textContent = count;
        })
        .catch(error => console.error("Помилка при отриманні кількості товарів у кошику:", error));
    }

    // Перевірка чи є checkout-btn, якщо ні – стежимо за появою
    function handleCheckoutButton() {
        const checkoutBtn = document.querySelector("#checkout-btn");
        if (checkoutBtn) {
            checkoutBtn.addEventListener("click", function () {
                fetch("check_cart.php")
                    .then(response => response.json())
                    .then(data => {
                        if (data.cartCount > 0) {
                            window.location.href = 'checkout.php';
                        } else {
                            alert("Кошик порожній. Додайте товари в кошик.");
                        }
                    })
                    .catch(error => console.error("Помилка при перевірці кошика:", error));
            });

            return true; // Кнопка знайдена
        }
        return false; // Кнопки ще немає
    }

    if (!handleCheckoutButton()) {
        const observer = new MutationObserver(() => {
            if (handleCheckoutButton()) {
                observer.disconnect(); // Якщо кнопка з'явилася, зупиняємо стеження
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }

    // Обробка видалення товарів з кошика
    document.querySelectorAll(".remove-btn").forEach(button => {
        button.addEventListener("click", function () {
            let cartId = this.getAttribute("data-id");

            fetch("remove_from_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "cart_id=" + cartId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Товар видалено!");
                    updateCartCount();
                    location.reload();
                } else {
                    alert("Помилка: " + data.message);
                }
            })
            .catch(error => console.error("Помилка при видаленні товару:", error));
        });
    });

    // Виклик оновлення кошика після завантаження сторінки
    updateCartCount();
});

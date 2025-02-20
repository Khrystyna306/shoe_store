CREATE DATABASE shoe_store;
USE shoe_store;

-- Таблиця користувачів
create table  users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблиця категорій товарів
create table categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблиця товарів
create table products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category_id INT,
    image VARCHAR(255),
	size varchar(60),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Таблиця кошика
create table  cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Таблиця замовлень
create table orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'shipped', 'delivered') DEFAULT 'pending',
    phone VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    nova_poshta_branch VARCHAR(50) NOT NULL,
    payment_method ENUM('Банківська карта', 'Готівка при отриманні') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- Таблиця товарів у замовленнях
create table  order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Таблиця логів адміністратора
create table  admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

insert into users (name, email, password, role) VALUES 
('Admin', 'admin@example.com', 'admin123', 'admin'),
('Бабіїв Соломія', 'solomia.babiv@example.com', '1111', 'customer'),
('Прокопів Олександр', 'prokopiv.olexandr@example.com', '1111', 'customer');

insert into categories (name) VALUES 
('Чоловіче'),
('Жіноче'),
('Дитяче'),
('Знижки до 70%'),
('Новинки');

insert into products (name, description, price, stock, category_id, image, size ) VALUES 
('Nike Air Max', 'Комфортні та стильні кросівки.', 3500.00, 10, 1, '/images/Air-max.jpg',' 39, 40, 41, 42'),
('Adidas Ultraboost', 'Бігові кросівки для максимального комфорту.', 4200.00, 5, 2, '/images/adidas.jpg','36,37,38,39'),
('Класичні чоловічі туфлі', 'Стильні чорні туфлі для роботи.', 2700.00, 7, 1, '/images/чорні туфлі чоловічі-б..jpg','40, 41, 42, 43'),
('Зимові черевики Timberland', 'Міцні та теплі черевики.', 1800.00, 8, 4, '/images/Черевики зимові.jpg','30, 31, 32, 33'),
('Жіночі туфлі', 'Класичні підбори для романтичних вечерь.', 5000.00, 8, 4, '/images/Червоні туфлі.jpg','36, 37, 38, 39'),
('Дитячі туфлі', 'Красиві туфлі для святкових подій.', 1800.00, 12, 3, '/images/туфлі дитячі.jpg','20,21,22,23');

insert into cart (user_id, product_id, quantity) VALUES 
(2, 1, 1),
(3, 4, 2);   

insert into orders (user_id, total_price, status, phone, city, nova_poshta_branch, payment_method) VALUES 
(2, 4200.00, 'pending', '+380987654321', 'Київ', 'Відділення №5', 'Банківська карта');


insert into admin_logs (admin_id, action) VALUES 
(1, 'Додано новий товар: Nike Air Max'),
(1, 'Користувач Олена Сидорова оформила замовлення #2'),
(1, 'Змінено статус замовлення #2 на "shipped"');

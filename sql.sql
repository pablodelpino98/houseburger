-- CREATE --
CREATE DATABASE burger_house;
USE burger_house;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- PRODUCTOS
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50) NOT NULL
);

-- PEDIDOS
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    delivery_method ENUM('domicilio', 'recoger') NOT NULL DEFAULT 'domicilio',
    delivery_address VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- DETALLES DE PEDIDO
CREATE TABLE order_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    is_combo TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);


-- INSERT--

INSERT INTO products (name, description, price, image, category) VALUES
('Hamburguesa Clásica', 'Carne de vacuno 100%, queso cheddar, lechuga, tomate, pepinillos y salsa especial.', 7.50, 'burger1.jpg', 'hamburguesa'),
('Hamburguesa BBQ', 'Carne de vacuno 100% a la parrilla con salsa barbacoa, queso cheddar, bacon y cebolla caramelizada.', 8.90, 'burger2.jpg', 'hamburguesa'),
('Hamburguesa Vegana', 'Hamburguesa vegetal, queso camembert, aguacate, tomate, rúcula y mayonesa vegana.', 7.80, 'burger3.jpg', 'hamburguesa'),
('Hamburguesa Picante', 'Carne de vacuno 100%, jalapeños, queso pepper jack, lechuga y salsa chipotle picante.', 8.50, 'burger4.jpg', 'hamburguesa'),
('Hamburguesa Doble Queso', 'Doble carne de vacuno 100%, doble queso cheddar, cebolla, kétchup y mostaza.', 9.20, 'burger5.jpg', 'hamburguesa'),
('Hamburguesa con Huevo', 'Carne de vacuno 100%, queso suizo, bacon crujiente y huevo frito con mayonesa de ajo.', 8.70, 'burger6.jpg', 'hamburguesa'),
('Papas Fritas Clásicas', 'Papas cortadas a mano, crujientes por fuera y suaves por dentro. Servidas con salsas clásicas.', 2.50, 'fries1.jpg', 'papas'),
('Papas con Queso y Bacon', 'Una montaña de papas cubiertas con queso fundido y trocitos de bacon crujiente.', 3.90, 'fries2.jpg', 'papas'),
('Nachos Supreme', 'Totopos de maíz con queso fundido, guacamole, jalapeños, crema agria y pico de gallo.', 4.50, 'nachos.jpg', 'nachos'),
('Coca-cola (50CL)', '', 2.50, 'cocacola.jpg', 'refresco'),
('Coca-cola ZERO (50CL)', '', 2.50, 'cocacola_zero.jpg', 'refresco'),
('Fanta Naranja (50CL)', '', 2.50, 'fanta.jpg', 'refresco'),
('Tropical (33CL)', '', 2.80, 'tropical.jpg', 'cerveza'),
('Tropical 0,0% (33CL)', '', 2.80, 'tropical_zero.jpg', 'cerveza'),
('Estrella Galicia (33CL)', '', 2.80, 'estrella.jpg', 'cerveza'),
('Paulaner (33CL)', '', 3.60, 'paulaner.jpg', 'cerveza');


-- Insertar usuario admin
INSERT INTO users (name, email, phone, address, password) VALUES
('Administrador Principal', 'admin@burgerhouse.com', '612345678', 'Calle Principal 123, Madrid', '$2y$10$eMRXwoQM77BAiMC1HQBMh.j0UJim5SiID1gVkWrmTL6JwqeGsBL1y'); 
-- La contraseña es "admin001" hasheada con bcrypt

-- Declarar variables para los IDs
SET @admin_id = LAST_INSERT_ID();
SET @order_total = 0;

-- Insertar 20 pedidos para el admin
INSERT INTO orders (user_id, total) VALUES
(@admin_id, 15.00), (@admin_id, 22.50), (@admin_id, 18.70), (@admin_id, 32.40),
(@admin_id, 12.30), (@admin_id, 25.60), (@admin_id, 19.80), (@admin_id, 28.90),
(@admin_id, 14.20), (@admin_id, 21.50), (@admin_id, 17.30), (@admin_id, 24.60),
(@admin_id, 11.40), (@admin_id, 29.70), (@admin_id, 16.80), (@admin_id, 23.20),
(@admin_id, 13.50), (@admin_id, 20.90), (@admin_id, 27.30), (@admin_id, 31.80);

-- Insertar detalles para cada pedido (2-3 productos por pedido)
-- Pedido 1
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 7.50), (1, 7, 2, 2.50), (1, 10, 1, 2.50);

-- Pedido 2
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(2, 2, 1, 8.90), (2, 8, 1, 3.90), (2, 11, 2, 2.50), (2, 15, 1, 2.80);

-- Pedido 3
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(3, 3, 1, 7.80), (3, 9, 1, 4.50), (3, 13, 1, 2.80);

-- Pedido 4
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(4, 4, 2, 8.50), (4, 7, 3, 2.50), (4, 16, 2, 3.60);

-- Pedido 5
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(5, 5, 1, 9.20), (5, 10, 1, 2.50);

-- Pedido 6
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(6, 6, 1, 8.70), (6, 8, 1, 3.90), (6, 12, 1, 2.50), (6, 14, 1, 2.80);

-- Pedido 7
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(7, 1, 2, 7.50), (7, 7, 1, 2.50);

-- Pedido 8
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(8, 2, 1, 8.90), (8, 3, 1, 7.80), (8, 9, 1, 4.50);

-- Pedido 9
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(9, 4, 1, 8.50), (9, 8, 1, 3.90), (9, 11, 1, 2.50);

-- Pedido 10
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(10, 5, 1, 9.20), (10, 7, 2, 2.50), (10, 15, 1, 2.80);

-- Pedido 11
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(11, 6, 1, 8.70), (11, 10, 1, 2.50);

-- Pedido 12
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(12, 1, 1, 7.50), (12, 2, 1, 8.90), (12, 16, 1, 3.60);

-- Pedido 13
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(13, 3, 1, 7.80), (13, 7, 1, 2.50);

-- Pedido 14
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(14, 4, 1, 8.50), (14, 5, 1, 9.20), (14, 9, 1, 4.50), (14, 13, 1, 2.80);

-- Pedido 15
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(15, 6, 1, 8.70), (15, 8, 1, 3.90), (15, 12, 1, 2.50);

-- Pedido 16
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(16, 1, 1, 7.50), (16, 10, 2, 2.50), (16, 14, 1, 2.80);

-- Pedido 17
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(17, 2, 1, 8.90), (17, 7, 1, 2.50);

-- Pedido 18
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(18, 3, 1, 7.80), (18, 11, 1, 2.50), (18, 15, 1, 2.80);

-- Pedido 19
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(19, 4, 2, 8.50), (19, 16, 1, 3.60);

-- Pedido 20
INSERT INTO order_details (order_id, product_id, quantity, price) VALUES
(20, 5, 1, 9.20), (20, 6, 1, 8.70), (20, 9, 1, 4.50), (20, 12, 1, 2.50);
-- CREATE --

CREATE DATABASE burger_house;
USE burger_house;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50) NOT NULL
);

CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'preparing', 'ready', 'delivered') DEFAULT 'pending',
    delivery_type ENUM('pickup', 'delivery') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
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

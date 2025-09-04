CREATE DATABASE gastos;
USE gastos;

CREATE TABLE movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('ingreso','gasto') NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    fecha DATE NOT NULL,
    nota TEXT
);

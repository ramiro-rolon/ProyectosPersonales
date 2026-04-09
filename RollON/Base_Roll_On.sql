CREATE DATABASE IF NOT EXISTS cotizador_cortinas;
USE cotizador_cortinas;

-- 1. Tabla de Telas
CREATE TABLE Telas (
    id_tela INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tela VARCHAR(100) NOT NULL,
    precio_m2 DECIMAL(10, 2) NOT NULL,
    ancho_maximo_tela DECIMAL(10,2) DEFAULT 3.00
);

-- 2. Tabla de Dispositivos (Soportes/Mecanismos)
CREATE TABLE Dispositivos (
    id_dispositivo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_dispositivo VARCHAR(100),
    ancho_minimo DECIMAL(10, 2) NOT NULL,
    ancho_maximo DECIMAL(10, 2) NOT NULL,
    precio_dispositivo DECIMAL(10, 2) NOT NULL
);

-- 3. Tabla de Extras (Servicios o productos adicionales)
CREATE TABLE Extras (
    id_extra INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL,
    precio_fijo DECIMAL(10, 2) NOT NULL
);

-- 4. Tabla de Pedidos (Encabezado)
CREATE TABLE Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    nombre_cliente VARCHAR(100),
    -- El estado ayuda a filtrar: 'Presupuesto', 'Confirmado', 'Terminado', 'Entregado'
    estado ENUM('Presupuesto', 'En Produccion', 'Listo', 'Entregado', 'Cancelado') DEFAULT 'Presupuesto',
    total_pedido DECIMAL(10, 2) DEFAULT 0.00
);

-- 5. Tabla de Cortinas (Detalle del Pedido)
-- Aquí se guarda cada cortina específica solicitada
CREATE TABLE Cortinas (
    id_cortina INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_tela INT,
    id_dispositivo INT,
    ancho DECIMAL(10, 2) NOT NULL,
    largo DECIMAL(10, 2) NOT NULL,
    subtotal_cortina DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_tela) REFERENCES Telas(id_tela),
    FOREIGN KEY (id_dispositivo) REFERENCES Dispositivos(id_dispositivo)
);

-- 6. Tabla Intermedia: Pedido_Extras
-- Conecta los extras con el pedido general
CREATE TABLE Pedido_Extras (
    id_pedido_extra INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_extra INT,
    cantidad INT DEFAULT 1,
    precio_al_momento DECIMAL(10, 2), -- Guardamos el precio por si cambia en el futuro
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_extra) REFERENCES Extras(id_extra)
);



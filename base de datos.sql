-- ============================================
-- BASE DE DATOS SHOP
-- Sistema Ecommerce de productos de belleza
-- ============================================

DROP DATABASE IF EXISTS shop;

CREATE DATABASE shop
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE shop;


-- ============================================
-- USUARIOS
-- ============================================
CREATE TABLE usuarios(
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    latitud DECIMAL(10,8),
    longitud DECIMAL(11,8),
    rol ENUM('cliente','admin') DEFAULT 'cliente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- CATEGORIAS
-- ============================================

CREATE TABLE categorias(
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255)
);


-- ============================================
-- MARCAS
-- ============================================

CREATE TABLE marcas(
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    logo VARCHAR(255)
);


-- ============================================
-- PRODUCTOS
-- ============================================

CREATE TABLE productos(
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT,
    id_marca INT,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    precio_oferta DECIMAL(10,2),
    imagen VARCHAR(255),
    stock INT DEFAULT 0,
    destacado BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(id_categoria)
    REFERENCES categorias(id_categoria)
    ON DELETE SET NULL,

    FOREIGN KEY(id_marca)
    REFERENCES marcas(id_marca)
    ON DELETE SET NULL
);


-- ============================================
-- IMAGENES PRODUCTOS
-- ============================================

CREATE TABLE imagenes_productos(
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    ruta_imagen VARCHAR(255),

    FOREIGN KEY(id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE
);


-- ============================================
-- INVENTARIO
-- ============================================

CREATE TABLE inventario(
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    cantidad INT,
    tipo ENUM('entrada','salida'),
    descripcion VARCHAR(200),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE
);


-- ============================================
-- CARRITO
-- ============================================

CREATE TABLE carrito(
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(id_usuario)
    REFERENCES usuarios(id_usuario)
    ON DELETE CASCADE
);


-- ============================================
-- DETALLE CARRITO
-- ============================================

CREATE TABLE detalle_carrito(
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito INT,
    id_producto INT,
    cantidad INT DEFAULT 1,

    FOREIGN KEY(id_carrito)
    REFERENCES carrito(id_carrito)
    ON DELETE CASCADE,

    FOREIGN KEY(id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE
);


-- ============================================
-- PEDIDOS
-- ============================================

CREATE TABLE pedidos(
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),

    estado ENUM(
        'pendiente',
        'pagado',
        'enviado',
        'entregado',
        'cancelado'
    ) DEFAULT 'pendiente',

    FOREIGN KEY(id_usuario)
    REFERENCES usuarios(id_usuario)
    ON DELETE CASCADE
);


-- ============================================
-- DETALLE PEDIDOS
-- ============================================

CREATE TABLE detalle_pedido(
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    cantidad INT,
    precio DECIMAL(10,2),

    FOREIGN KEY(id_pedido)
    REFERENCES pedidos(id_pedido)
    ON DELETE CASCADE,

    FOREIGN KEY(id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE
);


-- ============================================
-- PAGOS
-- ============================================

CREATE TABLE pagos(
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,

    metodo_pago ENUM(
        'tarjeta',
        'paypal',
        'transferencia',
        'efectivo'
    ),

    monto DECIMAL(10,2),

    estado ENUM(
        'pendiente',
        'confirmado',
        'rechazado'
    ) DEFAULT 'pendiente',

    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(id_pedido)
    REFERENCES pedidos(id_pedido)
    ON DELETE CASCADE
);


-- ============================================
-- ENVIOS
-- ============================================

CREATE TABLE envios(
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    direccion TEXT,
    empresa_envio VARCHAR(100),
    codigo_seguimiento VARCHAR(100),

    estado ENUM(
        'preparando',
        'en camino',
        'entregado'
    ) DEFAULT 'preparando',

    fecha_envio TIMESTAMP NULL,

    FOREIGN KEY(id_pedido)
    REFERENCES pedidos(id_pedido)
    ON DELETE CASCADE
);


-- ============================================
-- FAVORITOS
-- ============================================

CREATE TABLE favoritos(
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_producto INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(id_usuario)
    REFERENCES usuarios(id_usuario)
    ON DELETE CASCADE,

    FOREIGN KEY(id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE
);


-- ============================================
-- RESEÑAS
-- ============================================

CREATE TABLE resenas(
    id_resena INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_producto INT,
    puntuacion INT,
    comentario TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(id_usuario)
    REFERENCES usuarios(id_usuario)
    ON DELETE CASCADE,

    FOREIGN KEY(id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE
);


-- ============================================
-- PROMOCIONES
-- ============================================

CREATE TABLE promociones(
    id_promocion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    descuento DECIMAL(5,2),
    fecha_inicio DATE,
    fecha_fin DATE,
    imagen VARCHAR(255)
);


-- ============================================
-- PRODUCTOS PROMOCIONES
-- ============================================

CREATE TABLE productos_promociones(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    id_promocion INT,

    FOREIGN KEY(id_producto)
    REFERENCES productos(id_producto)
    ON DELETE CASCADE,

    FOREIGN KEY(id_promocion)
    REFERENCES promociones(id_promocion)
    ON DELETE CASCADE
);


-- ============================================
-- BLOG
-- ============================================

CREATE TABLE blog(
    id_blog INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150),
    contenido TEXT,
    imagen VARCHAR(255),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- DATOS INICIALES
-- ============================================



INSERT INTO marcas(nombre)
VALUES
('ATK'),
('Lamzu'),
('Attack Shark'),
('Scyrox');




INSERT INTO promociones
(nombre,descripcion,descuento,fecha_inicio,fecha_fin,imagen)
VALUES

('Coleccion ATK',
'Nueva coleccion ATK 2026',
20,
'2026-01-01',
'2026-12-31',
'collection-1.jpg'),

('Compra 3 te regalamos 1',
'Apartir de 1500 Bs en adelante',
50,
'2026-02-01',
'2026-03-01',
'collection-3.jpg');


INSERT INTO blog
(titulo,contenido,imagen)
VALUES

('Cuidado de los perifericos',
'Consejos para la limpieza de tu setup',
'blog-1.jpg'),

('Nuevos productos',
'Tendencias del mercado',
'blog-2.jpg'),

('Nuestra historia',
'Conoce nuestra tienda',
'blog-3.jpg');


-- ============================================
-- FIN DE BASE DE DATOS SHOP
-- ============================================
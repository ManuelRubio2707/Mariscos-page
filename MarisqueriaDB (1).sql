IF DB_ID('MarisqueriaDB') IS NULL
    CREATE DATABASE MarisqueriaDB;
GO
USE MarisqueriaDB;
GO

CREATE TABLE categorias_inventario (
    id_categoria INT IDENTITY(1,1) PRIMARY KEY,
    nombre       VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE productos (
    id_producto  INT IDENTITY(1,1) PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    cantidad     INT NOT NULL DEFAULT 0,
    imagen       VARCHAR(500) NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT FK_productos_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias_inventario(id_categoria)
);

CREATE TABLE categorias_menu (
    id_categoria INT IDENTITY(1,1) PRIMARY KEY,
    nombre       VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE platillos (
    id_platillo  INT IDENTITY(1,1) PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    precio       DECIMAL(10,2) NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT FK_platillos_categoria
        FOREIGN KEY (id_categoria) REFERENCES categorias_menu(id_categoria)
);

CREATE TABLE usuarios (
    id_usuario INT IDENTITY(1,1) PRIMARY KEY,
    nombre     VARCHAR(100) NOT NULL,
    usuario    VARCHAR(50)  NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol        VARCHAR(20)  NOT NULL
        CONSTRAINT DF_usuarios_rol DEFAULT 'Cajero'
        CONSTRAINT CK_usuarios_rol CHECK (rol IN ('Administrador','Cajero'))
);

CREATE TABLE cuentas (
    id_cuenta   INT IDENTITY(1,1) PRIMARY KEY,
    fecha       DATETIME NOT NULL DEFAULT GETDATE(),
    total       DECIMAL(10,2) NOT NULL DEFAULT 0,
    estado      VARCHAR(20) NOT NULL DEFAULT 'Abierta'
        CONSTRAINT CK_cuentas_estado CHECK (estado IN ('Abierta','Pagada')),
    mesero      VARCHAR(100) NULL,
    id_usuario  INT NULL,
    metodo_pago VARCHAR(20) NULL
        CONSTRAINT CK_cuentas_metodo CHECK (metodo_pago IN ('Efectivo','Tarjeta')),
    CONSTRAINT FK_cuentas_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE detalle_cuenta (
    id_detalle      INT IDENTITY(1,1) PRIMARY KEY,
    id_cuenta       INT NOT NULL,
    id_platillo     INT NOT NULL,
    cantidad        INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    CONSTRAINT FK_detalle_cuenta   FOREIGN KEY (id_cuenta)   REFERENCES cuentas(id_cuenta),
    CONSTRAINT FK_detalle_platillo FOREIGN KEY (id_platillo) REFERENCES platillos(id_platillo)
);
GO

INSERT INTO categorias_inventario (nombre) VALUES ('Pez'),('Cubiertos'),('Bebidas'),('materiales');
INSERT INTO categorias_menu (nombre) VALUES ('Calientes'),('Fríos'),('Bebidas');
INSERT INTO productos (nombre, cantidad, id_categoria) VALUES ('Camarón', 20, 1);
INSERT INTO platillos (nombre, precio, id_categoria) VALUES ('Platillo 1', 159.00, 1), ('Platillo 3', 189.00, 2);
GO

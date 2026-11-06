--------------------------------------------------
-- 1. CREACIÓN Y USO DE LA BASE DE DATOS
--------------------------------------------------
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'MarisqueriaDB')
BEGIN
    CREATE DATABASE MarisqueriaDB;
END
GO

USE MarisqueriaDB;
GO

--------------------------------------------------
-- 2. ELIMINACIÓN DE TABLAS EXISTENTES (ORDEN INVERSO)
--------------------------------------------------
IF OBJECT_ID('Factura_Platillo', 'U') IS NOT NULL DROP TABLE Factura_Platillo;
IF OBJECT_ID('Platillo_Insumo', 'U') IS NOT NULL DROP TABLE Platillo_Insumo;
IF OBJECT_ID('Factura', 'U') IS NOT NULL DROP TABLE Factura;
IF OBJECT_ID('Usuarios', 'U') IS NOT NULL DROP TABLE Usuarios;
IF OBJECT_ID('Platillo', 'U') IS NOT NULL DROP TABLE Platillo;
IF OBJECT_ID('Insumo', 'U') IS NOT NULL DROP TABLE Insumo;
GO

--------------------------------------------------
-- 3. CREACIÓN DE TABLAS PRINCIPALES
--------------------------------------------------

-- Tabla: Insumo
CREATE TABLE Insumo (
    id_insumo INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    url VARCHAR(255) NULL,
    cantidad INT NOT NULL DEFAULT 0
);
GO

-- Tabla: Platillo
CREATE TABLE Platillo (
    id_platillo INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL
);
GO

-- Tabla: Usuarios
CREATE TABLE Usuarios (
    id_usuario INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    tipo_usuario VARCHAR(50) NOT NULL -- Ej. 'Mesero', 'Administrador', 'Mantenimiento'
);
GO

--------------------------------------------------
-- 4. CREACIÓN DE TABLAS CON RELACIONES (FK)
--------------------------------------------------

-- Tabla: Factura (Relacionada con Usuarios)
CREATE TABLE Factura (
    id_factura INT IDENTITY(1,1) PRIMARY KEY,
    fecha DATE NOT NULL DEFAULT GETDATE(),
    hora TIME NOT NULL DEFAULT CAST(GETDATE() AS TIME),
    total DECIMAL(10,2) NOT NULL,
    forma_de_pago VARCHAR(50) NOT NULL, -- Ej. 'Efectivo', 'Transferencia'
    FK_usuario INT NOT NULL,
    CONSTRAINT FK_Factura_Usuarios FOREIGN KEY (FK_usuario) 
        REFERENCES Usuarios(id_usuario) 
        ON DELETE CASCADE ON UPDATE CASCADE
);
GO

-- Tabla Intermedia: Platillo_Insumo (Relación N:M entre Platillo e Insumo)
CREATE TABLE Platillo_Insumo (
    id_platillo_insumo INT IDENTITY(1,1) PRIMARY KEY,
    FK_insumo INT NOT NULL,
    FK_platillo INT NOT NULL,
    CONSTRAINT FK_PlatilloInsumo_Insumo FOREIGN KEY (FK_insumo) 
        REFERENCES Insumo(id_insumo) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FK_PlatilloInsumo_Platillo FOREIGN KEY (FK_platillo) 
        REFERENCES Platillo(id_platillo) 
        ON DELETE CASCADE ON UPDATE CASCADE
);
GO

-- Tabla Intermedia: Factura_Platillo (Relación N:M entre Factura y Platillo)
CREATE TABLE Factura_Platillo (
    id_factura_platillo INT IDENTITY(1,1) PRIMARY KEY,
    FK_platillo INT NOT NULL,
    FK_factura INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    CONSTRAINT FK_FacturaPlatillo_Platillo FOREIGN KEY (FK_platillo) 
        REFERENCES Platillo(id_platillo) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FK_FacturaPlatillo_Factura FOREIGN KEY (FK_factura) 
        REFERENCES Factura(id_factura) 
        ON DELETE CASCADE ON UPDATE CASCADE
);
GO
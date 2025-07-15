--CREAR NOMBRE DE LA BASE DE DATOS
CREATE DATABASE site_web;
--Creando tabla user_data
CREATE TABLE users_data (
    idUser INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    direccion TEXT,
    sexo ENUM('M', 'F', 'Otro')
);
--Creando tabla user_login con llave foranea a users_data
CREATE TABLE users_login (
    idLogin INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idUser INT UNIQUE NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'user') NOT NULL,
    FOREIGN KEY (idUser) REFERENCES users_data(idUser) ON DELETE CASCADE
);
--Creando tabla de citras con llave foranea a users_data
CREATE TABLE citas (
    idCita INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    idUser INT NOT NULL,
    fecha_cita DATE NOT NULL,
    motivo_cita TEXT,
    FOREIGN KEY (idUser) REFERENCES users_data(idUser) ON DELETE CASCADE
);
--creando tabla de noticias con llave foranea a users_data
CREATE TABLE noticias (
    idNoticia INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo VARCHAR(200) UNIQUE NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    texto LONGTEXT NOT NULL,
    fecha DATE NOT NULL,
    idUser INT NOT NULL,
    FOREIGN KEY (idUser) REFERENCES users_data(idUser)
);
INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo) 
VALUES ('Admin', 'Principal', 'admin@sitio.com', '123456789', '1990-01-01', 'Dirección Admin', 'M');

INSERT INTO users_login (idUser, usuario, password, rol) 
VALUES (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
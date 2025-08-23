CREATE DATABASE IF NOT EXISTS PruebaTecDB;
USE PruebaTecDB;

CREATE TABLE Pais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    NombrePais VARCHAR(50) UNIQUE NOT NULL COLLATE latin1_swedish_ci,
    CodeAlf3 VARCHAR(3) UNIQUE,
    CodeAlf2 VARCHAR(2) UNIQUE,
    CodeNum INT(3) UNIQUE
);

CREATE TABLE Departamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    NombreDepartamento VARCHAR(50) NOT NULL COLLATE latin1_swedish_ci,
    PaisId INT NOT NULL,
    FOREIGN KEY (CountryId) REFERENCES Country(id),
    UNIQUE(NameCity, CountryId)
);

CREATE TABLE Usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(80) NOT NULL COLLATE latin1_swedish_ci,
    apellidos VARCHAR(80) NOT NULL COLLATE latin1_swedish_ci,
    direccion VARCHAR(225) NOT NULL COLLATE latin1_swedish_ci,
    telefono VARCHAR(225) NOT NULL COLLATE latin1_swedish_ci,
    PaisId INT NOT NULL,
    DepartamentoId INT NOT NULL,
    estadoCivil VARCHAR(100) NOT NULL COLLATE latin1_swedish_ci,
    foto VARCHAR(225) NOT NULL COLLATE latin1_swedish_ci,
    edad VARCHAR(3) NOT NULL COLLATE latin1_swedish_ci,
    descripcion TEXT NOT NULL COLLATE latin1_swedish_ci,
    AceptaTyC BOOLEAN NOT NULL,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (PaisId) REFERENCES Pais(id),
    FOREIGN KEY (DepartamentoId) REFERENCES Departamento(id)
);

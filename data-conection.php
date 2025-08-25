<?php
$host = 'localhost';
$username = 'root';
$password = 'root'; 
$database = 'formulario_db';

// Intentar conectar directamente a la base de datos
$connection = mysqli_connect($host, $username, $password, $database);

// Si la base no existe, crearla
if (!$connection) {
    $connection = mysqli_connect($host, $username, $password);
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    mysqli_query($connection, $sql);
    mysqli_select_db($connection, $database);

    // Crear tabla
    $sql = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombres VARCHAR(100) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        direccion VARCHAR(200) NOT NULL,
        telefono VARCHAR(20) NOT NULL,
        pais VARCHAR(100) NOT NULL,
        departamento VARCHAR(100) NOT NULL,
        estado_civil VARCHAR(50) NOT NULL,
        foto VARCHAR(200),
        edad VARCHAR(10) NOT NULL,
        descripcion TEXT NOT NULL,
        acepta_terminos TINYINT(1) DEFAULT 0 NOT NULL,
        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($connection, $sql);
}

// Configurar charset
mysqli_set_charset($connection, "utf8");
?>

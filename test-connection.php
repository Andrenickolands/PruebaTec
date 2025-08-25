<?php
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$database = 'formulario_db';

// Crear conexión
$connection = mysqli_connect($host, $username, $password);

// Verificar conexión
if (!$connection) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si la base de datos existe
$db_exists = mysqli_select_db($connection, $database);
if (!$db_exists) {
    // La base de datos no existe, vamos a crearla
    $sql = "CREATE DATABASE IF NOT EXISTS formulario_db";
    if (mysqli_query($connection, $sql)) {
        echo "Base de datos creada exitosamente o ya existía.<br>";
        
        // Seleccionar la base de datos
        mysqli_select_db($connection, $database);
        
        // Crear la tabla usuarios
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
        
        if (mysqli_query($connection, $sql)) {
            echo "Tabla 'usuarios' creada exitosamente o ya existía.<br>";
        } else {
            echo "Error al crear la tabla: " . mysqli_error($connection) . "<br>";
        }
    } else {
        echo "Error al crear la base de datos: " . mysqli_error($connection) . "<br>";
    }
} else {
    echo "La base de datos 'formulario_db' ya existe.<br>";
    
    // Verificar si la tabla usuarios existe
    $result = mysqli_query($connection, "SHOW TABLES LIKE 'usuarios'");
    if (mysqli_num_rows($result) == 0) {
        // La tabla no existe, vamos a crearla
        $sql = "CREATE TABLE usuarios ( 
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
        
        if (mysqli_query($connection, $sql)) {
            echo "Tabla 'usuarios' creada exitosamente.<br>";
        } else {
            echo "Error al crear la tabla: " . mysqli_error($connection) . "<br>";
        }
    } else {
        echo "La tabla 'usuarios' ya existe.<br>";
    }
}

echo "Conexión a la base de datos exitosa.<br>";
mysqli_close($connection);
?>
<a href="index.php">Volver al inicio</a>
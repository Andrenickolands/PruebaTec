<?php
session_start();
include_once './data-conection.php';

// Verificar si se han enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php?error=Método no permitido');
    exit();
}

// Obtener y limpiar datos del formulario
$nombres = trim($_POST['nombres']);
$apellidos = trim($_POST['apellidos']);
$direccion = trim($_POST['direccion']);
$telefono = trim($_POST['telefono']);
$pais = $_POST['pais'];
$departamento = $_POST['departamento'];
$edad = intval($_POST['edad']);
$estado_civil = $_POST['estado_civil'];
$descripcion = trim($_POST['descripcion']);
$acepta_terminos = isset($_POST['acepta_terminos']) ? 1 : 0;

// Validaciones básicas
if (empty($nombres) || empty($apellidos) || empty($direccion) || empty($telefono) || 
    empty($pais) || empty($departamento) || $edad < 15 || $edad > 30 || 
    empty($estado_civil) || empty($descripcion) || !$acepta_terminos) {
    header('Location: index.php?error=Todos los campos obligatorios deben ser completados');
    exit();
}

// Procesar archivo de foto
$foto_nombre = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $foto = $_FILES['foto'];
    
    // Validar tipo de archivo
    $tipos_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($foto['type'], $tipos_permitidos)) {
        header('Location: index.php?error=Tipo de archivo no válido para la foto');
        exit();
    }
    
    // Validar tamaño (2MB máximo)
    if ($foto['size'] > 2 * 1024 * 1024) {
        header('Location: index.php?error=El archivo de foto es demasiado grande (máx. 2MB)');
        exit();
    }
    
    // Crear directorio de uploads si no existe
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generar nombre único para el archivo
    $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
    $foto_nombre = uniqid('foto_') . '.' . $extension;
    $ruta_foto = $upload_dir . $foto_nombre;
    
    // Mover archivo
    if (!move_uploaded_file($foto['tmp_name'], $ruta_foto)) {
        header('Location: index.php?error=Error al subir la foto');
        exit();
    }
}

// Preparar consulta SQL
$query = "INSERT INTO usuarios (nombres, apellidos, direccion, telefono, pais, departamento, edad, estado_civil, foto, descripcion, acepta_terminos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssssssissi", $nombres, $apellidos, $direccion, $telefono, $pais, $departamento, $edad, $estado_civil, $foto_nombre, $descripcion, $acepta_terminos);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header('Location: index.php?success=registered');
        exit();
    } else {
        mysqli_stmt_close($stmt);
        // Si hay error y se subió foto, eliminarla
        if (!empty($foto_nombre) && file_exists($upload_dir . $foto_nombre)) {
            unlink($upload_dir . $foto_nombre);
        }
        header('Location: index.php?error=Error al guardar el registro: ' . mysqli_error($connection));
        exit();
    }
} else {
    header('Location: index.php?error=Error en la consulta: ' . mysqli_error($connection));
    exit();
}
?>
<?php
session_start();
include_once './data-conection.php';

// Verificar si se han enviado datos por POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php?error=Método no permitido');
    exit();
}

// Obtener y validar ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header('Location: index.php?error=ID de usuario no proporcionado');
    exit();
}

$id = intval($_POST['id']);

// Obtener y limpiar datos del formulario
$nombres = trim($_POST['nombres']);
$apellidos = trim($_POST['apellidos']);
$direccion = trim($_POST['direccion']);
$telefono = trim($_POST['telefono']);
$pais = $_POST['pais'];
$departamento = $_POST['departamento'];
$edad = intval($_POST['edad']);

// Validaciones básicas
if (empty($nombres) || empty($apellidos) || empty($direccion) || empty($telefono) || 
    empty($pais) || empty($departamento) || $edad < 15 || $edad > 30) {
    header('Location: index.php?error=Todos los campos son obligatorios');
    exit();
}

// Verificar que el usuario existe
$check_query = "SELECT id FROM usuarios WHERE id = ?";
$check_stmt = mysqli_prepare($connection, $check_query);
mysqli_stmt_bind_param($check_stmt, "i", $id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) === 0) {
    mysqli_stmt_close($check_stmt);
    header('Location: index.php?error=Usuario no encontrado');
    exit();
}
mysqli_stmt_close($check_stmt);

// Preparar consulta de actualización
$query = "UPDATE usuarios SET nombres = ?, apellidos = ?, direccion = ?, telefono = ?, pais = ?, departamento = ?, edad = ? WHERE id = ?";

$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssssii", $nombres, $apellidos, $direccion, $telefono, $pais, $departamento, $edad, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header('Location: index.php?success=updated');
        exit();
    } else {
        mysqli_stmt_close($stmt);
        header('Location: index.php?error=Error al actualizar el registro: ' . mysqli_error($connection));
        exit();
    }
} else {
    header('Location: index.php?error=Error en la consulta: ' . mysqli_error($connection));
    exit();
}
?>
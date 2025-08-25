<?php
session_start();
include_once './data-conection.php';

// Verificar si se ha proporcionado un ID válido
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?error=ID de usuario no válido');
    exit();
}

$id = intval($_GET['id']);

// Primero, obtener la información del usuario para eliminar la foto si existe
$select_query = "SELECT foto FROM usuarios WHERE id = ?";
$select_stmt = mysqli_prepare($connection, $select_query);
mysqli_stmt_bind_param($select_stmt, "i", $id);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);

if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($select_stmt);
    header('Location: index.php?error=Usuario no encontrado');
    exit();
}

$usuario = mysqli_fetch_assoc($result);
mysqli_stmt_close($select_stmt);

// Preparar consulta de eliminación
$delete_query = "DELETE FROM usuarios WHERE id = ?";
$delete_stmt = mysqli_prepare($connection, $delete_query);

if ($delete_stmt) {
    mysqli_stmt_bind_param($delete_stmt, "i", $id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        // Eliminar foto del servidor si existe
        if (!empty($usuario['foto']) && file_exists('uploads/' . $usuario['foto'])) {
            unlink('uploads/' . $usuario['foto']);
        }
        
        mysqli_stmt_close($delete_stmt);
        header('Location: index.php?success=deleted');
        exit();
    } else {
        mysqli_stmt_close($delete_stmt);
        header('Location: index.php?error=Error al eliminar el registro: ' . mysqli_error($connection));
        exit();
    }
} else {
    header('Location: index.php?error=Error en la consulta: ' . mysqli_error($connection));
    exit();
}
?>
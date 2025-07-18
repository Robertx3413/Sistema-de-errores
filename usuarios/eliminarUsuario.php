<?php
include '../connect.php';
session_start();

$usuario = $_SESSION['usuario'] ?? null;
if (isset($_GET['eliminarid'])) {
    $id = $_GET['eliminarid'];

$sql = "DELETE FROM usuario WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . mysqli_error($connect));
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: usuarios.php");
    exit();
} else {
    die('Error al eliminar el usuario: ' . mysqli_error($connect));
}
}
?>

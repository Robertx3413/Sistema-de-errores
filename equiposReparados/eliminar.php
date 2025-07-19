<?php
include '../connect.php';
session_start();
$usuario = $_SESSION['usuario'];
if (isset($_GET['eliminarid'])) {
    $id = $_GET['eliminarid'];

$sql = "SELECT * FROM equiposreparados WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);
$titulo = htmlspecialchars($row['titulo_error']);
$descripcion = htmlspecialchars($row['descripcion']);
$categoria = htmlspecialchars($row['categoria']);
$propietario = htmlspecialchars($row['propietario']);
$tecnico = htmlspecialchars($row['tecnico']);
$departamento = htmlspecialchars($row['departamento']);
$fecha = htmlspecialchars($row['fecha']);
$gravedad = htmlspecialchars($row['gravedad']);


  
  
      $sql2 = "DELETE FROM equiposreparados WHERE id = ?";
      $stmt3 = mysqli_prepare($connect, $sql2);
      mysqli_stmt_bind_param($stmt3, "i", $id);
      if (mysqli_stmt_execute($stmt3)) {
          header("Location: dashboard.php");
          exit();
      } else {
          die('Error al eliminar: ' . mysqli_error($connect));
      }

  
}
?>
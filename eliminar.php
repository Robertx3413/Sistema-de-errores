<?php
include 'connect.php';
session_start();
$usuario = $_SESSION['usuario'];
if (isset($_GET['eliminarid'])) {
    $id = $_GET['eliminarid'];

  $sql = "DELETE FROM registro WHERE id = $id";
  $result = mysqli_query($connect, $sql);

  if ($result) {
      // Redireccionar
      header("location: main.php");
  } else {
      die(mysqli_error($connect));
  }
}
?>
<?php
session_start();
include "../connect.php";

// Variables para mensajes de error
$error_usuario = '';
$error_pass = '';
$error_general = '';

// Procesar formulario cuando se envía
if (isset($_POST['registrar'])) {
    // Validar campos vacíos
    if (empty($_POST["usuario"])) {
        $error_usuario = 'Por favor ingrese su usuario';
    }
    
    if (empty($_POST["pass"])) {
        $error_pass = 'Por favor ingrese su contraseña';
    }
    
    // Si no hay errores de campos vacíos, validar credenciales
    if (empty($error_usuario) && empty($error_pass)) {
        $usuario = mysqli_real_escape_string($connect, trim($_POST["usuario"]));
        $pass = $_POST["pass"];
        
        $sql = "SELECT * FROM usuario WHERE usuario = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $consulta = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($consulta) > 0) {
            $fila = mysqli_fetch_assoc($consulta); 

            if (password_verify($pass, $fila['contraseña'])) {
                $_SESSION['usuario'] = $usuario;
                header("Location: ../main.php");
                exit(); 
            } else {
                $error_general = 'Usuario o contraseña incorrectos';
            }
        } else {
            $error_general = 'Usuario o contraseña incorrectos';
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>
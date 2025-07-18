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

            $estado = $fila['estado'];
            $intentos_fallidos = $fila['intentosfallidos'];

            if ($estado === "Inactivo") {
                $error_general = 'Este usuario está bloqueado, contacte con el administrador';
            } else {
                if (password_verify($pass, $fila['contraseña'])) {
                    $_SESSION['usuario'] = $usuario;

                    // Resetear intentos fallidos a 0
                    $update_query = "UPDATE usuario SET intentosfallidos = 0 WHERE usuario = ?";
                    $stmt_update = mysqli_prepare($connect, $update_query);
                    mysqli_stmt_bind_param($stmt_update, "s", $usuario);
                    mysqli_stmt_execute($stmt_update);
                    mysqli_stmt_close($stmt_update);

                    header("Location: ../main.php");
                    exit();
                } else {
                    $intentos_fallidos++;
                    if ($intentos_fallidos >= 3) {
                        // Bloquear usuario
                        $update_query = "UPDATE usuario SET estado = 'Inactivo', intentosfallidos = 0 WHERE usuario = ?";
                        $stmt_update = mysqli_prepare($connect, $update_query);
                        mysqli_stmt_bind_param($stmt_update, "s", $usuario);
                        mysqli_stmt_execute($stmt_update);
                        mysqli_stmt_close($stmt_update);

                        $error_general = 'Usuario bloqueado por demasiados intentos fallidos, contacte con el administrador';
                    } else {
                        // Actualizar intentos fallidos
                        $update_query = "UPDATE usuario SET intentosfallidos = ? WHERE usuario = ?";
                        $stmt_update = mysqli_prepare($connect, $update_query);
                        mysqli_stmt_bind_param($stmt_update, "is", $intentos_fallidos, $usuario);
                        mysqli_stmt_execute($stmt_update);
                        mysqli_stmt_close($stmt_update);

                        $error_general = 'Usuario o contraseña incorrectos';
                    }
                }
            }
        } else {
            $error_general = 'Usuario o contraseña incorrectos';
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

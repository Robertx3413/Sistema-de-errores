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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Moderno</title>
    <link rel="stylesheet" href="\sistema\styles.css">
</head>
<body>
    <div class="container">
    <form method="post" class="form-container">
        <h2>LOGIN</h2>
        
        <?php if(!empty($error_general)): ?>
            <div class="alert alert-danger"><?php echo $error_general; ?></div>
        <?php endif; ?>
        
        <div class="form-group">
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Ingrese su usuario" 
                   value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>"
                   class="<?php echo !empty($error_usuario) ? 'error' : ''; ?>">
            <?php if(!empty($error_usuario)): ?>
                <span class="error-message"><?php echo $error_usuario; ?></span>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="pass" placeholder="Ingrese su contraseña"
                   class="<?php echo !empty($error_pass) ? 'error' : ''; ?>">
            <?php if(!empty($error_pass)): ?>
                <span class="error-message"><?php echo $error_pass; ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" name="registrar" class="btn">Ingresar</button>
    </form>
    
        <!-- Footer con información de creadores -->
        
    </div>
</body>
</html>
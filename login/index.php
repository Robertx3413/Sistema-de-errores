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
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container-login">

        <div class="container-side shapedividers_com-3746">
            <div class="container-side-text">
                <h2> Sistema de Gestión de Fallas PC</h2>
                <div class="side-text">
                    <p>
                        Solución rápida y organizada para reportar y resolver problemas de computadoras.
                    </p>
                    <ul>
                        <li>✅ Registro sencillo de fallas de hardware y software.</li>
                        <li>✅ Seguimiento en tiempo real de solicitudes.</li>
                        <li>✅ Respuesta ágil con técnicos asignados..</li>
                    </ul>
                </div>
            </div>
        </div>

        
        <form method="post" class="form-container" id="form">
            
        <div class="form-header">
                <div class="logo-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#3f37c9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            <h2>Bienvenido</h2>
            <p>Inicia sesión para acceder al sistema.</p>
        </div>

        <div class="form">
                <?php if(!empty($error_general)): ?>
                    <div class=" alert alert-danger" id="error-general"><?php echo $error_general; ?></div>
                <?php endif; ?>
            

            <div class="error alert-danger" id="error-general"></div>

            <div class="form-group">
                <label>Nombre de Usuario</label>
                <input type="text" name="usuario" id="user" placeholder="Ingrese su usuario" 
                    value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>"
                    class="<?php echo !empty($error_usuario) ? 'error' : ''; ?>">
                <?php if(!empty($error_usuario)): ?>
                    <span class="error-message"><?php echo $error_usuario; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="pass" id="pass" placeholder="Ingrese su contraseña"
                    class="<?php echo !empty($error_pass) ? 'error' : ''; ?>">
                <?php if(!empty($error_pass)): ?>
                    <span class="error-message"><?php echo $error_pass; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group ">
                <span>¿No tienes una cuenta? <a class="link" href="registro.php">Regístrate Aquí</a></span>
            </div>

            <button type="submit" name="registrar" class="btn btn-primary ">Ingresar</button>
        </form>
        </div>
    
        <!-- Footer con información de creadores -->
        
    </div>
</body>

<script src="../validacion.js"></script>
</html>


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
        $error_usuario = 'Por favor ingrese un nombre de usuario';
    }
    
    if (empty($_POST["pass"])) {
        $error_pass = 'Por favor ingrese una contraseña';
    }
    
    // Si no hay errores de campos vacíos, validar que el usuario no exista
    if (empty($error_usuario) && empty($error_pass)) {
        $usuario = mysqli_real_escape_string($connect, trim($_POST["usuario"]));
        $pass = $_POST["pass"];
        
        $sql = "SELECT * FROM usuario WHERE usuario = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $consulta = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($consulta) > 0) {
            $error_general = 'El nombre de usuario ya existe. Por favor, elige otro.';
        } else {
            // Hash de la contraseña
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            
            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuario (usuario, contraseña) VALUES (?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $usuario, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                // Registro exitoso, redirigir al usuario a la página principal
                $_SESSION['usuario'] = $usuario;
                header("Location: ../main.php");
                exit();
            } else {
                $error_general = 'Error al registrar el usuario. Por favor, inténtalo de nuevo.';
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container-login">

        <div class="container-side shapedividers_com-3746">
            <div class="container-side-text">
                <h2>Crea una cuenta en nuestro Sistema</h2>
                <p>Regístrate para acceder a todas las funcionalidades.</p>
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
            <h2>Regístrate</h2>
            <p>Crea una cuenta para acceder al sistema.</p>
        </div>

        <div class="form">
                <?php if(!empty($error_general)): ?>
                    <div class="alert alert-danger"><?php echo $error_general; ?></div>
                <?php endif; ?>


                <div class="error alert-danger" id="error-general"></div>

            
            <div class="form-group">
                <label>Nombre de Usuario</label>
                <input type="text" name="usuario" id="user" placeholder="Ingrese un nombre de usuario" 
                    value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>"
                    class="<?php echo !empty($error_usuario) ? 'error' : ''; ?>">
                <?php if(!empty($error_usuario)): ?>
                    <span class="error-message"><?php echo $error_usuario; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="pass" id="pass" placeholder="Ingrese una contraseña"
                    class="<?php echo !empty($error_pass) ? 'error' : ''; ?>">
                <?php if(!empty($error_pass)): ?>
                    <span class="error-message"><?php echo $error_pass; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group ">
                <span>¿Ya tienes una cuenta? <a class="link" href="index.php">Inicia Sesión</a></span>
            </div>

            <button type="submit" name="registrar" class="btn btn-primary">Registrar</button>
        </form>
        </div>
    
        <!-- Footer con información de creadores -->
        
    </div>
</body>
<script>

    const user = document.getElementById('user');
    const pass = document.getElementById('pass');
    const form = document.querySelector('form');
    const errorGeneral = document.getElementById('error-general');

    form.addEventListener('submit', (e) => {
        let messages = [];

        if (user.value === '' || user.value == null) {
            messages.push('El usuario es requerido');
        } else if (!/^[a-zA-Z0-9]+$/.test(user.value)) {
            messages.push('El usuario solo puede contener letras y números');
        }

        if (pass.value === '' || pass.value == null) {
            messages.push('La contraseña es requerida');
        }
        else if (pass.value.length < 4) {
            messages.push('La contraseña debe tener al menos 4 caracteres');
        }
        
        if (messages.length > 0) {
            e.preventDefault();
            errorGeneral.innerText = messages[0]; // Muestra solo el primer mensaje de error
            errorGeneral.style.display = 'block'; // Asegura que el mensaje se muestre
        } else {
            errorGeneral.style.display = 'none'; // Oculta el mensaje si no hay errores
        }
    });


</script>
</html>
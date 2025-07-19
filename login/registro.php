<?php
session_start();
include "../connect.php";

// Variables para mensajes de error
$error_usuario = '';
$error_pass = '';
$error_preguntaseguridad = '';
$error_respuesta = '';
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

    if (empty($_POST["preguntaseguridad"])) {
        $error_preguntaseguridad = 'Por favor ingrese una pregunta de seguridad';
    }

    if (empty($_POST["respuesta"])) {
        $error_respuesta = 'Por favor ingrese una respuesta';
    }
    
    // Si no hay errores de campos vacíos, validar que el usuario no exista
    if (empty($error_usuario) && empty($error_pass) && empty($error_preguntaseguridad) && empty($error_respuesta)) {
        $usuario = mysqli_real_escape_string($connect, trim($_POST["usuario"]));
        $pass = $_POST["pass"];
        $preguntaseguridad = mysqli_real_escape_string($connect, trim($_POST["preguntaseguridad"]));
        $respuesta = $_POST["respuesta"];
        
        $sql = "SELECT * FROM usuario WHERE usuario = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $consulta = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($consulta) > 0) {
            $error_general = 'El nombre de usuario ya existe. Por favor, elige otro.';
        } else {
            // Hash de la contraseña y respuesta
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $hashed_respuesta = password_hash($respuesta, PASSWORD_DEFAULT);
            
            // Insertar el nuevo usuario en la base de datos
            $rol = 2;
            $intentosfallidos= 0;
            $estado= "Activo";
            $sql = "INSERT INTO usuario (usuario, contraseña, preguntaseguridad, respuesta,intentosfallidos, estado, idrol) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssi", $usuario, $hashed_password, $preguntaseguridad, $hashed_respuesta,$intentosfallidos, $estado, $rol);
            
            if (mysqli_stmt_execute($stmt)) {
                // Registro exitoso, redirigir al usuario a la página principal
                $_SESSION['usuario'] = $usuario;
                header("Location: ../login/index.php");
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

        
        <form method="post" class="form-container" id="form" style="height: 100%;">
            
        <div class="form-header">
                <div class="logo-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#3f37c9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            <h2>Bienvenido</h2>
            <p>Registrate para acceder al sistema.</p>
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

            <div class="form-group">
                <label>Pregunta de Seguridad</label>
                <input type="text" name="preguntaseguridad" id="preguntaseguridad" placeholder="Ingrese una pregunta de seguridad"
                    value="<?php echo isset($_POST['preguntaseguridad']) ? htmlspecialchars($_POST['preguntaseguridad']) : ''; ?>"
                    class="<?php echo !empty($error_preguntaseguridad) ? 'error' : ''; ?>">
                <?php if(!empty($error_preguntaseguridad)): ?>
                    <span class="error-message"><?php echo $error_preguntaseguridad; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Respuesta</label>
                <input type="password" name="respuesta" id="respuesta" placeholder="Ingrese una respuesta"
                    class="<?php echo !empty($error_respuesta) ? 'error' : ''; ?>">
                <?php if(!empty($error_respuesta)): ?>
                    <span class="error-message"><?php echo $error_respuesta; ?></span>
                <?php endif; ?>
            </div>

            

            <button type="submit" name="registrar" class="btn btn-primary">Registrar</button>
              <div class="form-group ">
                <span>¿Ya tienes una cuenta? <a class="link" href="index.php">Inicia Sesión</a></span>
            </div>
        </div>
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

        const preguntaseguridad = document.getElementById('preguntaseguridad');
        if (preguntaseguridad.value === '' || preguntaseguridad.value == null) {
            messages.push('La pregunta de seguridad es requerida');
        }

        const respuesta = document.getElementById('respuesta');
        if (respuesta.value === '' || respuesta.value == null) {
            messages.push('La respuesta es requerida');
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
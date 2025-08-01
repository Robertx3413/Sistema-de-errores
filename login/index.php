<?php
session_start();
include "../connect.php";

// Variables para mensajes de error
$error_general = '';

// Procesar formulario cuando se envía
if (isset($_POST['registrar'])) {
    // Validar campos vacíos
    if (empty($_POST["usuario"])) {
        $error_general = 'Por favor ingrese su usuario';
    }
    
    if (empty($_POST["pass"])) {
        $error_general = 'Por favor ingrese su contraseña';
    }
    
    // Si no hay errores de campos vacíos, validar credenciales
    if (empty($error_general)) {
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

                        // Mensaje específico para el segundo intento fallido
                        if ($intentos_fallidos == 2) {
                            $error_general = 'Contraseña incorrecta, en el próximo intento el usuario será bloqueado';
                        } else {
                            $error_general = 'Contraseña incorrecta';
                        }
                    }
                }
            }
        } else {
            $error_general = 'Usuario no existe';
        }
        
        mysqli_stmt_close($stmt);
    }

   
    if (!empty($error_general)) {
        echo "<script>
            setTimeout(() => {
                const errorGeneral = document.getElementById('error-general');
                if (errorGeneral) {
                    errorGeneral.style.display = 'none';
                }
            }, 4000);
        </script>";
    }
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión de Fallas de PC</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container-login">

        <div class="container-side shapedividers_com-3746">
            <div class="container-side-text">
                <h2> Sistema de Gestión de Fallas de PC</h2>
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
                    <div class="error alert alert-danger" id="error-general"><?php echo $error_general; ?></div>
                <?php endif; ?>
            

            <div class="error alert-danger" id="error-general"></div>

            <div class="form-group">
                <label>Nombre de Usuario</label>
                <input type="text" name="usuario" id="user" placeholder="Ingrese su usuario" 
                    value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>"
                    class="form-input">
            </div>
            
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="pass" id="pass" placeholder="Ingrese su contraseña"
                    class="form-input">

            </div>

            <div class="form-group ">
                <span>¿No tienes una cuenta? <a class="link" href="registro.php">Regístrate Aquí</a></span>
            </div>
            
            <div class="form-group ">
                <span>¿Olvidaste la contraseña? <a class="link" href="recuperarClave.php">Ingresa Aquí</a></span>
            </div>

            <button type="submit" name="registrar" class="btn btn-primary ">Ingresar</button>
        </form>
        </div>
        
    </div>
</body>

<script>
    

    const user = document.getElementById('user');
    const pass = document.getElementById('pass');
    const form = document.querySelector('form');
    const errorGeneral = document.getElementById('error-general');

    form.addEventListener('submit', (e) => {
        let messages = [];

        if (!/^[a-zA-Z0-9]{4,16}$/.test(user.value.trim())) {
            messages.push('El usuario debe tener entre 4 y 16 caracteres y solo puede contener letras y números.');
        }
        if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/.test(pass.value)) {
            messages.push('La contraseña debe tener al menos 6 caracteres, contener al menos una letra y un número.');
        }

        if (messages.length > 0) {
            e.preventDefault();
            errorGeneral.innerText = messages[0];
            errorGeneral.style.display = 'block';


            setTimeout(() => {
                errorGeneral.style.display = 'none';
            }, 4000);
        } else {
            errorGeneral.style.display = 'none';
        }
    });


    
</script>
</html>


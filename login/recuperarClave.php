<?php
session_start();
include "../connect.php";

$error_usuario = '';
$error_pass = '';
$error_general = '';
$show_form = 'username'; // 'username' or 'reset'
$security_question = '';
$security_answer = '';
$usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_username'])) {
        // User submitted username to get security question and answer
        if (empty($_POST['usuario'])) {
            $error_usuario = 'Por favor ingrese un nombre de usuario';
        } else {
            $usuario = mysqli_real_escape_string($connect, trim($_POST['usuario']));
            $sql = "SELECT * FROM usuario WHERE usuario = ?";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "s", $usuario);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                // Assuming the security question is already decrypted in the database or fetched decrypted
                $security_question = $row['preguntaseguridad'];
                $security_answer = $row['respuesta'];
                $idrol = $row['idrol'];
                $show_form = 'reset';
            } else {
                $error_usuario = 'Usuario no encontrado';
            }
            mysqli_stmt_close($stmt);
        }
    } elseif (isset($_POST['submit_newpass'])) {
        // User submitted new password to update
        if (empty($_POST['usuario']) || empty($_POST['newpass']) || empty($_POST['respuesta'])) {
            if (empty($_POST['usuario'])) {
                $error_usuario = 'Usuario no especificado';
            }
            if (empty($_POST['newpass'])) {
                $error_pass = 'Por favor ingrese una nueva contraseña';
            }
            if (empty($_POST['respuesta'])) {
                $error_general = 'Por favor ingrese la respuesta a la pregunta de seguridad';
            }
            $show_form = 'reset';
            $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
            $security_question = isset($_POST['security_question']) ? $_POST['security_question'] : '';
            $security_answer = isset($_POST['security_answer']) ? $_POST['security_answer'] : '';
        } else {
            $usuario = mysqli_real_escape_string($connect, trim($_POST['usuario']));
            $newpass = $_POST['newpass'];
            $respuesta_usuario = $_POST['respuesta'];
            $security_answer = isset($_POST['security_answer']) ? $_POST['security_answer'] : '';
            $idrol = isset($_POST['idrol']) ? $_POST['idrol'] : null;
            if (strlen($newpass) < 4) {
                $error_pass = 'La contraseña debe tener al menos 4 caracteres';
                $show_form = 'reset';
                $security_question = isset($_POST['security_question']) ? $_POST['security_question'] : '';
            } elseif (!password_verify($respuesta_usuario, $security_answer)) {
                $error_general = 'Respuesta incorrecta. No se puede actualizar la contraseña.';
                $show_form = 'reset';
                $security_question = isset($_POST['security_question']) ? $_POST['security_question'] : '';
            } else {
                $hashed_password = password_hash($newpass, PASSWORD_DEFAULT);
                if ($idrol == 1){

                $estado = "Activo";
                $sql = "UPDATE usuario SET contraseña = ?, estado = ? WHERE usuario = ?";
                $stmt = mysqli_prepare($connect, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $hashed_password,$estado, $usuario);
                }else{
                $sql = "UPDATE usuario SET contraseña = ? WHERE usuario = ?";
                $stmt = mysqli_prepare($connect, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $usuario);
                }
                if (mysqli_stmt_execute($stmt)) {
                    $success_message = 'Contraseña actualizada correctamente. Puede iniciar sesión con su nueva contraseña.';
                    $show_form = 'success';
                } else {
                    $error_general = 'Error al actualizar la contraseña. Inténtelo de nuevo.';
                    $show_form = 'reset';
                    $security_question = isset($_POST['security_question']) ? $_POST['security_question'] : '';
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recuperar Contraseña</title>
        <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container-login">
        <div class="container-side shapedividers_com-3746">
            <div class="container-side-text">
                <h2>Sistema de Gestión de Fallas PC</h2>
                <div class="side-text">
                    <p>
                        Solución rápida y organizada para reportar y resolver problemas de computadoras.
                    </p>
                    <ul>
                        <li>✅ Registro sencillo de fallas de hardware y software.</li>
                        <li>✅ Seguimiento en tiempo real de solicitudes.</li>
                        <li>✅ Respuesta ágil con técnicos asignados.</li>
                    </ul>
                </div>
            </div>
        </div>

        <?php if ($show_form === 'username'): ?>
            <form method="post" class="form-container" id="formUser" style="height: 100vh;">
                <div class="form-header">
                    <div class="logo-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#3f37c9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h3>Verificación de Usuario</h3>
                    <p>Agrega tu nombre de usuario.</p>
                </div>
                <div class="form">

                    <?php if (!empty($error_usuario)): ?>
                        <div class="alert alert-danger"><?php echo $error_usuario; ?></div>
                    <?php endif; ?>


                                <div class="error alert-danger" id="error-general"></div>


                    <div class="form-group">
                        <label>Nombre de Usuario</label>
                        <input type="text" name="usuario" id="user" placeholder="Ingrese su nombre de usuario"
                            value="<?php echo htmlspecialchars($usuario); ?>" />
                    </div>

                    <div class="form-group ">
                        <span>¿No tienes una cuenta? <a class="link" href="registro.php">Regístrate Aquí</a></span>
                    </div>

                    <div class="form-group">
                        <span>¿Ya tienes una cuenta? <a class="link" href="index.php">Inicia Sesión</a></span>
                    </div>

                    <button type="submit" name="submit_username" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        <?php elseif ($show_form === 'reset'): ?>
            <form method="post" class="form-container" id="form">
                <div class="form-header">
                    <div class="logo-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="#3f37c9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h3>Respuesta de Seguridad</h3>
                    <p>Responde las preguntas de seguridad.</p>
                </div>
                <div class="form">
                    <div class="error alert-danger" id="error-general"></div>
                    <?php if (!empty($error_general)): ?>
                        <div class="alert alert-danger"><?php echo $error_general; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error_pass)): ?>
                        <div class="alert alert-danger"><?php echo $error_pass; ?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label><b>Pregunta de Seguridad del usuario</b></label>
                        <label class="form-control"><?php echo htmlspecialchars(strtoupper($security_question)); ?></label>
                    </div>
                    <div class="form-group">
                        <label>Respuesta</label>
                        <input type="password" name="respuesta" id="p1" placeholder="Ingrese la respuesta a la pregunta" />
                    </div>
                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" name="newpass" id="pass" placeholder="Ingrese nueva contraseña" />
                    </div>
                    <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>" />
                    <input type="hidden" name="security_question" value="<?php echo htmlspecialchars($security_question); ?>" />
                    <input type="hidden" name="security_answer" value="<?php echo htmlspecialchars($security_answer); ?>" />
                    <input type="hidden" name="idrol" value="<?php echo htmlspecialchars($idrol); ?>" />
                    <div class="form-group">
                        <span>¿Ya tienes una cuenta? <a class="link" href="index.php">Inicia Sesión</a></span>
                    </div>
                    <button type="submit" name="submit_newpass" class="btn btn-primary">Actualizar Contraseña</button>
                </div>
            </form>
        <?php elseif ($show_form === 'success'): ?>
            <script>
                alert("<?php echo $success_message; ?>");
                window.location.href = "index.php";
            </script>
        <?php endif; ?>
    </div>


    <script>
    const form = document.getElementById('form');
    const formUser = document.getElementById('formUser');
    const user = document.getElementById('user');
    const p1 = document.getElementById('p1');
    const pass = document.getElementById('pass');
    const errorGeneral = document.getElementById('error-general');

    if (form) {
        form.addEventListener('submit', (e) => {
            let messages = [];


            if (p1 && !/^[a-zA-Z0-9]{4,16}$/.test(p1.value.trim())) {
                messages.push('La respuesta debe tener entre 4 y 16 caracteres y solo puede contener letras y números.');
            }
            if (pass && !/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/.test(pass.value)) {
                messages.push('La contraseña debe tener al menos 6 caracteres, contener al menos una letra y un número.');
            }

            if (messages.length > 0) {
                e.preventDefault();
                if (errorGeneral) {
                    errorGeneral.innerText = messages[0];
                    errorGeneral.style.display = 'block';

                    setTimeout(() => {
                        errorGeneral.style.display = 'none';
                    }, 3000);
                }
            } else {
                if (errorGeneral) {
                    errorGeneral.style.display = 'none';
                }
            }
        });
    }
    if (formUser) {
        formUser.addEventListener('submit', (e) => {
            let messages = [];


                        if (user && !/^[a-zA-Z0-9]{4,16}$/.test(user.value.trim())) {
                messages.push('El usuario debe tener entre 4 y 16 caracteres y solo puede contener letras y números.');
            }
            if (messages.length > 0) {
                e.preventDefault();
                if (errorGeneral) {
                    errorGeneral.innerText = messages[0];
                    errorGeneral.style.display = 'block';

                    setTimeout(() => {
                        errorGeneral.style.display = 'none';
                    }, 3000);
                }
            } else {
                if (errorGeneral) {
                    errorGeneral.style.display = 'none';
                }
            }
        });
    }
    </script>
</body>
</html>

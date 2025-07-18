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
    <link rel="stylesheet" href="../styles.css" />
</head>
<body>
    <div class="container-login">
        <div class="container-side shapedividers_com-3746">
            <div class="container-side-text">
                <h2>Recuperar Contraseña</h2>
                <p>Ingrese su nombre de usuario para recuperar su contraseña.</p>
            </div>
        </div>

        <?php if ($show_form === 'username'): ?>
            <form method="post" class="form-container" id="form-username">
                <div class="form-header">
                    <h2>Ingrese Nombre de Usuario</h2>
                </div>
                <div class="form">
                    <?php if (!empty($error_usuario)): ?>
                        <div class="alert alert-danger"><?php echo $error_usuario; ?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Nombre de Usuario</label>
                        <input type="text" name="usuario" id="usuario" placeholder="Ingrese su nombre de usuario" 
                            value="<?php echo htmlspecialchars($usuario); ?>" />
                    </div>
                    <button type="submit" name="submit_username" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        <?php elseif ($show_form === 'reset'): ?>
            <form method="post" class="form-container" id="form-reset">
                <div class="form-header">
                    <h2>Responder Pregunta de Seguridad</h2>
                </div>
                <div class="form">
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
                         <input type="password" name="respuesta" id="respuesta" placeholder="Ingrese la respuesta a la pregunta" />
                    </div>
                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" name="newpass" id="newpass" placeholder="Ingrese nueva contraseña" />
                    </div>
            <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>" />
            <input type="hidden" name="security_question" value="<?php echo htmlspecialchars($security_question); ?>" />
            <input type="hidden" name="security_answer" value="<?php echo htmlspecialchars($security_answer); ?>" />
            <input type="hidden" name="idrol" value="<?php echo htmlspecialchars($idrol); ?>" />
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
</body>
</html>

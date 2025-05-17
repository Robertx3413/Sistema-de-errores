<?php
include "connect.php";
session_start();

// Verificación de sesión mejorada
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header('Location: login/index.php');
    exit();
}

// Validación y seguridad para el ID
$id = filter_input(INPUT_GET, 'editarid', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: main.php');
    exit();
}

// Consulta preparada para obtener datos
$sql = "SELECT * FROM registro WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header('Location: main.php');
    exit();
}

$row = mysqli_fetch_assoc($result);
$titulo_error = htmlspecialchars($row['titulo_error']);
$descripcion = htmlspecialchars($row['descripcion']);
$categoria = htmlspecialchars($row['categoria']);

// Procesar actualización
if (isset($_POST['registrar'])) {
    $falla1 = mysqli_real_escape_string($connect, $_POST['errorTitle']);
    $falla2 = mysqli_real_escape_string($connect, $_POST['errorDescription']);
    $falla3 = mysqli_real_escape_string($connect, $_POST['errorCategory']);

    $update_sql = "UPDATE registro SET titulo_error=?, descripcion=?, categoria=? WHERE id=?";
    $update_stmt = mysqli_prepare($connect, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "sssi", $falla1, $falla2, $falla3, $id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        header('Location: main.php');
        exit;
    } else {
        $error_message = "Error al actualizar: " . mysqli_error($connect);
    }
    
    mysqli_stmt_close($update_stmt);
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro de Error</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="\sistema\styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="header">
                <h1>
                    <i class="fas fa-edit"></i> Editar Registro
                    <span class="badge">ID: <?= $id ?></span>
                </h1>
            </div>

            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="errorTitle">Título del Error *</label>
                    <input type="text" id="errorTitle" name="errorTitle" 
                           value="<?= $titulo_error ?>" 
                           placeholder="Ej: Pantalla azul al iniciar Windows" required>
                </div>
                
                <div class="form-group">
                    <label for="errorDescription">Descripción Detallada *</label>
                    <textarea id="errorDescription" name="errorDescription" 
                              placeholder="Describa el error con el mayor detalle posible..." 
                              required><?= $descripcion ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="errorCategory">Categoría *</label>
                    <select id="errorCategory" name="errorCategory" required>
                        <option value="" disabled>Seleccione una categoría</option>
                        <option value="Hardware" <?= $categoria == 'Hardware' ? 'selected' : '' ?>>Hardware</option>
                        <option value="Software" <?= $categoria == 'Software' ? 'selected' : '' ?>>Software</option>
                        <option value="Hardware y Software" <?= $categoria == 'Hardware y Software' ? 'selected' : '' ?>>Hardware y Software</option>
                        <option value="Red" <?= $categoria == 'Red' ? 'selected' : '' ?>>Red</option>
                        <option value="Sistema Operativo" <?= $categoria == 'Sistema Operativo' ? 'selected' : '' ?>>Sistema Operativo</option>
                        <option value="Otro" <?= $categoria == 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
                
                <div class="btn-group">
                    <button type="submit" name="registrar" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="main.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
        
    </div>

    <script>
        // Validación del lado del cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            const title = document.getElementById('errorTitle').value.trim();
            const description = document.getElementById('errorDescription').value.trim();
            const category = document.getElementById('errorCategory').value;
            
            if (!title || !description || !category) {
                e.preventDefault();
                alert('Por favor complete todos los campos obligatorios (*)');
                return false;
            }
            
            if (description.length < 20) {
                e.preventDefault();
                alert('La descripción debe tener al menos 20 caracteres');
                return false;
            }
        });
    </script>
</body>
</html>
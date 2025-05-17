<?php
include "connect.php";
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header('Location: login/index.php');
    exit();
}

// Procesar el formulario
if(isset($_POST['registrar'])) {
    $titulo = mysqli_real_escape_string($connect, $_POST['errorTitle']);
    $descripcion = mysqli_real_escape_string($connect, $_POST['errorDescription']);
    $categoria = mysqli_real_escape_string($connect, $_POST['errorCategory']);

    $sql = "INSERT INTO registro (titulo_error, descripcion, categoria, fecha) VALUES (?, ?, ?, CURRENT_TIMESTAMP())";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $titulo, $descripcion, $categoria);
    
    if(mysqli_stmt_execute($stmt)) {
        header('Location: main.php');
        exit;
    } else {
        $error_message = "Error al registrar: " . mysqli_error($connect);
    }
    
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Errores de PC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="\sistema\styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="header">
                <h1><i class="fas fa-bug"></i> Registrar Nuevo Error</h1>
                <p>Complete el formulario para reportar un problema</p>
            </div>

            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="#" method="POST">
                <div class="form-group">
                    <label for="errorTitle">Título del Error *</label>
                    <input type="text" id="errorTitle" name="errorTitle" placeholder="Ej: Pantalla azul al iniciar Windows" required>
                </div>
                
                <div class="form-group">
                    <label for="errorDescription">Descripción Detallada *</label>
                    <textarea id="errorDescription" name="errorDescription" placeholder="Describa el error con el mayor detalle posible, incluyendo pasos para reproducirlo..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="errorCategory">Categoría *</label>
                    <select id="errorCategory" name="errorCategory" required>
                        <option value="" disabled selected>Seleccione una categoría</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Hardware y Software">Hardware y Software</option>
                        <option value="Red">Red</option>
                        <option value="Sistema Operativo">Sistema Operativo</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                
                <button type="submit" name="registrar" class="btn">
                    <i class="fas fa-save"></i> Guardar Registro
                </button>
            </form>
            
            <a href="main.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
        
    </div>

    <script>
        // Validación básica del lado del cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            const title = document.getElementById('errorTitle').value.trim();
            const description = document.getElementById('errorDescription').value.trim();
            const category = document.getElementById('errorCategory').value;
            
            if(!title || !description || !category) {
                e.preventDefault();
                alert('Por favor complete todos los campos obligatorios (*)');
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
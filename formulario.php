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
    $propietario = mysqli_real_escape_string($connect, $_POST['Owner']);
    $categoria = mysqli_real_escape_string($connect, $_POST['errorCategory']);
    $tecnico = mysqli_real_escape_string($connect, $_POST['Technical']);
    $departamento = mysqli_real_escape_string($connect, $_POST['Department']);
    $registroLugar = mysqli_real_escape_string($connect, $_POST['registro']); 
    $severidad = mysqli_real_escape_string($connect, $_POST['severidad']);
    $fecha = mysqli_real_escape_string($connect, $_POST['date']); 

    

    

    $sql = "INSERT INTO registro (titulo_error, descripcion, propietario, categoria, tecnico, departamento,gravedad, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ? )";
    $stmt = mysqli_prepare($connect, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", $titulo, $descripcion,$propietario, $categoria, $tecnico, $departamento, $severidad, $fecha);
    
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        
            <header class="header">
                <h1><i class="fas fa-table"></i> Registros de Errores</h1>
                <div class="btn-group">
                <a href="main.php" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Volver</a>
                </div>

                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item"><a href="#">Inicio</a></li>
                        <li class="nav-item"><a href="#">Dashboard</a></li>
                    </ul>
                </nav>
        </header>

            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
            <form action="#" method="POST" class="form">
                <div class="form-group">
                    <label for="errorTitle" class="form-label">Título del Error *</label>
                    <input type="text" id="errorTitle" name="errorTitle" class="form-input" placeholder="Ej: Pantalla azul al iniciar Windows">
                </div>
                
                <div class="form-group">
                    <label for="errorCategory" class="form-label">Categoría *</label>
                    <select id="errorCategory" name="errorCategory" class="form-select">
                        <option value="" disabled selected>Seleccione una categoría</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Hardware y Software">Hardware y Software</option>
                        <option value="Red">Red</option>
                        <option value="Sistema Operativo">Sistema Operativo</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label for="errorDescription" class="form-label">Descripción Detallada *</label>
                    <textarea id="errorDescription" name="errorDescription" class="form-textarea" placeholder="Describa el error con el mayor detalle posible, incluyendo pasos para reproducirlo..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="Owner" class="form-label">Nombre del Usuario del Equipo</label>
                    <input type="text" id="Owner" name="Owner" class="form-input" placeholder="Nombre del usuario">
                </div>

                <div class="form-group">
                    <label for="Technical" class="form-label">Técnico a Cargo de la Reparación</label>
                    <input type="text" id="Technical" name="Technical" class="form-input" placeholder="Nombre del técnico" value="<?php echo isset($usuario) ? htmlspecialchars($usuario) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="Department" class="form-label">Departamento</label>
                    <input type="text" id="Department" name="Department" class="form-input" placeholder="Departamento">
                </div>

                <div class="form-group">
                    <label for="date" class="form-label">Fecha de Reparación</label>
                    <input type="date" id="date" name="date" class="form-input">
                </div>

                <div class="form-group">
                    <label for="lugarRegistro" class="form-label">Lugar de Registro</label>
                    <input type="text" id="registro" name="registro" class="form-input" placeholder="Lugar donde se realiza el registro">
                </div>

                <div class="form-group">
                    <label for="severidad" class="form-label">Gravedad del problema *</label>
                    <select id="severidad" name="severidad" class="form-select">
                        <option value="" disabled selected>Seleccione la gravedad</option>
                        <option value="baja">Baja (molestia menor)</option>
                        <option value="media">Media (afecta uso normal)</option>
                        <option value="alta">Alta (inutiliza el equipo)</option>
                    </select>
                </div>
        
                <button type="submit" name="registrar" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Registro
                </button>
            </form>
        </div>
                <footer class="footer">
            <p>&copy; 2025 Registro de Errores. Todos los derechos reservados.</p>
        </footer>
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
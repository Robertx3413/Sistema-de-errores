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
$propietario = htmlspecialchars($row['propietario']);
$departamento = htmlspecialchars($row['departamento']);
$fecha = htmlspecialchars($row['fecha']);
$registroLugar = htmlspecialchars($row['registroLugar']);
$gravedad = htmlspecialchars($row['gravedad']);

// Procesar actualización
if (isset($_POST['registrar'])) {
    $errorTitle = mysqli_real_escape_string($connect, $_POST['errorTitle']);
    $errorDescription = mysqli_real_escape_string($connect, $_POST['errorDescription']);
    $errorCategory = mysqli_real_escape_string($connect, $_POST['errorCategory']);
    $UsuarioEquipo = mysqli_real_escape_string($connect, $_POST['UsuarioEquipo']);
    $departamento = mysqli_real_escape_string($connect, $_POST['departamento']);
    $tecnicoReparacion = mysqli_real_escape_string($connect, $_POST['tecnicoReparacion']);
    $fechaReparacion = mysqli_real_escape_string($connect, $_POST['fechaReparacion']);
    $lugarRegistro = mysqli_real_escape_string($connect, $_POST['lugarRegistro']);
    $severidad = mysqli_real_escape_string($connect, $_POST['severidad']);

    $update_sql = "UPDATE registro SET titulo_error=?, descripcion=?, categoria=?, propietario=?, departamento=?, fecha=?, registroLugar=?, gravedad=? WHERE id=?";
    $update_stmt = mysqli_prepare($connect, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "ssssssssi", $errorTitle, $errorDescription, $errorCategory, $UsuarioEquipo, $departamento, $fechaReparacion, $lugarRegistro, $severidad, $id);
    
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
    <link rel="stylesheet" href="styles.css">
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
                
                <div class="form-group">
                    <label for="errorDescription">Descripción Detallada *</label>
                    <textarea id="errorDescription" name="errorDescription" 
                              placeholder="Describa el error con el mayor detalle posible..." 
                              required><?= $descripcion ?></textarea>
                </div>
                
               

                 <div class="form-group">
                    <label for="UsuarioEquipo" class="form-label">Nombre del Usuario del Equipo</label>
                    <input type="text" id="UsuarioEquipo" name="UsuarioEquipo" class="form-input" placeholder="Nombre del usuario" value="<?php echo isset($propietario) ? htmlspecialchars($propietario) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="tecnicoReparacion" class="form-label">Técnico a Cargo de la Reparación</label>
                    <input type="text" id="tecnicoReparacion" name="tecnicoReparacion" class="form-input" placeholder="Nombre del técnico" value="<?php echo isset($usuario) ? htmlspecialchars($usuario) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="departamento" class="form-label">Departamento</label>
                    <input type="text" id="departamento" name="departamento" class="form-input" placeholder="Departamento" value="<?php echo isset($departamento) ? htmlspecialchars($departamento) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="fechaReparacion" class="form-label">Fecha de Reparación</label>
                    <input type="date" id="fechaReparacion" name="fechaReparacion" class="form-input" value="<?php echo isset($fecha) ? htmlspecialchars($fecha) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="lugarRegistro" class="form-label">Lugar de Registro</label>
                    <input type="text" id="lugarRegistro" name="lugarRegistro" class="form-input" placeholder="Lugar donde se realiza el registro" value="<?php echo isset($registroLugar) ? htmlspecialchars($registroLugar) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="severidad" class="form-label">Gravedad del problema *</label>
                    <select id="severidad" name="severidad" class="form-select">
                        <option value="" disabled selected>Seleccione la gravedad</option>
                        <option value="baja"<?= $gravedad == 'baja' ? 'selected' : '' ?>>Baja (molestia menor)</option>
                        <option value="media" <?= $gravedad == 'media' ? 'selected' : '' ?>>Media (afecta uso normal)</option>
                        <option value="alta" <?= $gravedad == 'alta' ? 'selected' : '' ?>>Alta (inutiliza el equipo)</option>
                    </select>
                </div>
                
                <div class="btn-group">
                    <button type="button"  class="btn btn-primary btn-modal">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <a href="main.php" class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
                <!-- modal editar -->
                <div class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="btn-close"><i class="fas fa-times"></i></span>
                            <h4>Confirmar Edición</h4>
                        </div>
                        <div class="modal-txt">
                            <p>¿Estás seguro de que deseas editar este registro?</p>
                        </div>
                        <div class="modal-actions">
                            <button type="submit" name="registrar" class="btn btn-danger">Guardar Cambios   </button>
                            <button type="button" class="btn-off btn btn-warning">Cancelar</button>
                        </div>
                    </div>
                </div>


            </form>
        </div>
        
    </div>

    <script>
    const btnsModal = document.querySelectorAll(".btn-modal");
    const modals = document.querySelectorAll(".modal");
    const btnsClose = document.querySelectorAll(".btn-close");
    const btnsOff = document.querySelectorAll(".btn-off");

    btnsModal.forEach((btn, index) => {
        btn.addEventListener("click", function() {
            modals[index].classList.add("show");
        });
    });

    btnsClose.forEach((btn, index) => {
        btn.addEventListener("click", function() {
            modals[index].classList.remove("show");
        });
    });

    btnsOff.forEach((btn, index) => {
        btn.addEventListener("click", function() {
            modals[index].classList.remove("show");
        });
    });



    document.addEventListener('click', function(event) {
        modals.forEach((modal) => {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        });
    });

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


                                        
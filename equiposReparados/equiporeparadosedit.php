<?php
include "../connect.php";
session_start();

// Verificación de sesión mejorada
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header('Location: ../login/index.php');
    exit();
}

// Validación y seguridad para el ID
$id = filter_input(INPUT_GET, 'editarid', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: ../main.php');
    exit();
}

$sql = "SELECT * FROM usuario WHERE usuario = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$rol = $row['idrol'];

// Consulta preparada para obtener datos
$sql = "SELECT * FROM equiposreparados WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header('Location: ../main.php');
    exit();
}

$row = mysqli_fetch_assoc($result);
$titulo_error = htmlspecialchars($row['titulo_error']);
$descripcion = htmlspecialchars($row['descripcion']);
$categoria = htmlspecialchars($row['categoria']);
$propietario = htmlspecialchars($row['propietario']);
$departamento = htmlspecialchars($row['departamento']);
$tecnico = htmlspecialchars($row['tecnico']);
$fecha = htmlspecialchars($row['fecha']);
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
    $severidad = mysqli_real_escape_string($connect, $_POST['severidad']);

    $update_sql = "UPDATE equiposreparados SET titulo_error=?, descripcion=?, categoria=?, propietario=?, departamento=?, fecha=?, gravedad=? WHERE id=?";
    $update_stmt = mysqli_prepare($connect, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "sssssssi", $errorTitle, $errorDescription, $errorCategory, $UsuarioEquipo, $departamento, $fechaReparacion, $severidad, $id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        header('Location: dashboard.php');
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
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
         <header class="header">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/>
                    </svg> Registro de Errores
                </h1>
                <div class="btn-group">

                    <a href="dashboard.php" class="btn btn-secondary">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-svg" aria-hidden="true" focusable="false">
                            <g>
                                <path d="M17 16L13 12L17 8M11 16L7 12L11 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg> Volver
                    </a>
                   
                    
                </div>

                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="../main.php">
                                <!-- Improved Home SVG: clearer, more modern, accessible -->

                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-nav" aria-hidden="true" focusable="false">
                                        <path d="M5.77778 10.2222V18C5.77778 19.1046 6.67321 20 7.77778 20H12M5.77778 10.2222L11.2929 4.70711C11.6834 4.31658 12.3166 4.31658 12.7071 4.70711L17.5 9.5M5.77778 10.2222L4 12M18.2222 10.2222V18C18.2222 19.1046 17.3268 20 16.2222 20H12M18.2222 10.2222L20 12M18.2222 10.2222L17.5 9.5M17.5 9.5V6M12 20V15" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                            
                                    <span class="txt-nav">Inicio</span>
                           
                            </a>
                        </li>
                    <?php
                    if ($rol === 1){
                        echo ' <li class="nav-item">
                            <a href="dashboard.php">
                                <!-- Improved Dashboard SVG: simple, bold, accessible -->
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" class="icon-nav" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                    <path d="M13 12C13 11.4477 13.4477 11 14 11H19C19.5523 11 20 11.4477 20 12V19C20 19.5523 19.5523 20 19 20H14C13.4477 20 13 19.5523 13 19V12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M4 5C4 4.44772 4.44772 4 5 4H9C9.55228 4 10 4.44772 10 5V12C10 12.5523 9.55228 13 9 13H5C4.44772 13 4 12.5523 4 12V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M4 17C4 16.4477 4.44772 16 5 16H9C9.55228 16 10 16.4477 10 17V19C10 19.5523 9.55228 20 9 20H5C4.44772 20 4 19.5523 4 19V17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M13 5C13 4.44772 13.4477 4 14 4H19C19.5523 4 20 4.44772 20 5V7C20 7.55228 19.5523 8 19 8H14C13.4477 8 13 7.55228 13 7V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                                <span>Equipos reparados</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../usuarios/usuarios.php">
                                <!-- Improved Dashboard SVG: simple, bold, accessible -->
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" class="icon-nav" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                    <path d="M13 12C13 11.4477 13.4477 11 14 11H19C19.5523 11 20 11.4477 20 12V19C20 19.5523 19.5523 20 19 20H14C13.4477 20 13 19.5523 13 19V12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M4 5C4 4.44772 4.44772 4 5 4H9C9.55228 4 10 4.44772 10 5V12C10 12.5523 9.55228 13 9 13H5C4.44772 13 4 12.5523 4 12V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M4 17C4 16.4477 4.44772 16 5 16H9C9.55228 16 10 16.4477 10 17V19C10 19.5523 9.55228 20 9 20H5C4.44772 20 4 19.5523 4 19V17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                    <path d="M13 5C13 4.44772 13.4477 4 14 4H19C19.5523 4 20 4.44772 20 5V7C20 7.55228 19.5523 8 19 8H14C13.4477 8 13 7.55228 13 7V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                </svg>
                                <span>Usuarios</span>
                            </a>
                        </li>
                    </ul>';
                    }  
                    ?>
                    <div class="container-off">
                            <a href="../login/cerrar_sesion.php" class="btn btn-danger ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                </svg> 
                                Salir
                            </a>
                        </div>
                </nav>
            </header>
            
        <div class="secondary-form-container">
            <div class="edit-group">
                <h1 class="edit-group-title">
                    <i class="fas fa-edit"></i> Editar Registro
                    <span class="badge">ID: <?= $id ?></span>
                </h1>
            </div>

            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="form " id="form">
                <div class="form-group">
                    <label for="errorTitle" class="form-label">Título del Error *</label>
                    <input type="text" id="errorTitle" name="errorTitle" 
                           value="<?= $titulo_error ?>" 
                           placeholder="Ej: Pantalla azul al iniciar Windows" >
                </div>


                                <div class="error alert-danger" id="error-general"></div>


                 <div class="form-group">
                    <label for="errorCategory">Categoría *</label>
                    <select id="errorCategory" name="errorCategory" >
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
                              ><?= $descripcion ?></textarea>
                </div>
                
               

                 <div class="form-group">
                    <label for="UsuarioEquipo" class="form-label">Nombre del Usuario del Equipo</label>
                    <input type="text" id="UsuarioEquipo" name="UsuarioEquipo" class="form-input" placeholder="Nombre del usuario" value="<?php echo isset($propietario) ? htmlspecialchars($propietario) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="tecnicoReparacion" class="form-label">Técnico a Cargo de la Reparación</label>
                    <input type="text" id="tecnicoReparacion" name="tecnicoReparacion" class="form-input" placeholder="Nombre del técnico" value="<?php echo isset($tecnico) ? htmlspecialchars($tecnico) : ''; ?>">
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
                    <a href="dashboard.php" class="btn btn-outline">
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
    document.addEventListener("DOMContentLoaded", function() {
        const btnModal = document.querySelector(".btn-modal");
        const modal = document.querySelector(".modal");
        const btnClose = document.querySelector(".btn-close");
        const btnOff = document.querySelector(".btn-off");
    const form = document.querySelector('form');
    const errorTitle = document.getElementById('errorTitle');
    const errorCategory = document.getElementById('errorCategory');
    const errorDescription = document.getElementById('errorDescription');
    const owner = document.getElementById('UsuarioEquipo');
    const technical = document.getElementById('tecnicoReparacion');
    const department = document.getElementById('departamento');
    const date = document.getElementById('fechaReparacion');
    const severidad = document.getElementById('severidad');
    const errorGeneral = document.getElementById('error-general');


        // Modal logic
        btnModal.addEventListener("click", function() {
            modal.classList.add("show");
        });
        btnClose.addEventListener("click", function() {
            modal.classList.remove("show");
        });
        btnOff.addEventListener("click", function() {
            modal.classList.remove("show");
        });
        document.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        });

        // Validation
        form.addEventListener('submit', (e) => {
        let messages = [];

        // Regex patterns
        const titlePattern = /^[\w\sáéíóúÁÉÍÓÚüÜñÑ.,:;()\-]{3,50}$/i;
        const ownerPattern = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]{3,25}$/;
        const technicalPattern = /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]{3,25}$/;
        const departmentPattern = /^.{3,25}$/;

        if (errorTitle.value.trim() === '') {
            messages.push('El título del error es requerido');
        } else if (!titlePattern.test(errorTitle.value.trim())) {
            messages.push('El título debe tener entre 3 y 25 caracteres y solo caracteres válidos');
        }

        if (!errorCategory.value) {
            messages.push('La categoría es requerida');
        }

        if (errorDescription.value.trim() === '') {
            messages.push('La descripción es requerida');
        }

        if (owner.value.trim() === '') {
            messages.push('El nombre del usuario del equipo es requerido');
        } else if (!ownerPattern.test(owner.value.trim())) {
            messages.push('El nombre del usuario debe tener entre 3 y 25 letras');
        }

        if (technical.value.trim() === '') {
            messages.push('El técnico a cargo es requerido');
        } else if (!technicalPattern.test(technical.value.trim())) {
            messages.push('El nombre del técnico debe tener entre 3 y 25 letras');
        }

        if (department.value.trim() === '') {
            messages.push('El campo de equipos es requerido');
        } else if (!departmentPattern.test(department.value.trim())) {
            messages.push('El campo de equipos debe tener entre 3 y 25 caracteres');
        }

        if (!date.value) {
            messages.push('La fecha de reparación es requerida');
        }

        if (!severidad.value) {
            messages.push('La gravedad del problema es requerida');
        }

        if (messages.length > 0) {
            e.preventDefault();
            errorGeneral.innerText = messages[0]; // Muestra solo el primer mensaje de error
            errorGeneral.style.display = 'block';
        } else {
            errorGeneral.style.display = 'none';
        }
        })
    });
    </script>
</body>
</html>


                                        
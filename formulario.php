<?php
include "connect.php";
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header('Location: login/index.php');
    exit();
}



$sql = "SELECT * FROM usuario WHERE usuario = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$rol = $row['idrol'];


// Procesar el formulario
if(isset($_POST['registrar'])) {
    $titulo = mysqli_real_escape_string($connect, $_POST['errorTitle']);
    $descripcion = mysqli_real_escape_string($connect, $_POST['errorDescription']);
    $propietario = mysqli_real_escape_string($connect, $_POST['Owner']);
    $categoria = mysqli_real_escape_string($connect, $_POST['errorCategory']);
    $tecnico = mysqli_real_escape_string($connect, $_POST['Technical']);
    $departamento = mysqli_real_escape_string($connect, $_POST['Department']);
    $severidad = mysqli_real_escape_string($connect, $_POST['severidad']);
    $fecha = mysqli_real_escape_string($connect, $_POST['date']); 

    

    

    $sql = "INSERT INTO registro (titulo_error, descripcion, propietario, categoria, tecnico, departamento, gravedad, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ? )";
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
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/>
                    </svg> Registro de Errores
                </h1>
                <div class="btn-group">

                    <a href="http://localhost/dashboard/sistema/main.php" class="btn btn-secondary">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-svg" aria-hidden="true" focusable="false">
                            <g>
                                <path d="M17 16L13 12L17 8M11 16L7 12L11 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg> Volver
                    </a>


                    <a href="formulario.php" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg> Agregar
                    </a>

                   
                    
                </div>

                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="main.php">
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
                            <a href="equiposReparados\dashboard.php">
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
                            <a href="usuarios/usuarios.php">
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
                            <a href="login/cerrar_sesion.php" class="btn btn-danger ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                </svg> 
                                Salir
                            </a>
                        </div>
                </nav>
            </header>

            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="secondary-form-container ">
            <form action="#" method="POST" class="form ">
                
                <div class="form-group">
                    <label for="errorTitle" class="form-label">Título del Error *</label>
                    <input type="text" id="errorTitle" name="errorTitle" class="form-input" placeholder="Ej: Pantalla azul al iniciar Windows">
                </div>  

                    <div class="error alert-danger" id="error-general"></div>
                
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
                    <label for="Owner" class="form-label">Nombre del Usuario del Equipo *</label>
                    <input type="text" id="Owner" name="Owner" class="form-input" placeholder="Nombre del usuario">
                </div>

                <div class="form-group">
                    <label for="Technical" class="form-label">Técnico a Cargo de la Reparación *</label>
                    <input type="text" id="Technical" name="Technical" class="form-input" placeholder="Nombre del técnico" value="<?php echo isset($usuario) ? htmlspecialchars($usuario) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="Department" class="form-label">Equipos *</label>
                    <input type="text" id="Department" name="Department" class="form-input" placeholder="Equipos">
                </div>

                <div class="form-group">
                    <label for="date" class="form-label">Fecha de Reparación *</label>
                    <input type="date" id="date" name="date" class="form-input">
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
        
    const form = document.querySelector('form');
    const errorTitle = document.getElementById('errorTitle');
    const errorCategory = document.getElementById('errorCategory');
    const errorDescription = document.getElementById('errorDescription');
    const owner = document.getElementById('Owner');
    const technical = document.getElementById('Technical');
    const department = document.getElementById('Department');
    const date = document.getElementById('date');
    const severidad = document.getElementById('severidad');
    const errorGeneral = document.getElementById('error-general');

    form.addEventListener('submit', (e) => {
        let messages = [];

        // Regex patterns
        const titlePattern = /^[\w\sáéíóúÁÉÍÓÚüÜñÑ.,:;()\-]{3,25}$/i;
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
    });
    </script>
</body>
</html>
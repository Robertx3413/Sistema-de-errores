<?php
include "../connect.php";
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Registros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"/>
                    </svg> Usuarios
                </h1>

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
                            <a href="../equiposReparados/dashboard.php">
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
                            <a href="usuarios.php">
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

        <div class="table-container">
            <?php
            $sql = "SELECT * FROM `usuario` Where idrol = 2 ";
            $result = mysqli_query($connect, $sql);
           
            if (mysqli_num_rows($result) > 0): ?>
            <table>
            <thead>
            <tr>
            <th>Nombre de Usuario</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
            <td><?= htmlspecialchars($row['usuario']) ?></td>
            <td>
            <div class="action-buttons">

                <a href="editarUsuario.php?editarid=<?= $row['id'] ?>" class="btn btn-warning action-btn">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" class="icon-edit" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3.99997H6C4.89543 3.99997 4 4.8954 4 5.99997V18C4 19.1045 4.89543 20 6 20H18C19.1046 20 20 19.1045 20 18V12M18.4142 8.41417L19.5 7.32842C20.281 6.54737 20.281 5.28104 19.5 4.5C18.7189 3.71895 17.4526 3.71895 16.6715 4.50001L15.5858 5.58575M18.4142 8.41417L12.3779 14.4505C12.0987 14.7297 11.7431 14.9201 11.356 14.9975L8.41422 15.5858L9.00257 12.6441C9.08001 12.2569 9.27032 11.9013 9.54951 11.6221L15.5858 5.58575M18.4142 8.41417L15.5858 5.58575" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                     Editar
                </a>

                <button type="button" class="btn btn-danger action-btn btn-modal">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" class="icon-delete" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M6 7V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                     Eliminar
                </button>

                </div>
                </td>
                </tr>


                <!-- modal eliminar -->

                    <div class="modal">
                    <div class="modal-content">
                    <div class="modal-header">
                        <span class="btn-close">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                        </svg>
                        </span>
                        <h4>Confirmar Eliminación</h4>
                    </div>
                    <div class="modal-txt">
                        
                        <p>¿Estás seguro de que deseas eliminar este registro?</p>
                    </div>
                    <div class="modal-actions">
                        <a href="eliminarUsuario.php?eliminarid=<?= $row['id'] ?>" class="btn btn-danger">Eliminar</a href="">
                        <button type="button" class="btn-off btn btn-warning">Cancelar</button>
                    </div>
                    </div>
                    </div>

                

             



            <?php endwhile; ?>
            </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
            <i class="fas fa-database"></i>
            <h3>No hay registros encontrados</h3>
            <p>Agrega un nuevo registro haciendo clic en el botón "Agregar"</p>
            </div>
            <?php endif; ?>
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

    </script>

</body>
</html>
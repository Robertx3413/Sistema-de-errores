<?php
include "connect.php";
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header('Location: login/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Registros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1><i class="fas fa-table"></i> Registros de Errores</h1>
            <div class="btn-group">
                <a href="formulario.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar
                </a>
                <a href="login/cerrar_sesion.php" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>

            <nav class="nav">
                <ul class="nav-list">
                    <li class="nav-item"><a href="main.php">Inicio</a></li>
                    <li class="nav-item"><a href="dashboard.php">Dashboard</a></li>

                </ul>
            </nav>
        </header>

        <div class="table-container">
            <?php
            $sql = "SELECT * FROM `registro` ORDER BY `fecha` DESC";
            $result = mysqli_query($connect, $sql);
            
            if (mysqli_num_rows($result) > 0): ?>
            <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['titulo_error']) ?></td>
                <td>
                <span class="badge badge-primary">
                <?= htmlspecialchars($row['categoria']) ?>
                </span>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
                <td>
                <div class="action-buttons">

                <button type="button" class="btn btn-secondary action-btn btn-modal">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15v-2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5V17a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1zm-1-8a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                    </svg> Info
                </button>

                <a href="prueba.php?id=<?= $row['id'] ?>" class="btn btn-success action-btn">
                    <svg class="icon"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18 3H6c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM6 5h12v8H6V5zm0 14v-5h12v5H6z"/>
                    </svg> Imprimir
                </a>

                <a href="editar.php?editarid=<?= $row['id'] ?>" class="btn btn-warning action-btn">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg> Editar
                </a>

                <button type="button" class="btn btn-danger action-btn btn-modal">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                    </svg> Eliminar
                </button>
                </div>
                </td>
                </tr>


            <!-- modal informacion -->
           
<div class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Información del Registro</h4>
            <span class="btn-close">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                </svg>
            </span>
        </div>
        <div class="modal-txt">
            <!-- Aquí puedes poner la información que deseas mostrar -->
            <p><strong>Propietario del equipo:</strong> <?php echo $row['propietario']?></p>
            <p><strong>Descripción del problema:</strong> <?php echo $row['descripcion']?></p>
            <p><strong>Gravedad:</strong> <?php echo $row['gravedad']?></p>
            <p><strong>Técnico encargado:</strong> <?php echo $row['tecnico']?></p>
            <p><strong>Departamento encargado:</strong> <?php echo $row['departamento']?></p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-off btn btn-warning">Cerrar</button>
        </div>
    </div>
</div>

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
                        <a href="eliminar.php?eliminarid=<?= $row['id'] ?>" class="btn btn-danger">Eliminar</a href="">
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
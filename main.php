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
        <div class="header">
            <h1><i class="fas fa-table"></i> Registros de Errores</h1>
            <div class="btn-group">
                <a href="formulario.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar
                </a>
                <a href="login/cerrar_sesion.php" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>

        <div class="table-container">
            <?php
            $sql = "SELECT `id`, `titulo_error`, `descripcion`, `categoria`, `fecha` FROM `registro` ORDER BY `fecha` DESC";
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
                                        <a href="prueba.php?id=<?= $row['id'] ?>" class="btn btn-success action-btn">
                                            <i class="fas fa-print"></i> Imprimir
                                        </a>
                                        <a href="editar.php?editarid=<?= $row['id'] ?>" class="btn btn-warning action-btn">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <button type="button" class="btn btn-danger action-btn btn-modal">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>


                            <!-- modal eliminar -->

                                                <div class="modal">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <span class="btn-close"><i class="fas fa-times"></i></span>
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
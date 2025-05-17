<?php
// Iniciar el buffer de salida
ob_start();

// Incluir conexión y verificar ID
include 'connect.php';

// Validar y sanitizar el ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID no válido');
}

// Configurar zona horaria
date_default_timezone_set('America/Lima');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Fallas Técnicas</title>
    <style>
        /* Estilos base para el PDF */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            padding: 20px;
        }
        
        /* Encabezado del reporte */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4361ee;
            padding-bottom: 10px;
        }
        
        .header h1 {
            color: #4361ee;
            font-size: 18px;
            margin: 0;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        
        /* Estilo para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            page-break-inside: avoid;
        }
        
        th {
            background-color: #4361ee;
            color: white;
            text-align: left;
            padding: 8px;
            font-size: 11px;
        }
        
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        /* Pie de página */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        /* Estilos para datos importantes */
        .highlight {
            font-weight: bold;
            color: #f72585;
        }
    </style>
</head>
<body>
    <!-- Encabezado del reporte -->
    <div class="header">
        <h1>REPORTE DE FALLAS TÉCNICAS</h1>
        <div class="subtitle">Sistema de Registro de Incidencias</div>
    </div>
    
    <!-- Información del reporte -->
    <div style="margin-bottom: 15px;">
        <div><strong>Fecha de generación:</strong> <?= date('d/m/Y H:i:s') ?></div>
        <div><strong>ID de registro:</strong> <?= $id ?></div>
    </div>

    <!-- Tabla de fallas -->
    <table>
        <thead>
            <tr>
                <th>N° Registro</th>
                <th>Falla 1</th>
                <th>Falla 2</th>
                <th>Falla 3</th>
                <th>Falla 4</th>
                <th>Falla 5</th>
                <th>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta preparada para seguridad
            $sql = "SELECT `id`, `falla1`, `falla2`, `falla3`, `falla4`, `falla5`, `fecha` 
                    FROM `registro` WHERE id = ?";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // Formatear fecha
                $fecha = date('d/m/Y H:i', strtotime($row['fecha']));
                
                echo '<tr>
                    <td class="highlight">' . htmlspecialchars($row['id']) . '</td>
                    <td>' . nl2br(htmlspecialchars($row['falla1'])) . '</td>
                    <td>' . nl2br(htmlspecialchars($row['falla2'])) . '</td>
                    <td>' . nl2br(htmlspecialchars($row['falla3'])) . '</td>
                    <td>' . nl2br(htmlspecialchars($row['falla4'])) . '</td>
                    <td>' . nl2br(htmlspecialchars($row['falla5'])) . '</td>
                    <td>' . $fecha . '</td>
                </tr>';
            } else {
                echo '<tr><td colspan="7" style="text-align: center;">No se encontraron registros</td></tr>';
            }
            
            mysqli_stmt_close($stmt);
            ?>
        </tbody>
    </table>
    
    <!-- Pie de página -->
    <div class="footer">
        Reporte generado automáticamente por el Sistema de Registro de Fallas | 
        <?= date('Y') ?> Todos los derechos reservados
    </div>
</body>
</html>

<?php
// Obtener el contenido HTML
$html = ob_get_clean();

// Cargar DomPDF
require_once 'C:\xampp\htdocs\dashboard\Registro de fallos en ordenadores\pdf\dompdf\autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Configurar opciones
$options = new Options();
$options->set([
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,
    'defaultFont' => 'Helvetica',
    'isPhpEnabled' => true
]);

// Crear instancia de Dompdf
$dompdf = new Dompdf($options);

// Cargar HTML
$dompdf->loadHtml($html, 'UTF-8');

// Configurar papel y orientación
$dompdf->setPaper('A4', 'portrait');

// Renderizar PDF
$dompdf->render();

// Generar nombre de archivo
$filename = 'Reporte_Falla_' . $id . '_' . date('Ymd_His') . '.pdf';

// Enviar el PDF al navegador
$dompdf->stream($filename, [
    'Attachment' => false, // Abrir en navegador (true para descarga)
    'compress' => true
]);

exit;
?>
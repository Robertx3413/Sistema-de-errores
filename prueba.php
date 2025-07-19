<?php
// Iniciar buffer de salida
ob_start();

// Incluir conexión y validar ID
include '../connect.php';

// Validar y sanitizar el ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('ID de registro no válido');
}

// Configurar zona horaria
date_default_timezone_set('America/Lima');

// Consulta preparada para seguridad
$sql = "SELECT * FROM `equiposreparados` WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die('No se encontró el registro solicitado');
}

$row = mysqli_fetch_assoc($result);
$id = htmlspecialchars($row['id']);
$titulo = htmlspecialchars($row['titulo_error']);
$descripcion = nl2br(htmlspecialchars($row['descripcion']));
$propietario = htmlspecialchars($row['propietario']);
$categoria = htmlspecialchars($row['categoria']);
$tecnico = htmlspecialchars($row['tecnico']);
$departamento = htmlspecialchars($row['departamento']);
$gravedad =  htmlspecialchars($row['gravedad']);
$fecha = date('d/m/Y H:i', strtotime($row['fecha']));

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Técnico #<?= $id ?></title>
    <style>
        /* Estilos base para PDF */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            padding: 0;
            margin: 0;
        }
        
        /* Contenedor principal */
        .reporte-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        
        /* Encabezado */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4361ee;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #4361ee;
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        
        /* Información del reporte */
        .reporte-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .info-item {
            margin: 5px 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #4361ee;
        }
        
        /* Secciones de contenido */
        .seccion {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .seccion-titulo {
            color: #4361ee;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .seccion-contenido {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            min-height: 50px;
        }
        
        /* Pie de página */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        /* Estilos para datos importantes */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            background-color: #e3f2fd;
            color: #4361ee;
        }
    </style>
</head>
<body>
    <div class="reporte-container">
        <!-- Encabezado del reporte -->
        <div class="header">
            <h1>REPORTE TÉCNICO DE FALLA</h1>
            <div class="subtitle">Sistema de Gestión de Incidencias</div>
        </div>
        
        <!-- Información básica -->
        <div class="reporte-info">
            <div>
                <div class="info-item"><span class="info-label">N° de Registro:</span> <?= $id ?></div>
                <div class="info-item"><span class="info-label">Propietario:</span> <?= $propietario ?></div>
                <div class="info-item"><span class="info-label">Tecnico encargado:</span> <?= $tecnico ?></div>
                <div class="info-item"><span class="info-label">Departamento:</span> <?= $departamento ?></div>
                <div class="info-item"><span class="info-label">Fecha:</span> <?= $fecha ?></div>
            </div>
            <div>
                <div class="info-item"><span class="info-label">Categoría:</span> <span class="badge"><?= $categoria ?></span></div>
                <div class="info-item"><span class="info-label">Prioridad:</span> <span class="badge"><?= $gravedad?></span></div>
            </div>
        </div>
        
        <!-- Sección de título -->
        <div class="seccion">
            <div class="seccion-titulo">Título de la Falla</div>
            <div class="seccion-contenido"><?= $titulo ?></div>
        </div>
        
        <!-- Sección de descripción -->
        <div class="seccion">
            <div class="seccion-titulo">Descripción Detallada</div>
            <div class="seccion-contenido"><?= $descripcion ?></div>
        </div>
        
        <!-- Sección de diagnóstico -->
        <div class="seccion">
            <div class="seccion-titulo">Diagnóstico Técnico</div>
            <div class="seccion-contenido">
                <p>El problema ha sido identificado como una falla de tipo <strong><?= $categoria ?></strong>.</p>
                <p>Se recomienda realizar las siguientes acciones:</p>
                <ul>
                    <li>Verificación del sistema afectado</li>
                    <li>Análisis de posibles causas raíz</li>
                    <li>Implementación de solución temporal</li>
                </ul>
            </div>
        </div>
        
        <!-- Pie de página -->
        <div class="footer">
            Reporte generado automáticamente el <?= date('d/m/Y H:i:s') ?> | 
            © <?= date('Y') ?> Departamento de Soporte Técnico
        </div>
    </div>
</body>
</html>

<?php
// Obtener el contenido HTML
$html = ob_get_clean();

// Cargar DomPDF
require_once '../pdf\dompdf\autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Configurar opciones
$options = new Options();
$options->set([
    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,
    'defaultFont' => 'Helvetica',
    'isPhpEnabled' => true,
    'defaultPaperSize' => 'A4',
    'dpi' => 150
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
$filename = 'Reporte_Tecnico_' . $id . '_' . date('Ymd_His') . '.pdf';

// Enviar el PDF al navegador
$dompdf->stream($filename, [
    'Attachment' => false, // Abrir en navegador
    'compress' => true,
    'isRemoteEnabled' => true
]);

exit;
?>
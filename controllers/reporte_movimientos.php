<?php
session_start();
require_once "../system/config.php";
require_once "../fpdf/fpdf.php"; // Ajusta la ruta a tu instalación de FPDF

// Recibir filtros del formulario
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
$tipo_movimiento = $_POST['tipo_movimiento'] ?? null;
$id_usuario = $_POST['id_usuario'] ?? null;

// Construir consulta con filtros dinámicos
$sql = "SELECT m.id_movimiento, m.id_producto, u.nombre AS usuario, m.tipo_movimiento, m.cantidad, m.fecha_movimiento
        FROM movimiento m
        LEFT JOIN usuarios u ON m.id_usuario = u.id_usuario
        WHERE 1=1";

if ($fecha_inicio && $fecha_fin) {
    $sql .= " AND DATE(m.fecha_movimiento) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}
if ($tipo_movimiento) {
    $sql .= " AND m.tipo_movimiento = '$tipo_movimiento'";
}
if ($id_usuario) {
    $sql .= " AND m.id_usuario = '$id_usuario'";
}

$sql .= " ORDER BY m.fecha_movimiento DESC";

$result = mysqli_query($conn, $sql);

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Encabezado
$pdf->Cell(0, 10, "Reporte de Movimientos", 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, "Generado el: " . date("d/m/Y H:i:s"), 0, 1, 'R');
$pdf->Ln(5);

// Mostrar filtros aplicados
$pdf->SetFont('Arial', 'I', 10);
if ($fecha_inicio && $fecha_fin) {
    $pdf->Cell(0, 8, "Periodo: $fecha_inicio a $fecha_fin", 0, 1);
}
if ($tipo_movimiento) {
    $pdf->Cell(0, 8, "Tipo: $tipo_movimiento", 0, 1);
}
if ($id_usuario) {
    $pdf->Cell(0, 8, "Usuario: $id_usuario", 0, 1);
}
$pdf->Ln(3);

// Cabecera de tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 8, 'ID', 1);
$pdf->Cell(30, 8, 'Producto', 1);
$pdf->Cell(40, 8, 'Usuario', 1);
$pdf->Cell(30, 8, 'Tipo', 1);
$pdf->Cell(25, 8, 'Cantidad', 1);
$pdf->Cell(45, 8, 'Fecha', 1);
$pdf->Ln();

// Contenido de la tabla
$pdf->SetFont('Arial', '', 9);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20, 8, $row['id_movimiento'], 1);
        $pdf->Cell(30, 8, $row['id_producto'], 1);
        $pdf->Cell(40, 8, utf8_decode($row['usuario']), 1);
        $pdf->Cell(30, 8, ucfirst($row['tipo_movimiento']), 1);
        $pdf->Cell(25, 8, $row['cantidad'], 1);
        $pdf->Cell(45, 8, $row['fecha_movimiento'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(190, 8, "No se encontraron movimientos con los filtros seleccionados.", 1, 1, 'C');
}

// Salida del PDF
$pdf->Output("I", "Reporte_Movimientos.pdf");
exit;

<?php
session_start();
require_once "../system/config.php";
require_once "../fpdf/fpdf.php"; // Ajusta la ruta a tu instalación

// Filtros recibidos
$id_categoria = $_POST['id_categoria'] ?? null;
$id_proveedor = $_POST['id_proveedor'] ?? null;
$stock = $_POST['stock'] ?? null;

// Consulta dinámica
$sql = "SELECT p.id_producto, p.nombre, p.codigo, p.precio_unitario, p.stock_actual, p.stock_minimo,
        c.nombre AS categoria, pr.nombre AS proveedor
        FROM producto p
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
        LEFT JOIN proveedor pr ON p.id_proveedor = pr.id_proveedor
        WHERE 1=1";

if ($id_categoria) {
    $sql .= " AND p.id_categoria = '$id_categoria'";
}
if ($id_proveedor) {
    $sql .= " AND p.id_proveedor = '$id_proveedor'";
}
if ($stock === "bajo") {
    $sql .= " AND p.stock_actual <= p.stock_minimo";
}

$sql .= " ORDER BY p.nombre ASC";
$result = mysqli_query($conn, $sql);

// Crear PDF
$pdf = new FPDF("L","mm","A4"); // Horizontal
$pdf->AddPage();
$pdf->SetFont("Arial","B",14);

// Encabezado
$pdf->Cell(0,10,"Reporte de Inventario",0,1,"C");
$pdf->SetFont("Arial","",10);
$pdf->Cell(0,8,"Generado el: ".date("d/m/Y H:i:s"),0,1,"R");
$pdf->Ln(5);

// Filtros aplicados
$pdf->SetFont("Arial","I",10);
if ($id_categoria) $pdf->Cell(0,8,"Categoria ID: $id_categoria",0,1);
if ($id_proveedor) $pdf->Cell(0,8,"Proveedor ID: $id_proveedor",0,1);
if ($stock === "bajo") $pdf->Cell(0,8,"Filtro: Solo productos con bajo stock",0,1);
$pdf->Ln(3);

// Cabecera de tabla
$pdf->SetFont("Arial","B",10);
$pdf->Cell(20,8,"ID",1);
$pdf->Cell(50,8,"Nombre",1);
$pdf->Cell(30,8,"Codigo",1);
$pdf->Cell(40,8,"Categoria",1);
$pdf->Cell(40,8,"Proveedor",1);
$pdf->Cell(30,8,"Precio",1);
$pdf->Cell(25,8,"Stock",1);
$pdf->Cell(25,8,"Minimo",1);
$pdf->Ln();

// Datos
$pdf->SetFont("Arial","",9);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(20,8,$row['id_producto'],1);
        $pdf->Cell(50,8,utf8_decode($row['nombre']),1);
        $pdf->Cell(30,8,$row['codigo'],1);
        $pdf->Cell(40,8,utf8_decode($row['categoria']),1);
        $pdf->Cell(40,8,utf8_decode($row['proveedor']),1);
        $pdf->Cell(30,8,"$".$row['precio_unitario'],1);
        $pdf->Cell(25,8,$row['stock_actual'],1);
        $pdf->Cell(25,8,$row['stock_minimo'],1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(260,8,"No se encontraron productos con los filtros seleccionados.",1,1,"C");
}

// Salida
$pdf->Output("I","Reporte_Inventario.pdf");
exit;

<?php
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Egreso.php';

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$ventaModel = new Venta();
$egresoModel = new Egreso();

$efectivo = $ventaModel->totalPorMedioPago('efectivo', $fecha);
$transferencia = $ventaModel->totalPorMedioPago('transferencia', $fecha);
$egresos = $egresoModel->totalEgresosPorFecha($fecha);
$saldo = $efectivo + $transferencia - $egresos;

// Cabeceras para descargar como Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cuadre_caja_" . $fecha . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Contenido de la tabla
echo "<table border='1'>";
echo "<tr><th colspan='2'><strong>Cuadre de Caja - {$fecha}</strong></th></tr>";
echo "<tr><th>Concepto</th><th>Monto (USD)</th></tr>";
echo "<tr><td>Ingresos en efectivo</td><td>" . number_format($efectivo, 2) . "</td></tr>";
echo "<tr><td>Ingresos por transferencia</td><td>" . number_format($transferencia, 2) . "</td></tr>";
echo "<tr><td>Total egresos</td><td>" . number_format($egresos, 2) . "</td></tr>";
echo "<tr><td><strong>Saldo final</strong></td><td><strong>" . number_format($saldo, 2) . "</strong></td></tr>";
echo "</table>";

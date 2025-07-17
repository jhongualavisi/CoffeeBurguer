<?php
require_once __DIR__ . '/../models/CuadreCaja.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cuadre_caja_" . date("Ymd_His") . ".xls");

$fecha = $_GET['fecha'] ?? date('Y-m-d');
$cuadre = new CuadreCaja();
$data = $cuadre->obtenerPorFecha($fecha);

echo "<table border='1'>";
echo "<tr><th>Fecha</th><th>Efectivo</th><th>Transferencia</th><th>Egresos</th><th>Saldo</th></tr>";

if ($data) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($data['fecha']) . "</td>";
    echo "<td>" . number_format($data['efectivo'], 2) . "</td>";
    echo "<td>" . number_format($data['transferencia'], 2) . "</td>";
    echo "<td>" . number_format($data['egresos'], 2) . "</td>";
    echo "<td>" . number_format($data['saldo'], 2) . "</td>";
    echo "</tr>";
} else {
    echo "<tr><td colspan='5'>No se encontró información para la fecha $fecha</td></tr>";
}

echo "</table>";
?>

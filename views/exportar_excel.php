<?php
require_once __DIR__ . '/../models/Venta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    $venta = new Venta();
    $resultados = $venta->reporteProductosVendidos($desde, $hasta);
    $total = $venta->totalVentasPorFechas($desde, $hasta);
    $total_dinero = $total['total_facturado'] ?? 0;

    // Cabeceras para descargar como Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=reporte_ventas_{$desde}_a_{$hasta}.xls");

    echo "<table border='1'>";
    echo "<tr><th colspan='3'>Reporte de Ventas del $desde al $hasta</th></tr>";
    echo "<tr><th>Producto</th><th>Unidades Vendidas</th><th>Total Vendido ($)</th></tr>";

    foreach ($resultados as $fila) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
        echo "<td>" . $fila['total_vendido'] . "</td>";
        echo "<td>$" . number_format($fila['total_por_producto'], 2) . "</td>";
        echo "</tr>";
    }

    echo "<tr><td colspan='2'><strong>Total facturado</strong></td><td><strong>$" . number_format($total_dinero, 2) . "</strong></td></tr>";
    echo "</table>";
    exit;
}

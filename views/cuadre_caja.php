<?php
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Egreso.php';
require_once __DIR__ . '/../models/CuadreCaja.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado";
    exit();
}

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$ventaModel = new Venta();
$egresoModel = new Egreso();
$cuadreModel = new CuadreCaja();

$efectivo = $ventaModel->totalPorMedioPago('efectivo', $fecha);
$transferencia = $ventaModel->totalPorMedioPago('transferencia', $fecha);
$egresos = $egresoModel->totalEgresosPorFecha($fecha);
$saldo = $efectivo + $transferencia - $egresos;
?>

<h2>Cuadre de Caja - <?php echo $fecha; ?></h2>

<form method="GET">
    <label>Filtrar por fecha:</label>
    <input type="date" name="fecha" value="<?php echo $fecha; ?>">
    <button type="submit">Filtrar</button>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <tr><td>Ingresos en efectivo:</td><td>$<?php echo number_format($efectivo, 2); ?></td></tr>
    <tr><td>Ingresos por transferencia:</td><td>$<?php echo number_format($transferencia, 2); ?></td></tr>
    <tr><td>Total egresos:</td><td>$<?php echo number_format($egresos, 2); ?></td></tr>
    <tr><td><strong>Saldo final:</strong></td><td><strong>$<?php echo number_format($saldo, 2); ?></strong></td></tr>
</table>

<form method="POST" action="../controllers/CuadreCajaController.php">
    <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
    <input type="hidden" name="efectivo" value="<?php echo $efectivo; ?>">
    <input type="hidden" name="transferencia" value="<?php echo $transferencia; ?>">
    <input type="hidden" name="egresos" value="<?php echo $egresos; ?>">
    <input type="hidden" name="saldo" value="<?php echo $saldo; ?>">
    <button type="submit">Guardar cuadre</button>
</form>

<br>
<form method="GET" action="exportar_cuadre_excel.php">
    <input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
    <button type="submit">📤 Descargar reporte Excel</button>
</form>

<?php if (isset($_GET['success'])): ?>
    <p style="color: green;">✅ Cuadre de caja guardado correctamente.</p>
<?php endif; ?>
<br>
<br>
<a href="dashboard.php">⬅ Volver al panel</a>
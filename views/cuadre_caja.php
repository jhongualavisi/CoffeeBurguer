<?php
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Egreso.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado";
    exit();
}

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$ventaModel = new Venta();
$egresoModel = new Egreso();

$efectivo = $ventaModel->totalPorMedioPago('efectivo', $fecha);
$transferencia = $ventaModel->totalPorMedioPago('transferencia', $fecha);
$egresos = $egresoModel->totalEgresosPorFecha($fecha);
$saldo = $efectivo + $transferencia - $egresos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuadre de Caja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffeaa7, #fab1a0);
            min-height: 100vh;
            padding: 30px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4"><i class="bi bi-cash-coin"></i> Cuadre de Caja - <?= $fecha ?></h2>

    <form method="GET" class="row g-3 align-items-center mb-4">
        <div class="col-auto">
            <label class="col-form-label">📅 Selecciona una fecha:</label>
        </div>
        <div class="col-auto">
            <input type="date" name="fecha" class="form-control" value="<?= $fecha ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
        </div>
    </form>

    <div class="card p-4 mb-4">
        <table class="table table-bordered table-hover">
            <tr><th>Ingresos en efectivo:</th><td>$<?= number_format($efectivo, 2) ?></td></tr>
            <tr><th>Ingresos por transferencia:</th><td>$<?= number_format($transferencia, 2) ?></td></tr>
            <tr><th>Total egresos:</th><td>$<?= number_format($egresos, 2) ?></td></tr>
            <tr class="table-success">
                <th><strong>Saldo final:</strong></th>
                <td><strong>$<?= number_format($saldo, 2) ?></strong></td>
            </tr>
        </table>
    </div>

    <form method="GET" action="exportar_cuadre_excel.php" class="d-inline">
        <input type="hidden" name="fecha" value="<?= $fecha ?>">
        <button type="submit" class="btn btn-outline-success"><i class="bi bi-file-earmark-excel-fill"></i> Descargar Excel</button>
    </form>

    <a href="dashboard.php" class="btn btn-link mt-4"><i class="bi bi-arrow-left-circle"></i> Volver al panel</a>
</div>

</body>
</html>

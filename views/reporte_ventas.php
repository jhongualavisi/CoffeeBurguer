<?php
session_start();
require_once __DIR__ . '/../models/Venta.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$venta = new Venta();
$resultados = [];
$total_dinero = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];

    $resultados = $venta->reporteProductosVendidos($desde, $hasta);
    $total = $venta->totalVentasPorFechas($desde, $hasta);
    $total_dinero = $total['total_facturado'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            min-height: 100vh;
            padding-top: 40px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <div class="card p-4 mb-4">
        <h2 class="text-center mb-3">📊 Reporte de Productos Más Vendidos</h2>

        <!-- Formulario de fechas -->
        <form method="POST" class="row g-3 justify-content-center">
            <div class="col-md-4">
                <label for="desde" class="form-label">Desde:</label>
                <input type="date" name="desde" id="desde" class="form-control" required value="<?= $_POST['desde'] ?? '' ?>">
            </div>
            <div class="col-md-4">
                <label for="hasta" class="form-label">Hasta:</label>
                <input type="date" name="hasta" id="hasta" class="form-control" required value="<?= $_POST['hasta'] ?? '' ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">Consultar</button>
            </div>
        </form>
    </div>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($resultados)): ?>
        <div class="card p-4 mb-4">
            <!-- Botón Exportar -->
            <form method="POST" action="exportar_excel.php" target="_blank" class="text-end mb-3">
                <input type="hidden" name="desde" value="<?= htmlspecialchars($_POST['desde']) ?>">
                <input type="hidden" name="hasta" value="<?= htmlspecialchars($_POST['hasta']) ?>">
                <button type="submit" class="btn btn-primary">📥 Exportar a Excel</button>
            </form>

            <!-- Tabla de resultados -->
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Unidades Vendidas</th>
                            <th>Total Vendido ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultados as $fila): ?>
                            <tr>
                                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                <td><?= $fila['total_vendido'] ?></td>
                                <td>$<?= number_format($fila['total_por_producto'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h5 class="mt-3 text-end"><strong>Total facturado:</strong> $<?= number_format($total_dinero, 2) ?></h5>
        </div>

        <!-- Gráficos -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <h5 class="text-center">📈 Total vendido por producto</h5>
                    <canvas id="graficoTotal" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <h5 class="text-center">📦 Unidades vendidas por producto</h5>
                    <canvas id="graficoUnidades" height="300"></canvas>
                </div>
            </div>
        </div>

        <script>
            const nombres = <?= json_encode(array_column($resultados, 'nombre')) ?>;
            const totales = <?= json_encode(array_map('floatval', array_column($resultados, 'total_por_producto'))) ?>;
            const cantidades = <?= json_encode(array_map('intval', array_column($resultados, 'total_vendido'))) ?>;

            new Chart(document.getElementById('graficoTotal').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: nombres,
                    datasets: [{
                        label: 'Total vendido ($)',
                        data: totales,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '$' + value
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('graficoUnidades').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: nombres,
                    datasets: [{
                        label: 'Unidades vendidas',
                        data: cantidades,
                        backgroundColor: 'rgba(255, 205, 86, 0.5)',
                        borderColor: 'rgba(255, 205, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-warning mt-4">⚠️ No se encontraron resultados en ese rango de fechas.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-dark">⬅ Volver al panel</a>
    </div>
</div>

</body>
</html>

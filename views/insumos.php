<?php
require_once '../models/Insumo.php';
$insumo = new Insumo();
$insumos = $insumo->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Insumos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fefcea, #f1da36);
            padding: 30px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .estado-ok {
            color: green;
            font-weight: bold;
        }

        .estado-bajo {
            color: red;
            font-weight: bold;
        }

        .card-table {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 20px;
            background: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center mb-4">
        <h2><i class="bi bi-boxes"></i> Inventario de Insumos</h2>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <a href="entrada_insumo.php" class="btn btn-success">
            <i class="bi bi-cart-plus-fill"></i> Registrar compra / entrada
        </a>
        <a href="form_insumo.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Insumo
        </a>
    </div>

    <div class="card-table">
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Unidad</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($insumos as $i): ?>
                    <tr>
                        <td><?= htmlspecialchars($i['nombre']) ?></td>
                        <td><?= htmlspecialchars($i['unidad_medida']) ?></td>
                        <td><?= $i['stock_actual'] ?></td>
                        <td><?= $i['stock_minimo'] ?></td>
                        <td>
                            <?php if ($i['stock_actual'] <= $i['stock_minimo']): ?>
                                <span class="estado-bajo"><i class="bi bi-exclamation-circle-fill"></i> Bajo</span>
                            <?php else: ?>
                                <span class="estado-ok"><i class="bi bi-check-circle-fill"></i> OK</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="form_insumo.php?id=<?= $i['id'] ?>" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="../controllers/InsumoController.php?eliminar=<?= $i['id'] ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('¿Eliminar este insumo?')" 
                               title="Eliminar">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left-circle"></i> Volver al panel
        </a>
    </div>
</div>

</body>
</html>

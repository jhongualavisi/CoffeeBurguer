<?php
require_once '../models/Receta.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado";
    exit();
}

$receta = new Receta();
$productos = $receta->obtenerProductos();
$insumos = $receta->obtenerInsumos();
$producto_id = $_GET['producto_id'] ?? null;
$componentes = [];

if ($producto_id) {
    $componentes = $receta->obtenerPorProducto($producto_id);
}

$editando = $_GET['editar_insumo'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Definir Receta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #ffe0b2);
            padding: 30px;
        }

        .card-receta {
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: auto;
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="card-receta">
    <h3 class="text-center mb-4"><i class="bi bi-journal-plus"></i> Definir Receta por Producto</h3>
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left-circle"></i> Volver al panel</a>
    </div>

    <form method="GET" class="row g-3 align-items-center mb-4">
        <div class="col-auto">
            <label class="col-form-label fw-bold">Selecciona un producto:</label>
        </div>
        <div class="col-auto">
            <select name="producto_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Elegir --</option>
                <?php foreach ($productos as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $producto_id == $p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($producto_id): ?>
        <hr>
        <h5 class="mb-3"><i class="bi bi-plus-square-dotted"></i> Agregar insumo a la receta</h5>
        <form action="../controllers/RecetaController.php" method="POST" class="row g-3 mb-4">
            <input type="hidden" name="producto_id" value="<?= $producto_id ?>">
            <div class="col-md-5">
                <label class="form-label">Insumo:</label>
                <select name="insumo_id" class="form-select" required>
                    <option value="">-- Selecciona --</option>
                    <?php foreach ($insumos as $i): ?>
                        <option value="<?= $i['id'] ?>"><?= $i['nombre'] ?> (<?= $i['unidad_medida'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cantidad:</label>
                <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle-fill"></i> Agregar</button>
            </div>
        </form>

        <h5 class="mb-3"><i class="bi bi-list-check"></i> Receta actual para este producto</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Insumo</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($componentes as $c): ?>
                    <tr>
                        <td><?= $c['nombre'] ?></td>

                        <?php if ($editando == $c['insumo_id']): ?>
                            <form action="../controllers/RecetaController.php" method="POST">
                                <input type="hidden" name="producto_id" value="<?= $producto_id ?>">
                                <input type="hidden" name="insumo_id" value="<?= $c['insumo_id'] ?>">
                                <input type="hidden" name="editar" value="1">
                                <td>
                                    <input type="number" step="0.01" min="0.01" name="cantidad" class="form-control" value="<?= $c['cantidad'] ?>" required>
                                </td>
                                <td><?= $c['unidad_medida'] ?></td>
                                <td class="text-center">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-save-fill"></i></button>
                                    <a href="definir_receta.php?producto_id=<?= $producto_id ?>" class="btn btn-sm btn-secondary"><i class="bi bi-x-circle"></i></a>
                                </td>
                            </form>
                        <?php else: ?>
                            <td><?= $c['cantidad'] ?></td>
                            <td><?= $c['unidad_medida'] ?></td>
                            <td class="text-center">
                                <a href="definir_receta.php?producto_id=<?= $producto_id ?>&editar_insumo=<?= $c['insumo_id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="../controllers/RecetaController.php?eliminar=<?= $c['id'] ?>&producto_id=<?= $producto_id ?>"
                                   onclick="return confirm('¿Eliminar este insumo de la receta?')" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

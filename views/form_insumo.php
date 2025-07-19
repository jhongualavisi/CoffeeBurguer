<?php
require_once '../models/Insumo.php';
$insumo = new Insumo();
$editando = false;
$data = ['nombre' => '', 'unidad_medida' => '', 'stock_actual' => '', 'stock_minimo' => ''];

if (isset($_GET['id'])) {
    $data = $insumo->obtenerPorId($_GET['id']);
    $editando = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $editando ? 'Editar Insumo' : 'Nuevo Insumo' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fef9e7, #ffe5ec);
            min-height: 100vh;
        }

        .container {
            max-width: 600px;
            margin-top: 60px;
        }

        .form-label {
            font-weight: bold;
        }

        h2 {
            color: #cc3f3f;
        }
    </style>
</head>
<body>

<div class="container shadow p-4 bg-white rounded">
    <h2 class="mb-4"><?= $editando ? 'Editar Insumo' : 'Nuevo Insumo'; ?></h2>

    <form action="../controllers/InsumoController.php" method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($data['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unidad de medida:</label>
            <input type="text" class="form-control" name="unidad_medida" value="<?= htmlspecialchars($data['unidad_medida']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock actual:</label>
            <input type="number" class="form-control" name="stock_actual" step="0.01" value="<?= $data['stock_actual'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock mínimo:</label>
            <input type="number" class="form-control" name="stock_minimo" step="0.01" value="<?= $data['stock_minimo'] ?>" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="insumos.php" class="btn btn-outline-dark">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </form>
</div>

</body>
</html>

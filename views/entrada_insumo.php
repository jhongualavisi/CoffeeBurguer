<?php
require_once '../models/EntradaInsumo.php';
session_start();

// Seguridad
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado.";
    exit();
}

$entrada = new EntradaInsumo();
$insumos = $entrada->obtenerInsumos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #fff3e0);
            padding: 40px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h4 class="text-center mb-4">
        <i class="bi bi-box-arrow-in-down"></i> Registrar Entrada de Insumo
    </h4>

    <a href="insumos.php" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left-circle"></i> Volver al inventario
    </a>

    <form action="../controllers/EntradaInsumoController.php" method="POST">
        <div class="mb-3">
            <label class="form-label"><i class="bi bi-box-seam"></i> Insumo</label>
            <select name="insumo_id" class="form-select" required>
                <option value="">-- Selecciona --</option>
                <?php foreach ($insumos as $insumo): ?>
                    <option value="<?= $insumo['id'] ?>">
                        <?= htmlspecialchars($insumo['nombre']) ?> (<?= $insumo['unidad_medida'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-plus-circle-fill"></i> Cantidad comprada</label>
            <input type="number" step="0.01" min="0.01" class="form-control" name="cantidad" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Registrar entrada
            </button>
        </div>
    </form>
</div>

</body>
</html>

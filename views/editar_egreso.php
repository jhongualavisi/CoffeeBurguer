<?php
session_start();
require_once __DIR__ . '/../models/Egreso.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$egresoModel = new Egreso();
$egreso = $egresoModel->obtenerPorId($_GET['id']);

if (!$egreso) {
    echo " Egreso no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Egreso - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fbc2eb, #a6c1ee);
            min-height: 100vh;
        }

        .container-box {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .header-section {
            background: linear-gradient(to right, #3f2b96, #a8c0ff);
            padding: 30px 20px;
            border-radius: 12px;
            color: white;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .header-section h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header-section p {
            margin: 0;
            font-style: italic;
        }

        .form-label {
            font-weight: bold;
        }

        .miniatura {
            width: 140px;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 3px;
            transition: transform 0.3s ease;
        }

        .miniatura:hover {
            transform: scale(1.4);
        }

        .btn-actualizar {
            background-color: #28a745;
            color: #fff;
        }

        .btn-actualizar:hover {
            background-color: #218838;
        }

        .btn-volver {
            background-color: #6c757d;
            color: white;
        }

        .btn-volver:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="header-section">
        <h2> Editar Egreso</h2>
        <p>Modifica los datos de un egreso ya registrado en Coffee Burguer</p>
    </div>

    <div class="container-box">
        <form action="../controllers/EgresoController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $egreso['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($egreso['descripcion']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Monto ($):</label>
                <input type="number" step="0.01" name="monto" class="form-control" value="<?= $egreso['monto'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Medio de pago:</label>
                <select name="medio_pago" class="form-select" required>
                    <option value="efectivo" <?= $egreso['medio_pago'] === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                    <option value="transferencia" <?= $egreso['medio_pago'] === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen actual:</label><br>
                <?php if (!empty($egreso['imagen_factura'])): ?>
                    <img src="../public/facturas/<?= basename($egreso['imagen_factura']) ?>" class="miniatura" alt="Factura actual">
                <?php else: ?>
                    <p class="text-muted">No hay imagen actual.</p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label">Nueva imagen (opcional):</label>
                <input type="file" name="imagen" class="form-control">
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" name="accion" value="actualizar" class="btn btn-actualizar">💾 Actualizar</button>
                <a href="egresos.php" class="btn btn-volver">🔙 Volver</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>

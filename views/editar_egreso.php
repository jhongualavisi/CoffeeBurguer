<?php
session_start();
require_once __DIR__ . '/../models/Egreso.php';

// Solo el administrador puede editar
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$egresoModel = new Egreso();
$egreso = $egresoModel->obtenerPorId($_GET['id']);

if (!$egreso) {
    echo "Egreso no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Egreso</title>
    <style>
        .miniatura {
            width: 120px;
            height: auto;
            border: 1px solid #ccc;
            padding: 3px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h2>✏️ Editar Egreso</h2>

<form action="../controllers/EgresoController.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $egreso['id'] ?>">

    <label>Descripción:</label><br>
    <input type="text" name="descripcion" value="<?= htmlspecialchars($egreso['descripcion']) ?>" required><br><br>

    <label>Monto:</label><br>
    <input type="number" step="0.01" name="monto" value="<?= $egreso['monto'] ?>" required><br><br>

    <label>Medio de pago:</label><br>
    <select name="medio_pago">
        <option value="efectivo" <?= $egreso['medio_pago'] === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
        <option value="transferencia" <?= $egreso['medio_pago'] === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
    </select><br><br>

    <label>Imagen actual:</label><br>
    <?php if (!empty($egreso['imagen_factura'])): ?>
        <img src="../public/facturas/<?= basename($egreso['imagen_factura']) ?>" class="miniatura" alt="Factura actual"><br>
    <?php else: ?>
        <p>No hay imagen actual</p>
    <?php endif; ?>

    <label>Nueva imagen (opcional):</label><br>
    <input type="file" name="imagen"><br><br>

    <button type="submit" name="accion" value="actualizar">Actualizar</button>
</form>

<br>
<a href="egresos.php">🔙 Volver a la lista</a>

</body>
</html>

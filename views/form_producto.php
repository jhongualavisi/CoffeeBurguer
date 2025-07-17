<?php
session_start();
require_once __DIR__ . '/../models/Producto.php';

$producto = new Producto();
$modo = 'crear';
$titulo = 'Nuevo Producto';
$datos = ['nombre' => '', 'precio' => ''];

if (isset($_GET['id'])) {
    $modo = 'actualizar';
    $titulo = 'Editar Producto';
    $datos = $producto->obtenerPorId($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?> - Coffee Burguer</title>
</head>
<body>

<?php include 'header.php'; ?>

<h2><?= $titulo ?></h2>

<form action="../controllers/ProductoController.php" method="POST">
    <input type="hidden" name="accion" value="<?= $modo ?>">
    <?php if ($modo === 'actualizar'): ?>
        <input type="hidden" name="id" value="<?= $datos['id'] ?>">
    <?php endif; ?>

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required value="<?= htmlspecialchars($datos['nombre']) ?>"><br><br>

    <label>Precio ($):</label><br>
    <input type="number" step="0.01" name="precio" required value="<?= htmlspecialchars($datos['precio']) ?>"><br><br>

    <button type="submit">💾 Guardar</button>
    <a href="productos.php">Cancelar</a>
</form>

</body>
</html>

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8b500, #fceabb);
            min-height: 100vh;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-weight: bold;
            margin-bottom: 25px;
            color: #333;
        }

        .btn-primary {
            background-color: #e67e22;
            border: none;
        }

        .btn-primary:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center"><?= $titulo ?> 🛒</h2>

        <form action="../controllers/ProductoController.php" method="POST">
            <input type="hidden" name="accion" value="<?= $modo ?>">
            <?php if ($modo === 'actualizar'): ?>
                <input type="hidden" name="id" value="<?= $datos['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del producto:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required value="<?= htmlspecialchars($datos['nombre']) ?>">
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio ($):</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" required value="<?= htmlspecialchars($datos['precio']) ?>">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">💾 Guardar</button>
                <a href="productos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>

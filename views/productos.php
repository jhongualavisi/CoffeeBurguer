<?php
session_start();
require_once __DIR__ . '/../models/Producto.php';

$producto = new Producto();
$productos = $producto->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            min-height: 100vh;
        }

        .container {
            padding-top: 50px;
        }

        .card-producto {
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .card-producto:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        .card-footer a {
            text-decoration: none;
        }

        .btn-editar {
            background-color: #ffc107;
            color: white;
        }

        .btn-cambiar-estado {
            background-color: #6c757d;
            color: white;
        }

        .btn-cambiar-estado.activo {
            background-color: #dc3545; /* rojo para desactivar */
        }

        .btn-cambiar-estado.inactivo {
            background-color: #198754; /* verde para activar */
        }

        .btn-nuevo {
            margin-bottom: 20px;
        }

        .titulo {
            font-weight: bold;
            color: #343a40;
        }

        .alert-msg {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2 class="mb-4 titulo text-center"> Productos Disponibles</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success text-center alert-msg">
            <?php
                if ($_GET['msg'] === 'creado') echo "Producto creado con éxito.";
                if ($_GET['msg'] === 'actualizado') echo "Producto actualizado.";
                if ($_GET['msg'] === 'eliminado') echo "Producto eliminado.";
                if ($_GET['msg'] === 'activado') echo "Producto activado.";
                if ($_GET['msg'] === 'desactivado') echo "Producto desactivado.";
            ?>
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="form_producto.php" class="btn btn-primary btn-nuevo">
            <i class="bi bi-plus-circle-fill"></i> Nuevo producto
        </a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mt-3">
        <?php foreach ($productos as $p): ?>
            <div class="col">
                <div class="card card-producto shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>
                        <p class="card-text fw-bold text-success">$<?= number_format($p['precio'], 2) ?></p>
                        <p class="mb-0">
                            Estado:
                            <?php if ($p['estado'] === 'activo'): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-around">
                        <a href="form_producto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-editar">
                             Editar
                        </a>

                        <?php if ($p['estado'] === 'activo'): ?>
                            <a href="../controllers/ProductoController.php?desactivar=<?= $p['id'] ?>"
                               class="btn btn-sm btn-cambiar-estado activo"
                               onclick="return confirm('¿Desactivar este producto?')">
                                 Desactivar
                            </a>
                        <?php else: ?>
                            <a href="../controllers/ProductoController.php?activar=<?= $p['id'] ?>"
                               class="btn btn-sm btn-cambiar-estado inactivo"
                               onclick="return confirm('¿Activar este producto?')">
                                 Activar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary">
            ⬅ Volver al panel
        </a>
    </div>
</div>

</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../models/Egreso.php';

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

$egresoModel = new Egreso();
$egresos = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    if ($fecha_inicio && $fecha_fin) {
        $fecha_inicio .= ' 00:00:00';
        $fecha_fin .= ' 23:59:59';
        $egresos = $egresoModel->obtenerPorFecha($fecha_inicio, $fecha_fin);
    }
} else {
    $egresos = $egresoModel->obtenerTodos();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Egresos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            min-height: 100vh;
        }

        .container-box {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .miniatura {
            width: 60px;
            height: auto;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .miniatura:hover {
            transform: scale(1.5);
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-filtrar {
            background-color: #e67e22;
            color: #fff;
            border: none;
        }

        .btn-filtrar:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="container-box">
        <h2 class="text-center mb-4">📋 Listado de Egresos</h2>

        <!-- Formulario de filtro -->
        <form method="POST" class="row g-3 mb-4 justify-content-center">
            <div class="col-md-4">
                <label class="form-label">Desde:</label>
                <input type="date" name="fecha_inicio" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Hasta:</label>
                <input type="date" name="fecha_fin" class="form-control" required>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-filtrar w-100">🔎 Filtrar</button>
            </div>
        </form>

        <!-- Resultados -->
        <?php if (count($egresos) === 0): ?>
            <div class="alert alert-warning text-center">⚠️ No se encontraron egresos.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Monto</th>
                            <th>Medio de pago</th>
                            <th>Factura</th>
                            <th>Registrado por</th>
                            <?php if ($_SESSION['rol'] === 'admin'): ?>
                                <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($egresos as $egreso): ?>
                            <tr>
                                <td><?= $egreso['fecha'] ?></td>
                                <td><?= htmlspecialchars($egreso['descripcion']) ?></td>
                                <td class="text-end">$<?= number_format($egreso['monto'], 2) ?></td>
                                <td class="text-center"><?= ucfirst($egreso['medio_pago']) ?></td>
                                <td class="text-center">
                                    <?php
                                        $archivo = basename($egreso['imagen_factura']);
                                        $ruta = "../public/facturas/$archivo";
                                        if (!empty($archivo) && file_exists($ruta)) {
                                            echo "<a href=\"$ruta\" target=\"_blank\"><img src=\"$ruta\" class=\"miniatura\" alt=\"Factura\"></a>";
                                        } else {
                                            echo "<span class='text-muted'>No subida</span>";
                                        }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($egreso['nombre_usuario']) ?></td>
                                <?php if ($_SESSION['rol'] === 'admin'): ?>
                                    <td class="text-center">
                                        <a href="editar_egreso.php?id=<?= $egreso['id'] ?>" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                                        <a href="../controllers/EgresoController.php?accion=eliminar&id=<?= $egreso['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('¿Seguro que deseas eliminar este egreso?')">🗑️ Eliminar</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Volver al panel</a>
        </div>
    </div>
</div>

</body>
</html>

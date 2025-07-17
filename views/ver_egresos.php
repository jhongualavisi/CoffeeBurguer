<?php
session_start();
require_once __DIR__ . '/../models/Egreso.php';

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}

$egresoModel = new Egreso();
$egresos = [];

// Filtro por fechas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    if ($fecha_inicio && $fecha_fin) {
        $fecha_inicio .= ' 00:00:00';
        $fecha_fin .= ' 23:59:59';
        $egresos = $egresoModel->obtenerPorFecha($fecha_inicio, $fecha_fin);
    }
} else {
    // ✅ Mostrar todos los egresos para ambos roles
    $egresos = $egresoModel->obtenerTodos();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Egresos</title>
    <style>
        .miniatura {
            width: 80px;
            height: auto;
            border: 1px solid #ccc;
            padding: 2px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h2>📋 Listado de Egresos</h2>

<form method="POST">
    <label>Desde:</label>
    <input type="date" name="fecha_inicio" required>
    <label>Hasta:</label>
    <input type="date" name="fecha_fin" required>
    <button type="submit">Filtrar</button>
</form>

<?php if (count($egresos) === 0): ?>
    <p>No se encontraron egresos.</p>
<?php else: ?>
    <table border="1" cellpadding="8">
        <thead>
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
                    <td>$<?= number_format($egreso['monto'], 2) ?></td>
                    <td><?= ucfirst($egreso['medio_pago']) ?></td>
                    <td>
                        <?php
                        $archivo = basename($egreso['imagen_factura']);
                        $ruta = "../public/facturas/$archivo";
                        if (!empty($archivo) && file_exists($ruta)) {
                            echo "<a href=\"$ruta\" target=\"_blank\"><img src=\"$ruta\" class=\"miniatura\" alt=\"Factura\"></a>";
                        } else {
                            echo "No subida";
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($egreso['nombre_usuario']) ?></td>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <td>
                            <a href="editar_egreso.php?id=<?= $egreso['id'] ?>">✏️ Editar</a> |
                            <a href="../controllers/EgresoController.php?accion=eliminar&id=<?= $egreso['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este egreso?')">🗑️ Eliminar</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<br>
<a href="dashboard.php">🔙 Volver al panel</a>

</body>
</html>

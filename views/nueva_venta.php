<?php
session_start();
require_once __DIR__ . '/../models/Venta.php';

$venta = new Venta();
$productos = $venta->obtenerProductos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #ffe0b2);
            min-height: 100vh;
            padding: 20px;
        }

        .scrollable-menu {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            background-color: #ffffff;
        }

        .card-producto {
            margin-bottom: 15px;
        }

        .card-producto .btn {
            margin-top: 10px;
        }

        table {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center">🧾 Nuevo Pedido</h2>

    <h4 class="mb-3">🍔 Menú de Productos</h4>
    <div class="scrollable-menu mb-4 row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php foreach ($productos as $prod): ?>
            <div class="col">
                <div class="card card-producto shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
                        <p class="card-text text-success fw-bold">$<?= number_format($prod['precio'], 2) ?></p>
                        <button class="btn btn-warning btn-sm" onclick="agregarProducto(<?= $prod['id'] ?>, '<?= htmlspecialchars($prod['nombre']) ?>', <?= $prod['precio'] ?>)">Agregar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h4 class="mb-3">🧾 Resumen del Pedido</h4>
    <form action="../controllers/VentaController.php" method="POST" onsubmit="return validarEnvio()">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center" id="tablaResumen">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <p class="fs-5">💰 <strong>Total: $<span id="total">0.00</span></strong></p>

        <div class="mb-3">
            <label class="form-label">Método de pago:</label>
            <select name="medio_pago" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Transferencia</option>
            </select>
        </div>

        <input type="hidden" name="productos" id="productos">
        <input type="hidden" name="total" id="total_hidden">

        <button type="submit" class="btn btn-success">💾 Guardar venta</button>
        <a href="dashboard.php" class="btn btn-secondary ms-2">⬅ Volver al panel</a>
    </form>
</div>

<script>
let productos = [];

function agregarProducto(id, nombre, precio) {
    const existente = productos.find(p => p.id === id);
    if (existente) {
        existente.cantidad++;
    } else {
        productos.push({ id, nombre, precio, cantidad: 1 });
    }
    renderTabla();
}

function editarCantidad(index, cantidad) {
    productos[index].cantidad = parseInt(cantidad);
    renderTabla();
}

function eliminarProducto(index) {
    productos.splice(index, 1);
    renderTabla();
}

function renderTabla() {
    const tbody = document.querySelector('#tablaResumen tbody');
    tbody.innerHTML = '';
    let total = 0;

    productos.forEach((p, index) => {
        const subtotal = p.precio * p.cantidad;
        total += subtotal;

        tbody.innerHTML += `
            <tr>
                <td>${p.nombre}</td>
                <td>
                    <input type="number" min="1" class="form-control form-control-sm text-center" value="${p.cantidad}" onchange="editarCantidad(${index}, this.value)">
                </td>
                <td>$${p.precio.toFixed(2)}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${index})">❌</button></td>
            </tr>
        `;
    });

    document.getElementById('total').textContent = total.toFixed(2);
    document.getElementById('productos').value = JSON.stringify(productos);
    document.getElementById('total_hidden').value = total.toFixed(2);
}

function validarEnvio() {
    if (productos.length === 0) {
        alert("Debes agregar al menos un producto.");
        return false;
    }
    return true;
}
</script>

</body>
</html>

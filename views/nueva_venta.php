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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #ffe0b2);
            min-height: 100vh;
            padding: 20px;
        }
        .tabla-productos {
            height: 75vh;
            overflow-y: auto;
        }
        .resumen-box {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            height: 75vh;
            overflow-y: auto;
        }
        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <h2 class="mb-4 text-center">Nueva Venta</h2>

    <div class="row">
        <!-- Lista de productos -->
        <div class="col-md-7">
            <h5>Menú de Productos</h5>
            <div class="table-responsive tabla-productos">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $prod): ?>
                            <tr>
                                <td><?= htmlspecialchars($prod['nombre']) ?></td>
                                <td class="text-success fw-bold">$<?= number_format($prod['precio'], 2) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="agregarProducto(<?= $prod['id'] ?>, '<?= htmlspecialchars($prod['nombre']) ?>', <?= $prod['precio'] ?>)">
                                         Agregar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resumen del pedido -->
        <div class="col-md-5">
            <div class="resumen-box">
                <h5>Resumen del Pedido</h5>
                <form action="../controllers/VentaController.php" method="POST" onsubmit="return validarEnvio()">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-center align-middle" id="tablaResumen">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <p class="fs-6"><strong>Total: $<span id="total">0.00</span></strong></p>

                    <div class="mb-2">
                        <label class="form-label">Método de pago:</label>
                        <select name="medio_pago" class="form-select form-select-sm" required>
                            <option value="">Seleccione</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>

                    <input type="hidden" name="productos" id="productos">
                    <input type="hidden" name="total" id="total_hidden">

                    <button type="submit" class="btn btn-success btn-sm"> Guardar venta</button>
                    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">⬅ Volver</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let productos = [];

function agregarProducto(id, nombre, precio) {
    fetch(`../controllers/VentaController.php?verificar_stock=${id}`)
        .then(res => res.json())
        .then(data => {
            const maximo = data.maximo_permitido;
            const faltantes = data.faltantes;

            const existente = productos.find(p => p.id === id);

            if (existente) {
                if (existente.cantidad + 1 > maximo) {
                    alert(`Solo puedes agregar hasta ${maximo} unidades de ${nombre}. Faltan insumos:\n- ${faltantes.join("\n- ")}`);
                    return;
                }
                existente.cantidad++;
            } else {
                if (maximo < 1) {
                    alert(`No hay stock suficiente para vender '${nombre}'. Faltan insumos:\n- ${faltantes.join("\n- ")}`);
                    return;
                }
                productos.push({ id, nombre, precio, cantidad: 1 });
            }

            renderTabla();
        })
        .catch(err => {
            console.error("Error al verificar stock:", err);
            alert("No se pudo verificar el inventario. Intenta de nuevo.");
        });
}


function editarCantidad(index, cantidad) {
    const cantidadInt = parseInt(cantidad);
    if (isNaN(cantidadInt) || cantidadInt < 1) return;

    const producto = productos[index];
    $.getJSON('../controllers/VentaController.php', { verificar_stock: producto.id }, function(respuesta) {
        if (cantidadInt > respuesta.maximo_permitido) {
            alert("Solo puedes vender " + respuesta.maximo_permitido + " unidades. Faltan insumos.");
            productos[index].cantidad = respuesta.maximo_permitido;
        } else {
            productos[index].cantidad = cantidadInt;
        }
        renderTabla();
    });
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
                    <input type="number" min="1" max="999"
                        class="form-control form-control-sm text-center"
                        value="${p.cantidad}"
                        onchange="editarCantidad(${index}, this.value)">
                </td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger"
                        onclick="eliminarProducto(${index})">🗑️</button>
                </td>
            </tr>
        `;
    });

    document.getElementById('total').textContent = total.toFixed(2);
    document.getElementById('productos').value = JSON.stringify(productos);
    document.getElementById('total_hidden').value = total.toFixed(2);
}

function validarEnvio() {
    if (productos.length === 0) {
        alert("Agrega al menos un producto.");
        return false;
    }
    return true;
}
</script>

</body>
</html>

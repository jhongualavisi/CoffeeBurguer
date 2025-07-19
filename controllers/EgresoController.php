<?php
session_start();
require_once __DIR__ . '/../models/Egreso.php';

$egreso = new Egreso();

// ✅ Registrar nuevo egreso
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['usuario_id'])) {
    $accion = $_POST['accion'] ?? 'crear';

    if ($accion === 'crear') {
        $descripcion = $_POST['descripcion'];
        $monto = $_POST['monto'];
        $medio_pago = $_POST['medio_pago'];
        $usuario_id = $_SESSION['usuario_id'];

        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = time() . '_' . basename($_FILES['imagen']['name']);
            $rutaRelativa = "public/facturas/" . $nombreArchivo;
            $rutaDestino = "../" . $rutaRelativa;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen = $rutaRelativa;
            }
        }

        if ($egreso->registrar($descripcion, $monto, $medio_pago, $imagen, $usuario_id)) {
            header("Location: ../views/egresos.php?mensaje=creado");
            exit();
        } else {
            header("Location: ../views/egresos.php?mensaje=error");
            exit();
        }
    }

    // ✅ Actualizar egreso
    if ($accion === 'actualizar' && $_SESSION['rol'] === 'admin') {
        $id = $_POST['id'];
        $descripcion = $_POST['descripcion'];
        $monto = $_POST['monto'];
        $medio_pago = $_POST['medio_pago'];
        $imagen = null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = time() . '_' . basename($_FILES['imagen']['name']);
            $rutaRelativa = "public/facturas/" . $nombreArchivo;
            $rutaDestino = "../" . $rutaRelativa;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen = $rutaRelativa;
            }
        }

        $egreso->actualizar($id, $descripcion, $monto, $medio_pago, $imagen);

        // ✅ Redirigir con mensaje de éxito
        header("Location: ../views/ver_egresos.php?mensaje=actualizado");
        exit();
    }
}

// ✅ Eliminar egreso (GET)
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    if ($_SESSION['rol'] === 'admin' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $egreso->eliminar($id);
        header("Location: ../views/egresos.php?mensaje=eliminado");
        exit();
    }
}

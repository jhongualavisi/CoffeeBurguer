<?php
session_start();
require_once __DIR__ . '/../models/Venta.php';

$venta = new Venta();

//  Verificación de stock desde JavaScript (AJAX GET)
if (isset($_GET['verificar_stock'])) {
    $producto_id = $_GET['verificar_stock'];

    $maximo = 1000; // Límite alto inicial
    $faltantes = [];

    for ($i = 1; $i <= 999; $i++) {
        $faltantesSim = $venta->verificarStockDisponible($producto_id, $i);
        if (!empty($faltantesSim)) {
            $maximo = $i - 1;
            $faltantes = array_map(function($f) {
                return "{$f['insumo']} (disponible: {$f['disponible']}, necesita: {$f['necesita']})";
            }, $faltantesSim);
            break;
        }
    }

    echo json_encode([
        'maximo_permitido' => $maximo,
        'faltantes' => $faltantes
    ]);
    exit;
}

//  Registro de venta con verificación de stock del lado del servidor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../views/login.php");
        exit();
    }

    $usuario_id = $_SESSION['usuario_id'];
    $medio_pago = $_POST['medio_pago'];
    $total = $_POST['total'];
    $productos = json_decode($_POST['productos'], true);

    //  Verificar el stock de todos los productos antes de registrar
    foreach ($productos as $p) {
        $faltantes = $venta->verificarStockDisponible($p['id'], $p['cantidad']);
        if (!empty($faltantes)) {
            $mensaje = "Stock insuficiente para el producto '{$p['nombre']}'.";
            $_SESSION['error_venta'] = $mensaje;
            header("Location: ../views/nueva_venta.php?error=stock");
            exit();
        }
    }

    //  Todo verificado, registrar
    $venta->registrarPedido($usuario_id, $medio_pago, $total, $productos);
    header("Location: ../views/dashboard.php?mensaje=venta_exitosa");
    exit();
}

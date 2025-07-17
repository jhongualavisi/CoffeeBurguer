<?php
session_start();
require_once __DIR__ . '/../models/Venta.php';

$venta = new Venta();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //  Usar el ID real del usuario logeado
    $usuario_id = $_SESSION['usuario_id'];

    $medio_pago = $_POST['medio_pago'];
    $total = $_POST['total'];
    $productos = json_decode($_POST['productos'], true);

    $venta->registrarPedido($usuario_id, $medio_pago, $total, $productos);
    header("Location: ../views/dashboard.php?mensaje=venta_exitosa");
    exit();
}

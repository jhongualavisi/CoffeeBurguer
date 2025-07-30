<?php
require_once __DIR__ . '/../models/Producto.php';
session_start();

$producto = new Producto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    if (isset($_POST['id'])) {
        $producto->actualizar($_POST['id'], $nombre, $precio);
    } else {
        $producto->crear($nombre, $precio);
    }

    header('Location: ../views/productos.php');
    exit();
}

if (isset($_GET['desactivar'])) {
    $producto->cambiarEstado($_GET['desactivar'], 'inactivo');
    header('Location: ../views/productos.php');
    exit();
}

if (isset($_GET['activar'])) {
    $producto->cambiarEstado($_GET['activar'], 'activo');
    header('Location: ../views/productos.php');
    exit();
}

<?php
session_start();
require_once __DIR__ . '/../models/Producto.php';

$producto = new Producto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    if ($accion === 'crear') {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];

        $producto->crear($nombre, $precio);
        header("Location: ../views/productos.php?msg=creado");
        exit();
    }

    if ($accion === 'actualizar') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];

        $producto->actualizar($id, $nombre, $precio);
        header("Location: ../views/productos.php?msg=actualizado");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $producto->eliminar($id);
    header("Location: ../views/productos.php?msg=eliminado");
    exit();
}

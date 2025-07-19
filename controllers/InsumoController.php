<?php
require_once __DIR__ . '/../models/Insumo.php';
session_start();

$insumo = new Insumo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $unidad = $_POST['unidad_medida'];
    $stock = $_POST['stock_actual'];
    $minimo = $_POST['stock_minimo'];

    if (isset($_POST['id'])) {
        $insumo->actualizar($_POST['id'], $nombre, $unidad, $stock, $minimo);
    } else {
        $insumo->crear($nombre, $unidad, $stock, $minimo);
    }

    header('Location: ../views/insumos.php');
    exit();
}

if (isset($_GET['eliminar'])) {
    $insumo->eliminar($_GET['eliminar']);
    header('Location: ../views/insumos.php');
    exit();
}

<?php
require_once __DIR__ . '/../models/Receta.php';
session_start();

$receta = new Receta();

// ✅ Agregar insumo a receta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'], $_POST['insumo_id'], $_POST['cantidad'])) {
    $producto_id = $_POST['producto_id'];
    $insumo_id = $_POST['insumo_id'];
    $cantidad = $_POST['cantidad'];

    // Si viene desde editar_receta.php (lleva extra parámetro 'editar')
    if (isset($_POST['editar'])) {
        $receta->actualizarCantidad($producto_id, $insumo_id, $cantidad);
        header("Location: ../views/definir_receta.php?producto_id=$producto_id&mensaje=actualizado");
        exit();
    }

    // Si es una nueva receta (desde definir_receta.php)
    $receta->agregar($producto_id, $insumo_id, $cantidad);
    header("Location: ../views/definir_receta.php?producto_id=$producto_id&mensaje=agregado");
    exit();
}

// ✅ Eliminar un insumo de la receta
if (isset($_GET['eliminar']) && isset($_GET['producto_id'])) {
    $id = $_GET['eliminar'];
    $producto_id = $_GET['producto_id'];
    $receta->eliminar($id);
    header("Location: ../views/definir_receta.php?producto_id=$producto_id&mensaje=eliminado");
    exit();
}

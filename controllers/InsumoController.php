<?php
require_once __DIR__ . '/../models/Insumo.php';
session_start();

$insumo = new Insumo();

// GUARDAR: Crear o actualizar insumo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $unidad = $_POST['unidad_medida'];
    $stock = $_POST['stock_actual'];
    $minimo = $_POST['stock_minimo'];

    if (isset($_POST['id'])) {
        // Actualizar
        $insumo->actualizar($_POST['id'], $nombre, $unidad, $stock, $minimo);
    } else {
        // Crear nuevo
        $insumo->crear($nombre, $unidad, $stock, $minimo);
    }

    header('Location: ../views/insumos.php');
    exit();
}

// ELIMINAR: Con manejo de errores si está relacionado con otra tabla
if (isset($_GET['eliminar'])) {
    try {
        $insumo->eliminar($_GET['eliminar']);
        header('Location: ../views/insumos.php?mensaje=eliminado');
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            // Error por clave foránea (relación con recetas o entrada_insumo)
            header('Location: ../views/insumos.php?error=relacionado');
        } else {
            // Otro error inesperado
            header('Location: ../views/insumos.php?error=general');
        }
    }
    exit();
}

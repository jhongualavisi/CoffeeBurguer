<?php
require_once __DIR__ . '/../models/EntradaInsumo.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $insumo_id = $_POST['insumo_id'];
    $cantidad = $_POST['cantidad'];
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    $entrada = new EntradaInsumo();
    $entrada->registrarEntrada($insumo_id, $cantidad, $usuario_id);

    header('Location: ../views/insumos.php?mensaje=entrada_registrada');
    exit();
}

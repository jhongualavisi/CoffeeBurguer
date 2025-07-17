<?php
require_once __DIR__ . '/../models/CuadreCaja.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $efectivo = $_POST['efectivo'];
    $transferencia = $_POST['transferencia'];
    $egresos = $_POST['egresos'];
    $saldo = $_POST['saldo'];

    $cuadre = new CuadreCaja();
    $cuadre->guardar($fecha, $efectivo, $transferencia, $egresos, $saldo);

    header("Location: ../views/cuadre_caja.php?fecha=$fecha&success=1");
    exit();
}

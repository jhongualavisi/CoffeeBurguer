<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

$respuesta1 = strtolower(trim($_POST['respuesta1']));
$respuesta2 = strtolower(trim($_POST['respuesta2']));
$id = $_SESSION['recuperar_id'] ?? null;

if (!$id) {
    echo "No se encontró sesión activa.";
    exit();
}

$usuario = new Usuario();
$datos = $usuario->obtenerPorId($id);

if (
    $datos &&
    password_verify($respuesta1, $datos['respuesta_seguridad']) &&
    password_verify($respuesta2, $datos['respuesta_seguridad_2'])
) {
    $_SESSION['permitido_cambiar'] = true;
    $_SESSION['usuario_recuperado_email'] = $datos['email'];
    header("Location: ../views/nueva_clave.php");
    exit();
} else {
    echo "Una o ambas respuestas son incorrectas.";
}

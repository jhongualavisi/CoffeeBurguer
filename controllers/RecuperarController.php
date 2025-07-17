<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

if (isset($_POST['rol'])) {
    $usuario = new Usuario();
    $datos = $usuario->obtenerPorRol($_POST['rol']);

    if ($datos) {
        $_SESSION['recuperar_id'] = $datos['id'];
        $_SESSION['pregunta_1'] = $datos['pregunta_seguridad'];
        $_SESSION['pregunta_2'] = $datos['pregunta_seguridad_2'];
        header("Location: ../views/responder_preguntas.php");
    } else {
        echo "Rol no encontrado.";
    }
} else {
    echo "Parámetro faltante.";
}

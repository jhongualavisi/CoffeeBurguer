<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

$email = $_SESSION['login_email'] ?? null;

if ($email) {
    $usuario = new Usuario();
    $datos = $usuario->obtenerPorEmail($email);

    if ($datos) {
        $_SESSION['usuario_recuperado_email'] = $datos['email'];
        $_SESSION['pregunta_1'] = $datos['pregunta_seguridad'];
        $_SESSION['respuesta_1'] = $datos['respuesta_seguridad'];
        $_SESSION['pregunta_2'] = $datos['pregunta_seguridad_2'];
        $_SESSION['respuesta_2'] = $datos['respuesta_seguridad_2'];

        header("Location: pregunta_seguridad.php");
        exit();
    } else {
        echo "❌ El correo ya no existe en el sistema.";
        echo "<br><a href='login.php'>Volver al login</a>";
        exit();
    }
} else {
    echo "❌ No hay información previa del correo.";
    echo "<br><a href='login.php'>Volver al login</a>";
}

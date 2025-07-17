<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

if (isset($_POST['nueva_clave']) && isset($_SESSION['usuario_recuperado_email'])) {
    $usuario = new Usuario();
    $resultado = $usuario->cambiarClave($_SESSION['usuario_recuperado_email'], $_POST['nueva_clave']);

    if ($resultado) {
        echo "Contraseña actualizada correctamente. <a href='../views/login.php'>Iniciar sesión</a>";
        session_destroy();
    } else {
        echo "No se pudo actualizar la contraseña.";
    }
} else {
    echo "Faltan datos para cambiar la contraseña.";
}

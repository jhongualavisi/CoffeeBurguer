<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuesta1 = $_POST['respuesta_1'] ?? '';
    $respuesta2 = $_POST['respuesta_2'] ?? '';

    if (
        password_verify($respuesta1, $_SESSION['respuesta_1']) &&
        password_verify($respuesta2, $_SESSION['respuesta_2'])
    ) {
        header("Location: guardar_nueva_clave.php");
        exit();
    } else {
        echo " Acceso no autorizado. Las respuestas no coinciden.";
        echo "<br><a href='login.php'>Volver al login</a>";
        session_destroy();
        exit();
    }
}

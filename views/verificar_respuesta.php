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
        // Mostrar mensaje y redirigir automáticamente al login
        session_destroy(); // destruir la sesión antes de redirigir
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="refresh" content="3;url=../views/login.php">
            <title>Acceso denegado</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body style="background-color: #f8d7da; display: flex; justify-content: center; align-items: center; height: 100vh;">
            <div class="text-center">
                <div class="alert alert-danger" role="alert" style="max-width: 500px;">
                    <h4 class="alert-heading">❌ Acceso no autorizado</h4>
                    <p>Las respuestas no coinciden.</p>
                    <hr>
                    <p class="mb-0">Serás redirigido al login en 3 segundos...</p>
                </div>
            </div>
        </body>
        </html>';
        exit();
    }
}

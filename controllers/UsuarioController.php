<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $usuario = new Usuario();
    $resultado = $usuario->verificar($email, $clave);

    if ($resultado) {
        $_SESSION['usuario'] = $resultado['nombre'];
        $_SESSION['rol'] = $resultado['rol'];
        $_SESSION['usuario_id'] = $resultado['id'];
        unset($_SESSION['login_email']);
        header("Location: ../views/dashboard.php");
        exit();
    } else {
        $datos = $usuario->obtenerPorEmail($email);
        if ($datos) {
            $_SESSION['login_email'] = $email;
            header("Location: ../views/login.php?error=clave");
        } else {
            header("Location: ../views/login.php?error=usuario");
        }
        exit();
    }
}

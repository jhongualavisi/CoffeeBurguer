<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

$clave_actualizada = false;
$error = false;

if (isset($_POST['nueva_clave']) && isset($_SESSION['usuario_recuperado_email'])) {
    $usuario = new Usuario();
    $resultado = $usuario->cambiarClave($_SESSION['usuario_recuperado_email'], $_POST['nueva_clave']);

    if ($resultado) {
        $clave_actualizada = true;
        session_destroy();
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffcc70, #ff8177);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-reset {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        .logo {
            width: 100px;
            display: block;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>

<div class="card-reset text-center">
    <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo">
    <h4 class="mb-4">Restablecer contraseña</h4>

    <?php if ($clave_actualizada): ?>
        <div class="alert alert-success">✅ Contraseña actualizada correctamente.</div>
        <a href="login.php" class="btn btn-primary w-100 mt-3">Iniciar sesión</a>
    <?php elseif ($error): ?>
        <div class="alert alert-danger">❌ No se pudo actualizar la contraseña.</div>
        <a href="login.php" class="btn btn-secondary w-100 mt-3">Volver al login</a>
    <?php elseif (isset($_SESSION['usuario_recuperado_email'])): ?>
        <form method="POST">
            <div class="mb-3 text-start">
                <label class="form-label">Nueva contraseña:</label>
                <input type="password" name="nueva_clave" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning w-100">Guardar nueva contraseña</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">❌ Faltan datos para cambiar la contraseña.</div>
        <a href="login.php" class="btn btn-secondary w-100 mt-3">Volver al login</a>
    <?php endif; ?>
</div>

</body>
</html>

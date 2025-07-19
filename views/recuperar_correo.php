<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

$usuario = null;
$mostrar_formulario_respuestas = false;
$mensaje_error = "";
$correo_mostrado = "";

// Paso 1: buscar usuario por nombre y rol
if (isset($_POST['buscar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $rol = strtolower(trim($_POST['rol'] ?? ''));

    if (!in_array($rol, ['admin', 'cajero'])) {
        $mensaje_error = " El rol debe ser 'admin' o 'cajero'.";
    } else {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorNombreYRol($nombre, $rol);

        if ($usuario) {
            $_SESSION['tmp_usuario'] = $usuario;
            $mostrar_formulario_respuestas = true;
        } else {
            $mensaje_error = " No se encontró ningún usuario con ese nombre y rol.";
        }
    }
}

// Paso 2: verificar respuestas
if (isset($_POST['verificar'])) {
    $respuesta_1 = $_POST['respuesta_1'] ?? '';
    $respuesta_2 = $_POST['respuesta_2'] ?? '';
    $usuario = $_SESSION['tmp_usuario'] ?? null;

    if (
        $usuario &&
        password_verify($respuesta_1, $usuario['respuesta_seguridad']) &&
        password_verify($respuesta_2, $usuario['respuesta_seguridad_2'])
    ) {
        $correo_mostrado = $usuario['email'];
        session_destroy();
    } else {
        $mensaje_error = "Las respuestas no coinciden.";
        session_destroy();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Correo - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffcc70, #ff8177);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-recovery {
            background-color: #fff;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            max-width: 480px;
            width: 100%;
            animation: fadeIn 0.5s ease-in-out;
        }

        .logo {
            width: 90px;
            height: 90px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: block;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 600;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="card-recovery">
    <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo">
    <h4 class="text-center mb-3"><i class="bi bi-envelope-exclamation-fill"></i> ¿Olvidaste tu correo?</h4>

    <?php if ($mensaje_error): ?>
        <div class="alert alert-danger"><?= $mensaje_error ?></div>
    <?php endif; ?>

    <?php if ($correo_mostrado): ?>
        <div class="alert alert-success text-center">
            <i class="bi bi-check-circle-fill text-success"></i>
            <strong>Tu correo es:</strong> <?= htmlspecialchars($correo_mostrado) ?>
        </div>
        <a href="login.php" class="btn btn-outline-primary w-100 mt-3">
            <i class="bi bi-box-arrow-in-left"></i> Volver al login
        </a>
    <?php elseif (!$mostrar_formulario_respuestas): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person-fill"></i> Nombre completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-person-badge-fill"></i> Rol (admin o cajero)</label>
                <input type="text" name="rol" class="form-control" required>
            </div>

            <button type="submit" name="buscar" class="btn btn-warning w-100">
                <i class="bi bi-search"></i> Buscar preguntas
            </button>
        </form>
    <?php elseif ($mostrar_formulario_respuestas && $usuario): ?>
        <form method="POST" class="mt-4">
            <h5 class="mb-3 text-center"><i class="bi bi-shield-lock-fill"></i> Verifica tu identidad</h5>
            <div class="mb-3">
                <label class="form-label"><?= htmlspecialchars($usuario['pregunta_seguridad']) ?></label>
                <input type="text" name="respuesta_1" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><?= htmlspecialchars($usuario['pregunta_seguridad_2']) ?></label>
                <input type="text" name="respuesta_2" class="form-control" required>
            </div>

            <button type="submit" name="verificar" class="btn btn-success w-100">
                <i class="bi bi-unlock-fill"></i> Mostrar mi correo
            </button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>

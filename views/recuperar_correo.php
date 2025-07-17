<?php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

$usuario = null;
$mostrar_formulario_respuestas = false;
$mensaje_error = "";

// Paso 1: buscar usuario por nombre y rol
if (isset($_POST['buscar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $rol = strtolower(trim($_POST['rol'] ?? ''));

    if (!in_array($rol, ['admin', 'cajero'])) {
        $mensaje_error = "❌ El rol debe ser 'admin' o 'cajero'.";
    } else {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorNombreYRol($nombre, $rol);

        if ($usuario) {
            $_SESSION['tmp_usuario'] = $usuario;
            $mostrar_formulario_respuestas = true;
        } else {
            $mensaje_error = "❌ No se encontró ningún usuario con ese nombre y rol.";
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
        echo "<div style='text-align:center; margin-top:40px;'>";
        echo "<h3 class='text-success'>✅ Tu correo es: <strong>{$usuario['email']}</strong></h3>";
        echo "<a href='login.php' class='btn btn-primary mt-3'>Volver al login</a>";
        echo "</div>";
        session_destroy();
        exit();
    } else {
        $mensaje_error = "❌ Las respuestas no coinciden.";
        session_destroy();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar correo - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffcc70, #ff8177);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-recovery {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }

        .logo {
            width: 100px;
            margin: 0 auto 20px;
            display: block;
        }
    </style>
</head>
<body>

<div class="card-recovery">
    <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo">
    <h4 class="text-center mb-4">¿Olvidaste tu correo?</h4>

    <?php if ($mensaje_error): ?>
        <div class="alert alert-danger"><?php echo $mensaje_error; ?></div>
    <?php endif; ?>

    <?php if (!$mostrar_formulario_respuestas): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol (admin o cajero)</label>
                <input type="text" name="rol" class="form-control" required>
            </div>

            <button type="submit" name="buscar" class="btn btn-primary w-100">Buscar preguntas</button>
        </form>
    <?php endif; ?>

    <?php if ($mostrar_formulario_respuestas && $usuario): ?>
        <form method="POST" class="mt-4">
            <h5 class="mb-3 text-center">Verifica tu identidad</h5>
            <div class="mb-3">
                <label class="form-label"><?php echo $usuario['pregunta_seguridad']; ?></label>
                <input type="text" name="respuesta_1" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><?php echo $usuario['pregunta_seguridad_2']; ?></label>
                <input type="text" name="respuesta_2" class="form-control" required>
            </div>

            <button type="submit" name="verificar" class="btn btn-success w-100">Mostrar mi correo</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>

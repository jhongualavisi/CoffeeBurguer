<?php
session_start();
if (!isset($_SESSION['pregunta_1']) || !isset($_SESSION['pregunta_2'])) {
    echo "Error: No se ha definido el flujo de recuperación.";
    echo '<br><a href="login.php">Volver al login</a>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de Seguridad - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffcc70, #ff8177);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .verify-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        .logo {
            width: 100px;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>

<div class="verify-card text-center">
    <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo">
    <h3 class="mb-4">Verificación de Seguridad</h3>

    <form method="POST" action="verificar_respuesta.php" class="text-start">
        <div class="mb-3">
            <label class="form-label"><?php echo $_SESSION['pregunta_1']; ?></label>
            <input type="text" name="respuesta_1" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label"><?php echo $_SESSION['pregunta_2']; ?></label>
            <input type="text" name="respuesta_2" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Verificar respuestas</button>
        <div class="mt-3">
            <a href="login.php" class="text-decoration-none">⬅ Volver al login</a>
        </div>
    </form>
</div>

</body>
</html>

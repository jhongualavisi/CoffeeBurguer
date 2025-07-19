<?php
session_start();

if (!isset($_SESSION['permitido_cambiar']) || !$_SESSION['usuario_recuperado_email']) {
    echo " Acceso no autorizado.";
    echo "<br><a href='login.php'>Volver</a>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Clave</title>
</head>
<body>

<h2> Ingresa tu nueva contraseña</h2>

<form action="../controllers/CambiarClaveController.php" method="POST">
    <label>Nueva contraseña:</label><br>
    <input type="password" name="nueva_clave" required><br><br>
    <button type="submit">Cambiar contraseña</button>
</form>

</body>
</html>

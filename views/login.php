<!-- Estoy usando GIT -->
<?php
session_start();
$email_guardado = $_SESSION['login_email'] ?? '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffcc70, #ff8177);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
        }

        .form-text-danger {
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo">

    <h3 class="mb-4">Iniciar sesión</h3>

    <?php if ($error === 'clave'): ?>
        <div class="alert alert-danger py-2"> Contraseña incorrecta</div>
        <a href="recuperar.php" class="d-block mb-2">¿Olvidaste tu contraseña?</a>
    <?php elseif ($error === 'usuario'): ?>
        <div class="alert alert-danger py-2"> Usuario no encontrado</div>
        <a href="recuperar_correo.php" class="d-block mb-2">¿Olvidaste tu correo?</a>
    <?php endif; ?>

    <form id="loginForm" action="../controllers/UsuarioController.php" method="POST" novalidate>
        <div class="mb-3 text-start">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $email_guardado; ?>" required>
            <div id="emailError" class="form-text text-danger d-none"> Ingresa un correo electrónico válido.</div>
        </div>

        <div class="mb-3 text-start">
            <label for="clave" class="form-label">Contraseña</label>
            <input type="password" name="clave" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning w-100">Entrar</button>
    </form>
</div>

<!-- JS al final para asegurar que el DOM esté listo -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');

    // Validar caracteres permitidos al tipear
    emailInput.addEventListener('keypress', function (e) {
        const key = e.key;
        const allowedChars = /^[a-zA-Z0-9@._]$/;
        if (!allowedChars.test(key)) {
            e.preventDefault();
        }
    });

    // Validar estructura del correo al escribir
    emailInput.addEventListener('input', function () {
        const emailRegex = /^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            emailError.classList.remove('d-none');
        } else {
            emailInput.classList.remove('is-invalid');
            emailError.classList.add('d-none');
        }
    });

    // Limpiar error de la URL al recargar
    if (window.location.search.includes('error=')) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>

</body>
</html>

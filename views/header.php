<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .custom-header {
        background: linear-gradient(to right, #ffecd2, #fcb69f);
        padding: 30px;
        margin: 20px auto 10px auto;
        border-radius: 20px;
        max-width: 90%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .custom-header .title {
        font-weight: bold;
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .custom-header .logo {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
    }

    .custom-header .rol {
        color: #6c757d;
        font-size: 1rem;
    }

    .alert {
        max-width: 90%;
        margin: 10px auto;
        font-weight: bold;
    }
</style>

<!-- Encabezado principal -->
<div class="container">
    <div class="custom-header d-flex justify-content-between align-items-center">
        <div>
            <div class="title">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></div>
            <div class="rol">Rol: <strong><?= htmlspecialchars($_SESSION['rol']) ?></strong></div>
        </div>
        <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo">
    </div>
</div>

<!-- Sección de mensajes globales -->
<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'venta_exitosa'): ?>
    <div class="alert alert-success text-center"> ¡Venta registrada con éxito!</div>
<?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] === 'actualizado'): ?>
    <div class="alert alert-success text-center"> Egreso actualizado con éxito.</div>
<?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado'): ?>
    <div class="alert alert-warning text-center">🗑️ Egreso eliminado correctamente.</div>
<?php elseif (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
    <div class="alert alert-success text-center"> Egreso registrado con éxito.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <div class="alert alert-danger text-center"> Error al registrar el egreso.</div>
<?php endif; ?>

<!-- Opcional: Script para ocultar los mensajes automáticamente -->
<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => alert.remove());
    }, 5000); // Desaparece en 5 segundos
</script>

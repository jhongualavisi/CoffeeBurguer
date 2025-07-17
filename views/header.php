<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .custom-header {
        background: linear-gradient(to right, #ffecd2, #fcb69f);
        padding: 30px;
        margin: 20px auto;
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
</style>

<div class="container">
    <div class="custom-header d-flex justify-content-between align-items-center">
        <div>
            <div class="title">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></div>
            <div class="rol">Rol: <strong><?= htmlspecialchars($_SESSION['rol']) ?></strong></div>
        </div>
        <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo">
    </div>
</div>
 <!-- primer coambio con git -->
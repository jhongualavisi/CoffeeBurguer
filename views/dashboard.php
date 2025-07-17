<?php
session_start();

// Control de acceso
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffcc70, #ff8177);
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: auto;
            padding-top: 60px;
        }

        .logo {
            width: 120px;
        }

        .card-option {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ffffffd9;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-body i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #ff6b6b;
        }
    </style>
    <!-- Iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container dashboard-container">

    <!-- Encabezado -->
    <div class="header-row mb-4">
        <div>
            <h2 class="fw-bold">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>
            <p class="text-muted">Rol: <strong><?= htmlspecialchars($_SESSION['rol']) ?></strong></p>
        </div>
        <img src="../public/logo.jpg" alt="Coffee Burguer" class="logo rounded-circle border">
    </div>

    <!-- Mensaje de éxito -->
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'venta_exitosa'): ?>
        <div class="alert alert-success text-center">
            ✅ ¡Venta registrada con éxito!
        </div>
    <?php endif; ?>

    <!-- Opciones del panel -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

        <?php if ($_SESSION['rol'] === 'cajero'): ?>
            <div class="col">
                <a href="nueva_venta.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-cash-register"></i>
                            <h5 class="card-title">Registrar venta</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="egresos.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-money-bill-wave"></i>
                            <h5 class="card-title">Registrar egreso</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="ver_egresos.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-folder-open"></i>
                            <h5 class="card-title">Ver egresos</h5>
                        </div>
                    </div>
                </a>
            </div>

        <?php elseif ($_SESSION['rol'] === 'admin'): ?>
            <div class="col">
                <a href="nueva_venta.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-receipt"></i>
                            <h5 class="card-title">Registrar nueva venta</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="reporte_ventas.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-chart-line"></i>
                            <h5 class="card-title">Reportes de ventas</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="productos.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-hamburger"></i>
                            <h5 class="card-title">Gestión de productos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="egresos.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-credit-card"></i>
                            <h5 class="card-title">Registrar egreso</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="ver_egresos.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <h5 class="card-title">Ver y gestionar egresos</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="cuadre_caja.php" class="text-decoration-none">
                    <div class="card card-option text-center">
                        <div class="card-body">
                            <i class="fas fa-coins"></i>
                            <h5 class="card-title">Cuadre de caja</h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <div class="col">
            <a href="logout.php" class="text-decoration-none">
                <div class="card card-option text-center border-danger">
                    <div class="card-body">
                        <i class="fas fa-sign-out-alt text-danger"></i>
                        <h5 class="card-title text-danger">Cerrar sesión</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'cajero'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Egreso - Coffee Burguer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fceabb, #f8b500);
            min-height: 100vh;
        }

        .form-container {
            background-color: #fff;
            max-width: 600px;
            margin: 40px auto;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin-bottom: 25px;
            font-weight: bold;
            color: #333;
        }

        .btn-primary {
            background-color: #f0932b;
            border: none;
        }

        .btn-primary:hover {
            background-color: #eb7c00;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center"> Registrar Egreso</h2>

        <form action="../controllers/EgresoController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="accion" value="crear">

            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <input type="text" name="descripcion" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Monto ($):</label>
                <input type="number" step="0.01" name="monto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Medio de pago:</label>
                <select name="medio_pago" class="form-select" required>
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen de factura (opcional):</label>
                <input type="file" name="imagen" class="form-control">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="dashboard.php" class="btn btn-secondary">⬅ Volver al panel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>

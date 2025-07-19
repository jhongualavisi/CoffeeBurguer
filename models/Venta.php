<?php
require_once __DIR__ . '/../config/database.php';

class Venta {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    //  Obtener productos para el menú
    public function obtenerProductos() {
        $stmt = $this->conn->prepare("SELECT * FROM productos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 // Registrar un nuevo pedido con productos y descontar inventario
public function registrarPedido($usuario_id, $medio_pago, $total, $productos) {
    $this->conn->beginTransaction();

    // 1. Registrar el pedido
    $stmt = $this->conn->prepare("INSERT INTO pedidos (usuario_id, medio_pago, total) VALUES (?, ?, ?)");
    $stmt->execute([$usuario_id, $medio_pago, $total]);
    $pedido_id = $this->conn->lastInsertId();

    // 2. Registrar detalles del pedido
    $stmtDetalle = $this->conn->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");

    foreach ($productos as $producto) {
        $stmtDetalle->execute([
            $pedido_id,
            $producto['id'],
            $producto['cantidad'],
            $producto['precio']
        ]);
    }

    // 3. Descontar insumos del inventario según la receta de cada producto
    require_once __DIR__ . '/Receta.php';
    $recetaModel = new Receta();

    foreach ($productos as $producto) {
        $producto_id = $producto['id'];
        $cantidad_vendida = $producto['cantidad'];

        // Obtener receta del producto
        $receta = $recetaModel->obtenerPorProducto($producto_id);

        foreach ($receta as $componente) {
            $insumo_id = $componente['insumo_id'];
            $cantidad_usada = $componente['cantidad'] * $cantidad_vendida;

            // Descontar del inventario
            $stmtDescuento = $this->conn->prepare("UPDATE insumos SET stock_actual = stock_actual - :cantidad WHERE id = :id");
            $stmtDescuento->execute([
                ':cantidad' => $cantidad_usada,
                ':id' => $insumo_id
            ]);
        }
    }

    // Finalizar la transacción
    $this->conn->commit();
    return true;
}


    //  Reporte de productos más vendidos entre fechas
    public function reporteProductosVendidos($desde, $hasta) {
        $query = "
            SELECT 
                p.nombre,
                SUM(pd.cantidad) AS total_vendido,
                SUM(pd.cantidad * pd.precio_unitario) AS total_por_producto
            FROM pedido_detalle pd
            JOIN productos p ON pd.producto_id = p.id
            JOIN pedidos pe ON pd.pedido_id = pe.id
            WHERE DATE(pe.fecha) BETWEEN :desde AND :hasta
            GROUP BY pd.producto_id
            ORDER BY total_vendido DESC
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':desde', $desde);
        $stmt->bindParam(':hasta', $hasta);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    //  Total de dinero facturado entre fechas
    public function totalVentasPorFechas($desde, $hasta) {
        $query = "
            SELECT SUM(total) AS total_facturado
            FROM pedidos
            WHERE DATE(fecha) BETWEEN :desde AND :hasta
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':desde', $desde);
        $stmt->bindParam(':hasta', $hasta);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalPorMedioPago($medio_pago, $fecha) {
        $query = "SELECT SUM(total) AS total FROM pedidos WHERE medio_pago = :medio_pago AND DATE(fecha) = :fecha";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':medio_pago', $medio_pago);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }
    
    
}

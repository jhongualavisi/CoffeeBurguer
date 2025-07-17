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

    //  Registrar un nuevo pedido con productos
    public function registrarPedido($usuario_id, $medio_pago, $total, $productos) {
        $this->conn->beginTransaction();

        $stmt = $this->conn->prepare("INSERT INTO pedidos (usuario_id, medio_pago, total) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $medio_pago, $total]);

        $pedido_id = $this->conn->lastInsertId();

        $stmtDetalle = $this->conn->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");

        foreach ($productos as $producto) {
            $stmtDetalle->execute([
                $pedido_id,
                $producto['id'],
                $producto['cantidad'],
                $producto['precio']
            ]);
        }

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

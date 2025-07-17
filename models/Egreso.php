<?php
require_once __DIR__ . '/../config/database.php';

class Egreso {
    private $conn;
    private $table = 'egresos';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Registrar un nuevo egreso
    public function registrar($descripcion, $monto, $medio_pago, $imagen, $usuario_id) {
        $query = "INSERT INTO $this->table (descripcion, monto, medio_pago, imagen_factura, usuario_id) 
                  VALUES (:descripcion, :monto, :medio_pago, :imagen_factura, :usuario_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':medio_pago', $medio_pago);
        $stmt->bindParam(':imagen_factura', $imagen);
        $stmt->bindParam(':usuario_id', $usuario_id);

        return $stmt->execute();
    }

    // Obtener todos los egresos (para administrador)
    public function obtenerTodos() {
        $query = "SELECT e.*, u.nombre AS nombre_usuario 
                  FROM egresos e 
                  INNER JOIN usuarios u ON e.usuario_id = u.id 
                  ORDER BY e.fecha DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener egresos de un usuario específico (para cajero)
    public function obtenerPorUsuario($usuario_id) {
        $query = "SELECT e.*, u.nombre AS nombre_usuario 
                  FROM egresos e 
                  INNER JOIN usuarios u ON e.usuario_id = u.id 
                  WHERE e.usuario_id = :usuario_id 
                  ORDER BY e.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener egresos por fecha (todos o de un usuario si se pasa $usuario_id)
    public function obtenerPorFecha($fecha_inicio, $fecha_fin, $usuario_id = null) {
        if ($usuario_id) {
            $query = "SELECT e.*, u.nombre AS nombre_usuario 
                      FROM egresos e 
                      INNER JOIN usuarios u ON e.usuario_id = u.id 
                      WHERE e.fecha BETWEEN :inicio AND :fin 
                      AND e.usuario_id = :usuario_id 
                      ORDER BY e.fecha DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':inicio', $fecha_inicio);
            $stmt->bindParam(':fin', $fecha_fin);
            $stmt->bindParam(':usuario_id', $usuario_id);
        } else {
            $query = "SELECT e.*, u.nombre AS nombre_usuario 
                      FROM egresos e 
                      INNER JOIN usuarios u ON e.usuario_id = u.id 
                      WHERE e.fecha BETWEEN :inicio AND :fin 
                      ORDER BY e.fecha DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':inicio', $fecha_inicio);
            $stmt->bindParam(':fin', $fecha_fin);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un egreso por su ID (para editar)
    public function obtenerPorId($id) {
        $query = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar egreso (solo para administrador)
    public function actualizar($id, $descripcion, $monto, $medio_pago, $imagen = null) {
        if ($imagen) {
            $query = "UPDATE $this->table 
                      SET descripcion = :descripcion, monto = :monto, medio_pago = :medio_pago, imagen_factura = :imagen 
                      WHERE id = :id";
        } else {
            $query = "UPDATE $this->table 
                      SET descripcion = :descripcion, monto = :monto, medio_pago = :medio_pago 
                      WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':medio_pago', $medio_pago);
        $stmt->bindParam(':id', $id);
        if ($imagen) {
            $stmt->bindParam(':imagen', $imagen);
        }

        return $stmt->execute();
    }

    // Eliminar egreso por ID (solo administrador)
    public function eliminar($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function totalEgresosPorFecha($fecha) {
        $query = "SELECT SUM(monto) AS total FROM egresos WHERE DATE(fecha) = :fecha";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }
    
}

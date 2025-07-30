<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $stmt = $this->conn->query("SELECT * FROM productos ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos activos (para venta)
    public function obtenerActivos() {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE estado = 'activo' ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $precio) {
        $stmt = $this->conn->prepare("INSERT INTO productos (nombre, precio, estado) VALUES (?, ?, 'activo')");
        return $stmt->execute([$nombre, $precio]);
    }

    public function actualizar($id, $nombre, $precio) {
        $stmt = $this->conn->prepare("UPDATE productos SET nombre = ?, precio = ? WHERE id = ?");
        return $stmt->execute([$nombre, $precio, $id]);
    }

    // En lugar de eliminar, cambiamos el estado
    public function cambiarEstado($id, $nuevoEstado) {
        $stmt = $this->conn->prepare("UPDATE productos SET estado = ? WHERE id = ?");
        return $stmt->execute([$nuevoEstado, $id]);
    }

    // Si aún quieres eliminar físicamente un producto (NO recomendado)
    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

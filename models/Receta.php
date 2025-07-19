<?php
require_once __DIR__ . '/../config/database.php';

class Receta {
    private $conn;
    private $table = 'recetas';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerProductos() {
        return $this->conn->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerInsumos() {
        return $this->conn->query("SELECT * FROM insumos")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorProducto($producto_id) {
        $query = "SELECT r.*, i.nombre, i.unidad_medida 
                  FROM recetas r 
                  JOIN insumos i ON r.insumo_id = i.id 
                  WHERE r.producto_id = :producto_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':producto_id' => $producto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregar($producto_id, $insumo_id, $cantidad) {
        $query = "INSERT INTO recetas (producto_id, insumo_id, cantidad) 
                  VALUES (:producto_id, :insumo_id, :cantidad)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':producto_id' => $producto_id,
            ':insumo_id' => $insumo_id,
            ':cantidad' => $cantidad
        ]);
    }

    public function actualizarCantidad($producto_id, $insumo_id, $nueva_cantidad) {
        $query = "UPDATE recetas SET cantidad = :cantidad WHERE producto_id = :producto_id AND insumo_id = :insumo_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':cantidad' => $nueva_cantidad,
            ':producto_id' => $producto_id,
            ':insumo_id' => $insumo_id
        ]);
    }
    

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM recetas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

<?php
require_once __DIR__ . '/../config/database.php';

class Insumo {
    private $conn;
    private $table = 'insumos';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $unidad, $stock, $minimo) {
        $query = "INSERT INTO " . $this->table . " (nombre, unidad_medida, stock_actual, stock_minimo) 
                  VALUES (:nombre, :unidad, :stock, :minimo)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':nombre' => $nombre,
            ':unidad' => $unidad,
            ':stock' => $stock,
            ':minimo' => $minimo
        ]);
        return $stmt;
    }

    public function actualizar($id, $nombre, $unidad, $stock, $minimo) {
        $query = "UPDATE " . $this->table . " SET nombre=:nombre, unidad_medida=:unidad, stock_actual=:stock, stock_minimo=:minimo WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':nombre' => $nombre,
            ':unidad' => $unidad,
            ':stock' => $stock,
            ':minimo' => $minimo,
            ':id' => $id
        ]);
        return $stmt;
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}

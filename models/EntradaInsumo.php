<?php
require_once __DIR__ . '/../config/database.php';

class EntradaInsumo {
    private $conn;
    private $table = 'entradas_insumo';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function registrarEntrada($insumo_id, $cantidad, $usuario_id) {
        $query = "INSERT INTO $this->table (insumo_id, cantidad, usuario_id) 
                  VALUES (:insumo_id, :cantidad, :usuario_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':insumo_id' => $insumo_id,
            ':cantidad' => $cantidad,
            ':usuario_id' => $usuario_id
        ]);

        // Actualizar el stock_actual del insumo
        $update = "UPDATE insumos SET stock_actual = stock_actual + :cantidad WHERE id = :insumo_id";
        $stmt2 = $this->conn->prepare($update);
        $stmt2->execute([
            ':cantidad' => $cantidad,
            ':insumo_id' => $insumo_id
        ]);

        return true;
    }

    public function obtenerInsumos() {
        return $this->conn->query("SELECT * FROM insumos")->fetchAll(PDO::FETCH_ASSOC);
    }
}

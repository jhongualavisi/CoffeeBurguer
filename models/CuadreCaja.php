<?php
require_once __DIR__ . '/../config/database.php';

class CuadreCaja {
    private $conn;
    private $table = 'cuadre_caja';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Guardar el cuadre en la base de datos
    public function guardar($fecha, $efectivo, $transferencia, $egresos, $saldo) {
        $query = "INSERT INTO " . $this->table . " (fecha, efectivo, transferencia, egresos, saldo)
                  VALUES (:fecha, :efectivo, :transferencia, :egresos, :saldo)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':efectivo', $efectivo);
        $stmt->bindParam(':transferencia', $transferencia);
        $stmt->bindParam(':egresos', $egresos);
        $stmt->bindParam(':saldo', $saldo);
        return $stmt->execute();
    }

    // Obtener cuadre por fecha (opcional para historial)
    public function obtenerPorFecha($fecha) {
        $query = "SELECT * FROM " . $this->table . " WHERE fecha = :fecha LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar cuadre entre fechas (para exportar o reportes)
    public function listarEntreFechas($fechaInicio, $fechaFin) {
        $query = "SELECT * FROM " . $this->table . " WHERE fecha BETWEEN :inicio AND :fin ORDER BY fecha ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':inicio', $fechaInicio);
        $stmt->bindParam(':fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $conn;
    private $table = 'usuarios';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function verificar($email, $clave) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($clave, $usuario['clave'])) {
            return $usuario;
        }
        return false;
    }
    public function cambiarClave($email, $nuevaClave) {
        $claveHash = password_hash($nuevaClave, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table . " SET clave = :clave WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':clave', $claveHash);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function obtenerPorRol($rol) {
        $query = "SELECT * FROM " . $this->table . " WHERE rol = :rol LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorNombreYRol($nombre, $rol) {
        $query = "SELECT * FROM usuarios WHERE nombre LIKE :nombre AND rol = :rol LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $likeNombre = "%$nombre%";
        $stmt->bindParam(':nombre', $likeNombre);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    
}

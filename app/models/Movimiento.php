<?php
class Movimiento {
    private $conn;
    private $table = "movimientos";

    public $id;
    public $tipo;
    public $cantidad;
    public $categoria;
    public $fecha;
    public $nota;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (tipo, cantidad, categoria, fecha, nota) 
                  VALUES (:tipo, :cantidad, :categoria, :fecha, :nota)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":nota", $this->nota);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET tipo = :tipo, cantidad = :cantidad, categoria = :categoria, 
                      fecha = :fecha, nota = :nota 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":nota", $this->nota);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}

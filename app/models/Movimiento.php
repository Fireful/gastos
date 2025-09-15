<?php
class Movimiento {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }



    // Obtener todos los movimientos
    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM movimientos ORDER BY fecha ASC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }
    //Obtener suma de ingresos i gastos
    public function obtenerSumaIngresosGastos() {
        $stmt = $this->db->query("
            SELECT 
              DATE_FORMAT(fecha, '%Y-%m') AS mes,
              SUM(CASE WHEN tipo='ingreso' THEN cantidad ELSE 0 END) AS ingresos,
              SUM(CASE WHEN tipo='gasto'   THEN cantidad ELSE 0 END) AS gastos
            FROM movimientos
            GROUP BY mes
            ORDER BY mes");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function obtenerIngresos(){
        $stmt=$this->db->query("
        SELECT fecha, cantidad 
        FROM movimientos 
        WHERE tipo = 'ingreso' 
        ORDER BY fecha ASC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtenerGastos(){
        $stmt=$this->db->query("
        SELECT fecha, cantidad 
        FROM movimientos 
        WHERE tipo = 'gasto' 
        ORDER BY fecha ASC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //Obtener datos del último mes
    public function obtenerTotalesUltimoMes() {
        $stmt = $this->db->query("
            SELECT 
              SUM(CASE WHEN tipo='ingreso' THEN cantidad ELSE 0 END) AS ingresos,
              SUM(CASE WHEN tipo='gasto'   THEN cantidad ELSE 0 END) AS gastos
            FROM movimientos
            WHERE YEAR(fecha) = YEAR(CURDATE())
              AND MONTH(fecha) = MONTH(CURDATE())");
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    //Datos anuales
    public function obtenerDatosAnuales() {
        $this->db->query("SET lc_time_names = 'es_ES'");
        $stmt = $this->db->query("
            SELECT 
              MONTH(fecha) AS mes_num,
              DATE_FORMAT(fecha, '%M') AS mes_nombre,
              SUM(CASE WHEN tipo='ingreso' THEN cantidad ELSE 0 END) AS ingresos,
              SUM(CASE WHEN tipo='gasto'   THEN cantidad ELSE 0 END) AS gastos
            FROM movimientos
            WHERE YEAR(fecha) = YEAR(CURDATE())
            GROUP BY mes_num, mes_nombre
            ORDER BY mes_num");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    

    // Obtener un movimiento por ID
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM movimientos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Insertar nuevo movimiento
    public function insertar($datos) {
        $stmt = $this->db->prepare(
            "INSERT INTO movimientos (tipo, concepto, cantidad, fecha, categoria) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['tipo'],
            $datos['concepto'],
            $datos['cantidad'],
            $datos['fecha'],
            $datos['categoria']
        ]);
    }

    // Actualizar un movimiento existente
    public function actualizar($datos) {
        $stmt = $this->db->prepare(
            "UPDATE movimientos SET tipo = ?, concepto = ?, cantidad = ?, fecha = ?, categoria = ? WHERE id = ?"
        );
        $stmt->execute([
            $datos['tipo'],
            $datos['concepto'],
            $datos['cantidad'],
            $datos['fecha'],
            $datos['categoria'],
            $datos['id']
        ]);
    }

    // Eliminar movimiento
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM movimientos WHERE id = ?");
        $stmt->execute([$id]);
    }
}

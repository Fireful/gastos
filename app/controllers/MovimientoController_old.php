<?php
require_once 'app/models/Movimiento.php';

class MovimientoController {
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	// Listado de movimientos
	public function index() {
			// 1) Listado para la tabla
			$stmt = $this->db->query("SELECT * FROM movimientos ORDER BY fecha asc");
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// 2) Totales para las tarjetas
			$ingresos = 0; $gastos = 0;
			foreach ($resultados as $row) {
				if ($row['tipo'] === 'ingreso') $ingresos += (float)$row['cantidad'];
				else                           $gastos   += (float)$row['cantidad'];
			}
			$balance = $ingresos - $gastos;

			// 3) Datos agregados por mes para la gráfica
			$sql = "SELECT 
					  DATE_FORMAT(fecha, '%Y-%m') AS mes,
					  SUM(CASE WHEN tipo='ingreso' THEN cantidad ELSE 0 END) AS ingresos,
					  SUM(CASE WHEN tipo='gasto'   THEN cantidad ELSE 0 END) AS gastos
					FROM movimientos
					GROUP BY mes
					ORDER BY mes";
			$stmt2 = $this->db->query($sql);
			$agregado = $stmt2->fetchAll(PDO::FETCH_ASSOC);

			$labels = array_column($agregado, 'mes');
			$datosIngresos = array_map('floatval', array_column($agregado, 'ingresos'));
			$datosGastos   = array_map('floatval', array_column($agregado, 'gastos'));

			// 4) Totales del último mes
			$sqlUltimoMes = "SELECT 
							  SUM(CASE WHEN tipo='ingreso' THEN cantidad ELSE 0 END) AS ingresos,
							  SUM(CASE WHEN tipo='gasto'   THEN cantidad ELSE 0 END) AS gastos
							 FROM movimientos
							 WHERE YEAR(fecha) = YEAR(CURDATE())
							   AND MONTH(fecha) = MONTH(CURDATE())";

			$stmt3 = $this->db->query($sqlUltimoMes);
			$ultimoMes = $stmt3->fetch(PDO::FETCH_ASSOC);

			$ingresosMes = $ultimoMes['ingresos'] ?? 0;
			$gastosMes   = $ultimoMes['gastos'] ?? 0;

			// 5) Datos de todos los meses del año actual
			$this->db->query("SET lc_time_names = 'es_ES'");
			$sqlAnual = "SELECT 
						   MONTH(fecha) AS mes_num,
						   DATE_FORMAT(fecha, '%M') AS mes_nombre,
						   SUM(CASE WHEN tipo='ingreso' THEN cantidad ELSE 0 END) AS ingresos,
						   SUM(CASE WHEN tipo='gasto'   THEN cantidad ELSE 0 END) AS gastos
						 FROM movimientos
						 WHERE YEAR(fecha) = YEAR(CURDATE())
						 GROUP BY mes_num, mes_nombre
						 ORDER BY mes_num";

			$stmt4 = $this->db->query($sqlAnual);
			$anual = $stmt4->fetchAll(PDO::FETCH_ASSOC);

			$labelsAnual       = array_column($anual, 'mes_nombre');
			$ingresosAnualData = array_map('floatval', array_column($anual, 'ingresos'));
			$gastosAnualData   = array_map('floatval', array_column($anual, 'gastos'));


			require __DIR__ . '/../views/movimientos/index.php';
		

		$movimientos = $this->movimientoModel->obtenerTodos();
		include 'app/views/movimientos/index.php';
	}

	// Mostrar formulario de creación
	public function crear() {
		include 'app/views/movimientos/crear.php';
	}

	// Guardar nuevo movimiento
	public function guardar($datos) {
		$this->movimientoModel->insertar($datos);
		header("Location: index.php?controller=Movimiento&action=index");
	}

	// Mostrar formulario de edición
	public function editar($id) {
		$movimiento = $this->movimientoModel->obtenerPorId($id);
		if ($movimiento) {
			include 'app/views/movimientos/editar.php';
		} else {
			echo "Movimiento no encontrado";
		}
	}

	// Actualizar movimiento
	public function actualizar($datos) {
		$this->movimientoModel->actualizar($datos);
		header("Location: index.php?controller=Movimiento&action=index");
	}

	// Eliminar movimiento
	public function eliminar($id) {
		$this->movimientoModel->eliminar($id);
		header("Location: index.php?controller=Movimiento&action=index");
	}
}

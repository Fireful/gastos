<?php
require_once 'app/models/Movimiento.php';

class MovimientoController {
	private $movimientoModel;

	public function __construct($db) {
		$this->movimientoModel = new Movimiento($db);
	}

	// Listado de movimientos
	public function index() {
		// Dentro de index()
		$movimientosTodos = $this->movimientoModel->obtenerTodos();

		// Preparamos datos para el gráfico de evolución de movimientos
		$datosMovimientos = [];
		$acumulado = 0;

		foreach ($movimientosTodos as $m) {
			// Suma ingresos y resta gastos
			if ($m->tipo === 'ingreso') {
				$acumulado += (float)$m->cantidad;
			} else {
				$acumulado -= (float)$m->cantidad;
			}

			$datosMovimientos[] = [
				'x' => date("Y-m-d", strtotime($m->fecha)),
				'y' => (float)$m->cantidad,
				'tipo' => $m->tipo,
				'balance' => $acumulado
			];
		}


		$movimientos = $this->movimientoModel->obtenerTodos();
		$ingresos = 0; $gastos = 0;
		foreach ($movimientos as $row) {
			if ($row->tipo === 'ingreso') $ingresos += (float)$row->cantidad;
			else                           $gastos   += (float)$row->cantidad;
		}
		$balance = $ingresos - $gastos;

		$sumaMovimientos= $this->movimientoModel->obtenerSumaIngresosGastos();
		$labels = array_column($sumaMovimientos, 'mes');
		$datosIngresos = array_map('floatval', array_column($sumaMovimientos, 'ingresos'));
		$datosGastos   = array_map('floatval', array_column($sumaMovimientos, 'gastos'));

		$ingresosDiarios = $this->movimientoModel->obtenerIngresos();
		$ingresosAlDia = array_map(function($item) {
			return ['x' => date("Y-m-d", strtotime($item->fecha)), 'y' => (float)$item->cantidad];
		}, $ingresosDiarios);
		$gastosDiarios   = $this->movimientoModel->obtenerGastos();
		$gastosAlDia = array_map(function($item) {
			return ['x' => date("Y-m-d", strtotime($item->fecha)), 'y' => (float)$item->cantidad];
		}, $gastosDiarios);

		$totalesUltimoMes = $this->movimientoModel->obtenerTotalesUltimoMes();
		$ingresosMes = $totalesUltimoMes->ingresos ?? 0;
		$gastosMes   = $totalesUltimoMes->gastos ?? 0;


		
		// Datos anuales
		$datosAnuales = $this->movimientoModel->obtenerDatosAnuales();
		$labelsAnual = array_column($datosAnuales, 'mes_nombre');
		$datosIngresosAnual = array_map('floatval', array_column($datosAnuales, 'ingresos'));
		$datosGastosAnual   = array_map('floatval', array_column($datosAnuales, 'gastos'));

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

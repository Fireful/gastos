<?php
require_once __DIR__ . "/../models/Movimiento.php";

class MovimientoController
{
	private $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function index()
	{
		$movimiento = new Movimiento($this->db);
		$stmt = $movimiento->getAll();
		$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Calcular balance
		$ingresos = 0;
		$gastos = 0;
		foreach ($resultados as $row) {
			if ($row['tipo'] === 'ingreso') {
				$ingresos += $row['cantidad'];
			} else {
				$gastos += $row['cantidad'];
			}
		}
		$balance = $ingresos - $gastos;

		include __DIR__ . "/../views/movimientos/index.php";
	}


	public function crear()
	{
		if ($_POST) {
			$movimiento = new Movimiento($this->db);
			$movimiento->tipo = $_POST["tipo"];
			$movimiento->cantidad = $_POST["cantidad"];
			$movimiento->categoria = $_POST["categoria"];
			$movimiento->fecha = $_POST["fecha"];
			$movimiento->nota = $_POST["nota"];
			$movimiento->create();
			header("Location: index.php?controller=movimiento&action=index");
		} else {
			include __DIR__ . "/../views/movimientos/crear.php";
		}
	}

	public function editar()
	{
		$movimiento = new Movimiento($this->db);

		if ($_POST) {
			$movimiento->id = $_POST["id"];
			$movimiento->tipo = $_POST["tipo"];
			$movimiento->cantidad = $_POST["cantidad"];
			$movimiento->categoria = $_POST["categoria"];
			$movimiento->fecha = $_POST["fecha"];
			$movimiento->nota = $_POST["nota"];
			$movimiento->update();
			header("Location: index.php?controller=movimiento&action=index");
		} else {
			$id = $_GET["id"] ?? null;
			if ($id) {
				$data = $movimiento->getById($id);
				include __DIR__ . "/../views/movimientos/editar.php";
			} else {
				echo "ID no especificado";
			}
		}
	}

	public function borrar()
	{
		$id = $_GET["id"] ?? null;
		if ($id) {
			$movimiento = new Movimiento($this->db);
			$movimiento->id = $id;
			$movimiento->delete();
		}
		header("Location: index.php?controller=movimiento&action=index");
	}
}

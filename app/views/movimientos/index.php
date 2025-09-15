<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Listado de movimientos</title>
	<link rel="stylesheet" href="assets/css/estilos.css">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

	<!-- jQuery y DataTables -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

	
</head>
<body>
<div class="container">
	<h1>📊 Movimientos</h1>
	<div class="row m-2">
		<div class="col-md-4">
			<div class="card text-bg-success">
				<div class="card-body">
					<h5 class="card-title">Ingresos</h5>
					<p class="card-text fs-4">+<?= number_format($ingresos, 2) ?> €</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card text-bg-danger">
				<div class="card-body">
					<h5 class="card-title">Gastos</h5>
					<p class="card-text fs-4">-<?= number_format($gastos, 2) ?> €</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card <?= $balance >= 0 ? 'text-bg-primary' : 'text-bg-warning' ?>">
				<div class="card-body">
					<h5 class="card-title">Balance</h5>
					<p class="card-text fs-4"><?= number_format($balance, 2) ?> €</p>
				</div>
			</div>
		</div>
	</div>
		<a href="index.php?controller=Movimiento&action=crear" class="btn btn-outline-primary m-3">➕ Añadir Movimiento</a>
	<div class="card m-3">
		<div class="card-body">
			<h5 class="card-title mb-3">Evolución mensual</h5>
			<select id="tipoTotal" class="form-select w-auto">
				<option value="balance">Balance</option>
				<option value="ingresos">Ingresos</option>
				<option value="gastos">Gastos</option>
			  </select>
			<canvas id="evolucionMensual" style="max-height: 300px;"></canvas>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<div class="card m-3">
		<div class="card-body">
			<h5 class="card-title mb-3">Evolución de movimientos</h5>
			<canvas id="graficoMovimientos" style="max-height: 300px;"></canvas>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/luxon@3"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon"></script>

	<script>

		const ctxTodos = document.getElementById('graficoMovimientos').getContext('2d');

		const datosMovimientos = <?= json_encode($datosMovimientos) ?>;
		const ingresos = datosMovimientos.filter(m => m.tipo === "ingreso");
		const gastos   = datosMovimientos.filter(m => m.tipo === "gasto");

		const balance = datosMovimientos.map(m => ({
			x: m.x,
			y: m.balance
		}));

		new Chart(ctxTodos, {
			type: 'line',
			data: {
				datasets: [
					{
						label: "Ingresos",
						data: ingresos,
						borderColor: "green",
						backgroundColor: "rgba(0,128,0,0.3)",
						tension: 0.2
					},
					{
						label: "Gastos",
						data: gastos,
						borderColor: "red",
						backgroundColor: "rgba(255,0,0,0.3)",
						tension: 0.2
					},
					{
						label: 'Balance acumulado',
						data: balance,
						borderColor: 'orange',
						backgroundColor: 'transparent',
						fill: false,
						tension: 0.3,
						pointRadius: 3, // sin puntos, solo línea
						borderDash: [5, 5], 
						yAxisID: 'y'
					}
				]
			},
			options: {
				responsive: true,
				scales: {
					x: {
						type: 'category',
						labels: datosMovimientos.map(m => m.x), // solo fechas con movimientos
						title: {
							display: true,
							text: 'Fecha'
						}
					},
					y: {
						beginAtZero: true,
						title: {
							display: true,
							text: 'Cantidad (€)'
						}
					}
				}
			}

		});

	</script>

	<script>
		
		const ctx = document.getElementById('evolucionMensual').getContext('2d');
		const ingresosMensual = <?= json_encode($datosIngresos, JSON_UNESCAPED_UNICODE) ?>;
		const gastosMensual = <?= json_encode($datosGastos, JSON_UNESCAPED_UNICODE) ?>;
		const labels= <?= json_encode($labels, JSON_UNESCAPED_UNICODE) ?>;

		// Calcular balance acumulado (ingresos - gastos)
		let acumulado = 0;
		const datosBalance = ingresosMensual.map((ingreso, index) => {
			acumulado += ingreso - gastosMensual[index];
			return acumulado;
		});

		let graficoMensual=new Chart(ctx, {
			type: 'line',
			data: {
				labels: labels,
				datasets: [{
						label: 'Ingresos',
						data: ingresosMensual,
						borderColor: '#198754', // Bootstrap success
						backgroundColor: 'rgba(25,135,84,0.15)',
						tension: 0.2
					},
					{
						label: 'Gastos',
						data: gastosMensual,
						borderColor: '#dc3545', // Bootstrap danger
						backgroundColor: 'rgba(220,53,69,0.15)',
						tension: 0.2
					},
					{
						label: 'Balance',
						data: datosBalance,
						borderColor: 'rgba(255, 206, 86, 1)',
						backgroundColor: 'rgba(255, 206, 86, 0.2)',
						fill: false,
						borderDash: [5, 5], // línea discontinua para destacarlo
						tension: 0.2
					}
				]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'top'
					}
				},
				interaction: {
					mode: 'index',
					intersect: false
				},
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});

		// Detectar cambios en el select
		document.getElementById('tipoTotal').addEventListener('change', function() {
		  const tipo = this.value;
		  graficoMensual.data.datasets[0].label = tipo.charAt(0).toUpperCase() + tipo.slice(1);
		  graficoMensual.data.datasets[0].data = datosMensuales[tipo];
		  graficoMensual.update();
		});
	</script>

	<div class="row m-3">
		<!-- Gráfico circular -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title mb-3">Ingresos vs Gastos (totales)</h5>
					<canvas id="ingresosGastosPie" style="max-width:100%; max-height:300px;"></canvas>
				</div>
			</div>
		</div>

		<!-- Gráfico de barras -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mb-3">
					  <h5 class="card-title mb-0">Comparativa de ingresos y gastos</h5>
					  <select id="tipoComparativa" class="form-select w-auto">
						<option value="mes">Mes actual</option>
						<option value="anual">Todo el año</option>
					  </select>
					</div>
					<canvas id="comparativa" style="max-width:100%; height:300px;">></canvas>
				  </div>
			</div>
		</div>
	</div>


	<script>
		const ctxPie = document.getElementById('ingresosGastosPie').getContext('2d');
		new Chart(ctxPie, {
			type: 'pie',
			data: {
				labels: ['Ingresos', 'Gastos'],
				datasets: [{
					data: [<?= $ingresos ?>, <?= $gastos ?>],
					backgroundColor: ['#198754', '#dc3545'], // verde y rojo
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'top'
					}
				}
			}
		});
	</script>


	<script>
	const ctxComparativa = document.getElementById('comparativa').getContext('2d');

	// Datos PHP -> JS
	const ingresosMes = <?= $ingresosMes ?>;
	const gastosMes   = <?= $gastosMes ?>;

	const labelsAnual = <?= json_encode($labelsAnual, JSON_UNESCAPED_UNICODE) ?>;
	const ingresosAnual = <?= json_encode($datosIngresosAnual, JSON_UNESCAPED_UNICODE) ?>;
	const gastosAnual   = <?= json_encode($datosGastosAnual, JSON_UNESCAPED_UNICODE) ?>;

	// Inicial: mes actual
	let chartComparativa = new Chart(ctxComparativa, {
	  type: 'bar',
	  data: {
		labels: ['Mes actual'],
		datasets: [
		  { label: 'Ingresos', data: [ingresosMes], backgroundColor: '#198754' },
		  { label: 'Gastos',   data: [gastosMes],   backgroundColor: '#dc3545' }
		]
	  },
	  options: { responsive: true, scales: { y: { beginAtZero: true } } }
	});

	// Cambio con el select
	document.getElementById('tipoComparativa').addEventListener('change', function() {
	  if (this.value === 'mes') {
		chartComparativa.data.labels = ['Mes actual'];
		chartComparativa.data.datasets[0].data = [ingresosMes];
		chartComparativa.data.datasets[1].data = [gastosMes];
	  } else {
		chartComparativa.data.labels = labelsAnual;
		chartComparativa.data.datasets[0].data = ingresosAnual;
		chartComparativa.data.datasets[1].data = gastosAnual;
	  }
	  chartComparativa.update();
	});
	</script>



	<table id="tablaMovimientos" class="tablas">
		<thead>
			<tr>
				<th>Tipo</th>
				<th>Concepto</th>
				<th>Cantidad (€)</th>
				<th>Fecha</th>
				<th>Categoría</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($movimientos as $mov): ?>
			<tr class="<?= $mov->tipo==='ingreso' ? 'exito' : 'peligro' ?>">
				<td><?= htmlspecialchars($mov->tipo) ?></td>
				<td><?= htmlspecialchars($mov->concepto) ?></td>
				<td><?= number_format($mov->cantidad, 2, ',', '.') ?></td>
				<td><?= date("d/m/Y", strtotime($mov->fecha)) ?></td>
				<td><?= htmlspecialchars($mov->categoria) ?></td>
				<td>
					<a class="btn btn-warning me-2 mb-2" style="width: 110px;" href="index.php?controller=Movimiento&action=editar&id=<?= $mov->id ?>">✏️ Editar</a>
					<a class="btn btn-danger me-2" style="width: 110px;" href="index.php?controller=Movimiento&action=eliminar&id=<?= $mov->id ?>" onclick="return confirm('¿Seguro que quieres eliminar este movimiento?');">🗑 Eliminar</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script>
$(document).ready(function() {
	$('#tablaMovimientos').DataTable({
		order: [3, 'asc'],
		
		language: {
			"decimal": ",",
			"thousands": ".",
			"sProcessing":   "Procesando...",
			"sLengthMenu":   "Mostrar _MENU_ registros",
			"sZeroRecords":  "No se encontraron resultados",
			"sEmptyTable":   "Ningún dato disponible en esta tabla",
			"sInfo":         "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
			"sInfoEmpty":    "Mostrando registros del 0 al 0 de un total de 0",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sSearch":       "Buscar:",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			}
			
		}
	});
});
</script>
</body>
</html>

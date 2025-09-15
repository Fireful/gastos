<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Listado de Movimientos</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/estilos.css">

</head>

<body class="p-4">
	<h1>Movimientos</h1>
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

	<a href="index.php?controller=Movimiento&action=crear" class="btn btn-primary m-3">Añadir Movimiento</a>

	<div class="card m-3">
		<div class="card-body">
			<h5 class="card-title mb-3">Evolución mensual</h5>
			<canvas id="evolucionMensual" style="max-height: 300px;"></canvas>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		const ctx = document.getElementById('evolucionMensual').getContext('2d');
		new Chart(ctx, {
			type: 'line',
			data: {
				labels: <?= json_encode($labels, JSON_UNESCAPED_UNICODE) ?>,
				datasets: [{
						label: 'Ingresos',
						data: <?= json_encode($datosIngresos, JSON_UNESCAPED_UNICODE) ?>,
						borderColor: '#198754', // Bootstrap success
						backgroundColor: 'rgba(25,135,84,0.15)',
						tension: 0.2
					},
					{
						label: 'Gastos',
						data: <?= json_encode($datosGastos, JSON_UNESCAPED_UNICODE) ?>,
						borderColor: '#dc3545', // Bootstrap danger
						backgroundColor: 'rgba(220,53,69,0.15)',
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
					<canvas id="comparativa" style="max-width:100%; height:300px;"></canvas>
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
		const gastosMes = <?= $gastosMes ?>;

		const labelsAnual = <?= json_encode($labelsAnual, JSON_UNESCAPED_UNICODE) ?>;
		const ingresosAnual = <?= json_encode($ingresosAnualData, JSON_UNESCAPED_UNICODE) ?>;
		const gastosAnual = <?= json_encode($gastosAnualData, JSON_UNESCAPED_UNICODE) ?>;

		// Inicial: mes actual
		let chartComparativa = new Chart(ctxComparativa, {
			type: 'bar',
			data: {
				labels: ['Mes actual'],
				datasets: [{
						label: 'Ingresos',
						data: [ingresosMes],
						backgroundColor: '#198754'
					},
					{
						label: 'Gastos',
						data: [gastosMes],
						backgroundColor: '#dc3545'
					}
				]
			},
			options: {
				responsive: true,
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
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


	<div class="card m-3">
		<div class="card-body">
			<h5 class="card-title mb-3">Tabla de registros</h5>

			<table id="tablaMovimientos" class="tablas">
				<thead>
					<th>Tipo</th>
					<th>Cantidad</th>
					<th>Categoría</th>
					<th>Concepto</th>
					<th>Fecha</th>
					<th>Nota</th>
					<th>Acciones</th>
				</thead>

				<?php foreach ($movimientos as $row): ?>
						<tbody class="<?= $row['tipo'] === 'ingreso' ? 'exito' : 'peligro' ?>">
							<td><?= htmlspecialchars($row['tipo']) ?></td>
							<td><?= number_format($row['cantidad'], 2) ?> €</td>
							<td><?= htmlspecialchars($row['categoria']) ?></td>
							<td><?= htmlspecialchars($row['concepto']) ?></td>
							<td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
							<td><?= htmlspecialchars($row['nota']) ?></td>
							<td>
									<a class="btn" href="index.php?controller=Movimiento&action=editar&id=<?= $row->id ?>">✏️ Editar</a>
									
								<a href="index.php?controller=movimiento&action=borrar&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger btnEliminar">
									<i class="fa fa-trash"></i> Eliminar
								</a>
							</td>
						</tbody>

				<?php endforeach; ?>

			</table>
		</div>
	</div>


</body>


<script>
	$(document).ready(function() {
		$('#tablaMovimientos').DataTable({
			order: [3, 'asc'],
			dom: 'Bfrtip',
			buttons: [{
					extend: 'copyHtml5',
					text: '<i class="fa fa-copy"></i> Copiar',
					className: 'btn btn-secondary'
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i> Excel',
					className: 'btn btn-success'
				},
				{
					extend: 'csvHtml5',
					text: '<i class="fa fa-file-csv"></i> CSV',
					className: 'btn btn-info'
				},
				{
					extend: 'pdfHtml5',
					text: '<i class="fa fa-file-pdf"></i> PDF',
					className: 'btn btn-danger'
				},
				{
					extend: 'print',
					text: '<i class="fa fa-print"></i> Imprimir',
					className: 'btn btn-primary'
				}
			],
			"language": {
				"decimal": ",",
				"thousands": ".",
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sSearch": "Buscar:",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			}
		});
	});
</script>

</html>
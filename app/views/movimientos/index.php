<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Listado de Movimientos</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">
	<h1>Movimientos</h1>

	<div class="row mb-4">
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

	<a href="index.php?controller=movimiento&action=crear" class="btn btn-primary mb-3">Añadir Movimiento</a>

	<table class="table table-bordered table-striped">
		<tr>
			<th>Tipo</th>
			<th>Cantidad</th>
			<th>Categoría</th>
			<th>Fecha</th>
			<th>Nota</th>
			<th>Acciones</th>
		</tr>
		<?php foreach ($resultados as $row): ?>
			<tr class="<?= $row['tipo'] === 'ingreso' ? 'table-success' : 'table-danger' ?>">
				<td><?= htmlspecialchars($row['tipo']) ?></td>
				<td><?= number_format($row['cantidad'], 2) ?> €</td>
				<td><?= htmlspecialchars($row['categoria']) ?></td>
				<td><?= htmlspecialchars($row['fecha']) ?></td>
				<td><?= htmlspecialchars($row['nota']) ?></td>
				<td>
					<a href="index.php?controller=movimiento&action=editar&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
					<a href="index.php?controller=movimiento&action=borrar&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres borrar este movimiento?');">Borrar</a>
				</td>
			</tr>

		<?php endforeach; ?>
	</table>
</body>

</html>
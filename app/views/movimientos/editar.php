<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar movimiento</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Editar movimiento</h1>

        <form action="index.php?controller=Movimiento&action=actualizar" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($movimiento->id ?? '') ?>">

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="ingreso" <?= (isset($movimiento->tipo) && $movimiento->tipo === 'ingreso') ? 'selected' : '' ?>>Ingreso</option>
                <option value="gasto" <?= (isset($movimiento->tipo) && $movimiento->tipo === 'gasto') ? 'selected' : '' ?>>Gasto</option>
            </select>

            <label for="concepto">Concepto:</label>
            <input type="text" id="concepto" name="concepto" 
                   value="<?= htmlspecialchars($movimiento->concepto ?? '') ?>" required>

            <label for="cantidad">Cantidad (€):</label>
            <input type="number" step="0.01" id="cantidad" name="cantidad" 
                   value="<?= htmlspecialchars($movimiento->cantidad ?? '') ?>" required>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" 
                   value="<?= htmlspecialchars($movimiento->fecha ?? '') ?>" required>

            <label for="categoria">Categoría:</label>
            <input type="text" id="categoria" name="categoria" 
                   value="<?= htmlspecialchars($movimiento->categoria ?? '') ?>" required>

            <div style="text-align:center; margin-top:20px;">
                <button type="submit">💾 Guardar cambios</button>
                <a class="cancelar btn btn-sm me-1 btnCancelar" href="index.php?controller=Movimiento&action=index">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

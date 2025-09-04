<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Movimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <h1>Editar Movimiento</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="ingreso" <?= $data['tipo'] == 'ingreso' ? 'selected' : '' ?>>Ingreso</option>
                <option value="gasto" <?= $data['tipo'] == 'gasto' ? 'selected' : '' ?>>Gasto</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Cantidad</label>
            <input type="number" step="0.01" name="cantidad" value="<?= $data['cantidad'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <input type="text" name="categoria" value="<?= $data['categoria'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" value="<?= $data['fecha'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nota</label>
            <textarea name="nota" class="form-control"><?= $data['nota'] ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="index.php?controller=movimiento&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>

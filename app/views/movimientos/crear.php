<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Movimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <div class="container">
    <h1>Nuevo Movimiento</h1>
    <form method="POST" action="index.php?controller=Movimiento&action=guardar">
        
            <label >Tipo</label>
            <select name="tipo">
                <option value="ingreso">Ingreso</option>
                <option value="gasto">Gasto</option>
            </select>
        
        
            <label class="form-label">Cantidad</label>
            <input type="number" step="0.01" name="cantidad" required>
       
            <label for="categoria">Categoría</label>
            <select  id="categoria" name="categoria" required>
                <option value="General">General</option>
                <option value="Nómina">Nómina</option>
                <option value="Comida">Comida</option>
                <option value="Transporte">Transporte</option>
                <option value="Vivienda">Vivienda</option>
                <option value="Ocio">Ocio</option>
                <option value="Salud">Salud</option>
                <option value="Otros">Otros</option>
            </select>
            <label >Concepto</label>
            <input type="text" name="concepto"  required>
            <label >Fecha</label>
            <input type="date" name="fecha"  required>
            <label >Nota</label>
            <textarea name="nota" ></textarea>
        <button type="submit">Guardar</button>
        <a href="index.php?controller=movimiento&action=index" class="btn btn-secondary">Volver</a>
    </form>
    </div>
</body>
</html>

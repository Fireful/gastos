<?php
require_once __DIR__ . "/app/views/layout.php";
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/app/controllers/MovimientoController.php";

$database = new Database();
$db = $database->getConnection();

$controller = $_GET['controller'] ?? 'Movimiento';
$action = $_GET['action'] ?? 'index';

switch($controller) {
    case 'Movimiento':
        $ctrl = new MovimientoController($db);
        if (method_exists($ctrl, $action)) {
            if ($action === 'editar') {
                $id = $_GET['id'] ?? null;
                $ctrl->$action($id);
            } else if ($action === 'eliminar') {
                $id = $_GET['id'] ?? null;
                $ctrl->$action($id);
            } else if ($action === 'guardar') {
                $ctrl->$action($_POST);   // <<--- aquí se pasan los datos del formulario
            } else if ($action === 'actualizar') {
                $ctrl->$action($_POST);   // <<--- también lo mismo para actualizar
            } else {
                $ctrl->$action();
            }
        } else {
            echo "Acción no encontrada";
        }
        break;
    default:
        echo "Controlador no encontrado";
}


?>
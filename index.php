<?php
require_once __DIR__ . "/config/database.php";
require_once __DIR__ . "/app/controllers/MovimientoController.php";

$database = new Database();
$db = $database->getConnection();

$controller = $_GET['controller'] ?? 'movimiento';
$action = $_GET['action'] ?? 'index';

switch($controller) {
    case 'movimiento':
        $ctrl = new MovimientoController($db);
        if (method_exists($ctrl, $action)) {
            $ctrl->$action();
        } else {
            echo "Acción no encontrada";
        }
        break;
    default:
        echo "Controlador no encontrado";
}

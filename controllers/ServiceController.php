<?php
require_once __DIR__ . '/../config.php';

class ServiceController {
    
    
    public function getServices() {
        header('Content-Type: application/json');
        
        // Llamamos al método de tu modelo
        $services = Service::getServices();

        if ($services){
            echo json_encode([
                "status" => "success",
                "message" => "Servicios encontrados",
                "services" => $services
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error", 
                "message" => "Servicios no encontrados"
            ]);
        }
    }

    //Métodos de Leo...

}

// --- MINI ENRUTADOR API ---
// Verifica si se está llamando a este archivo por URL
if (isset($_GET['action'])) {
    
    // Instanciamos el controlador
    $controller = new ServiceController();
    $action = $_GET['action'];

    // Verificamos si el método que pide el JS realmente existe en esta clase
    if (method_exists($controller, $action)) {
        // Ejecutamos el método dinámicamente
        $controller->$action();
    } else {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
    }
}
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

    public function saveService() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            echo json_encode(["status" => "error", "message" => "No se recibieron datos"]);
            return;
        }

        try {
            $service = new Service();
            $reflector = new ReflectionClass($service);

            // 1. Manejo del ID (Sin setAccessible porque ya no hace falta en PHP 8.1+)
            if (!empty($data['id'])) {
                $propId = $reflector->getProperty('id');
                $propId->setValue($service, $data['id']);
            }

            // 2. Usamos los Setters que SÍ existen en tu modelo
            $service->setName($data['name']);
            $service->setPrice($data['price']);
            $service->setCategoryId($data['category_id']);

            // 3. Manejo de la descripción
            $propDesc = $reflector->getProperty('description');
            $propDesc->setValue($service, $data['description']);

            // 4. Guardamos usando el método del modelo
            if ($service->save()) {
                echo json_encode(["status" => "success", "message" => "Servicio guardado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al ejecutar save()"]);
            }

        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

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
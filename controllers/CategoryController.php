<?php
require_once __DIR__ . '/../config.php';

class CategoryController {
    
    public function getCategories() {
        header('Content-Type: application/json');
        
        $categories = Category::getAll();

        if ($categories) {
            echo json_encode([
                "status" => "success",
                "categories" => $categories
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "No se encontraron categorías"]);
        }
    }
}

// --- MINI ENRUTADOR API ---
if (isset($_GET['action'])) {
    $controller = new CategoryController();
    $action = $_GET['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
    }
}
?>
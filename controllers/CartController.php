<?php
require_once __DIR__ . '/../config.php';

class CartController {
    
    public function __construct() {
        // Nos aseguramos de que el carrito siempre exista como arreglo
        if (!isset($_SESSION['quote_services'])) {
            $_SESSION['quote_services'] = [];
        }
    }

    // 1. Obtener datos del carrito
    public function getCartData() {
        header('Content-Type: application/json');
        
        $cartArray = array_values($_SESSION['quote_services']);
        
        // Sumamos el total de todo el carrito
        $totalGeneral = 0;
        foreach ($cartArray as $item) {
            $totalGeneral += $item->getSubtotal();
        }
        
        // Devolvemos la estructura EXACTA que espera script.js
        echo json_encode([
            "status" => "success",
            "cart" => $cartArray,
            "total_general" => $totalGeneral
        ]);
    }

    // 2. Agregar o incrementar (Tu lógica original intacta)
    public function addToCart() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID no proporcionado"]);
            return;
        }

        if (isset($_SESSION['quote_services'][$id])) {
            $currentQuantity = $_SESSION['quote_services'][$id]->getQuantity();

            if($currentQuantity >= 10){
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Límite alcanzado. No puedes cotizar más de 10 unidades."]);
                return;
            }

            $_SESSION['quote_services'][$id]->setQuantity($currentQuantity + 1);
            $service = $_SESSION['quote_services'][$id];
            
        } else {
            $service = Service::findById($id);
            if ($service) {
                $_SESSION['quote_services'][$id] = $service;
            }
        }

        if (isset($service) && $service) {
            echo json_encode([
                "status" => "success",
                "message" => "Cantidad de {$service->getName()} actualizada: {$service->getQuantity()}",
                "total_unique_items" => count($_SESSION['quote_services'])
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Servicio no encontrado en la base de datos"]);
        }
    }

    // 3. Decrementar cantidad en el carrito
    public function decreaseQuantity() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if ($id && isset($_SESSION['quote_services'][$id])) {
            $currentQuantity = $_SESSION['quote_services'][$id]->getQuantity();
            
            // Si hay más de 1, restamos. Si es 1, lo eliminamos del carrito.
            if ($currentQuantity > 1) {
                $_SESSION['quote_services'][$id]->setQuantity($currentQuantity - 1);
            } else {
                unset($_SESSION['quote_services'][$id]);
            }
            
            echo json_encode(["status" => "success", "message" => "Cantidad decrementada"]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Servicio no encontrado en el carrito"]);
        }
    }

    // 4. Remover ítem por completo
    public function removeFromCart() {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if ($id && isset($_SESSION['quote_services'][$id])) {
            unset($_SESSION['quote_services'][$id]);
            echo json_encode(["status" => "success", "message" => "Servicio eliminado del carrito"]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "El servicio no estaba en el carrito"]);
        }
    }
}

// --- MINI ENRUTADOR API ---
if (isset($_GET['action'])) {
    $controller = new CartController();
    $action = $_GET['action'];

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
    }
}
?>
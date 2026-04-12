<?php
// controllers/QuoteController.php
require_once __DIR__ . '/../config.php';

class QuoteController {
    
    // Obtener todas las cotizaciones del usuario logueado
    public function getUserQuotes() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autorizado"]);
            return;
        }

        $quotes = Quote::getByUserId($_SESSION['user_id']);
        
        $data = [];
        foreach ($quotes as $q) {
            $data[] = [
                'codigo'  => $q->getCodigo(),
                'cliente' => $_SESSION['user_name'],
                'empresa' => $q->getEmpresa(),
                'fecha'   => $q->getFecha(),
                'total'   => $q->getTotal()
            ];
        }

        echo json_encode(["status" => "success", "quotes" => $data]);
    }

    public function getQuotes() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "No autorizado"]);
            return;
        }

        $quotes = Quote::getAll();
        
        $data = [];
        foreach ($quotes as $q) {
            $data[] = [
                'codigo'  => $q->getCodigo(),
                'empresa' => $q->getEmpresa(),
                'fecha'   => $q->getFecha(),
                'total'   => $q->getTotal()
            ];
        }

        echo json_encode(["status" => "success", "quotes" => $data]);
    }


    // Obtener el detalle de UNA cotización por su código
    public function getQuoteDetail() {
        header('Content-Type: application/json');
        
        $codigo = $_GET['codigo'] ?? null;
        
        if (!$codigo) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Código no proporcionado"]);
            return;
        }

        // Llamada al modelo
        $cotizacion = Quote::findByCodigo($codigo);

        // Verificamos si es el dueño de la cotización O si es el administrador
        $esDueño = ($cotizacion->getUserId() === $_SESSION['user_id']);
        $esAdmin = ($_SESSION['user_role'] === 'admin');

        if (!$cotizacion || (!$esDueño && !$esAdmin)) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Cotización no encontrada o no autorizada"]);
            return;
        }

        //Detalle
        $detalles = QuoteDetail::getByQuoteId($cotizacion->getId());
        
        $items = [];
        foreach ($detalles as $d) {
            $items[] = [
                'name'          => $d->getServiceName(),
                'description'   => $d->getServiceDescription(), 
                'category_name' => $d->getCategoryName(),      
                'quantity'      => $d->getQuantity(),
                'price'         => $d->getUnitPrice(),
                'subtotal'      => $d->getSubtotal()
            ];
        }

        //Respuesta
        echo json_encode([
            "status" => "success",
            "quote" => [
                'codigo'           => $cotizacion->getCodigo(),
                'cliente'          => $_SESSION['user_name'],
                'empresa'          => $cotizacion->getEmpresa(),
                'fecha_vencimiento'=> $cotizacion->getFechaVencimiento(),
                'subtotal'         => $cotizacion->getSubtotal(),
                'descuento'        => $cotizacion->getDescuento(),
                'impuesto'         => $cotizacion->getImpuesto(),
                'total'            => $cotizacion->getTotal(),
                'items'            => $items
            ]
        ]);
    }

    // Crear una nueva cotización a partir del carrito
    public function createQuote() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Debes iniciar sesión para cotizar."]);
            return;
        }

        if (empty($_SESSION['quote_services'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "El carrito está vacío."]);
            return;
        }

        // Recibir los datos del formulario (empresa)
        $input = json_decode(file_get_contents('php://input'), true);
        $empresa = $input['empresa'] ?? 'Independiente';

        try {
            $db = Database::getConnection();
            $db->beginTransaction(); // Iniciar transacción segura

            // Calcular el subtotal sumando los items de la sesión
            $subtotalCarrito = 0;
            foreach ($_SESSION['quote_services'] as $item) {
                $subtotalCarrito += $item->getSubtotal();
            }

            // Crear y guardar la Cotización Principal (Padre)
            $quote = new Quote();
            $quote->setUserId($_SESSION['user_id']);
            $quote->setEmpresa($empresa);
            $quote->setTotales($subtotalCarrito);
            
            if (!$quote->save()) {
                throw new Exception("Error al guardar la cotización principal.");
            }

            $quoteId = $quote->getId();

            // Crear y guardar los Detalles
            foreach ($_SESSION['quote_services'] as $item) {
                $detail = new QuoteDetail();
                $detail->setQuoteId($quoteId);
                $detail->setServiceId($item->getId());
                $detail->setQuantity($item->getQuantity());
                $detail->setUnitPrice($item->getPrice());
                
                if (!$detail->save()) {
                    throw new Exception("Error al guardar los detalles de la cotización.");
                }
            }

            // Si todo salió bien, vaciamos el carrito y confirmamos la base de datos
            $_SESSION['quote_services'] = [];
            $db->commit();

            echo json_encode([
                "status" => "success", 
                "message" => "Cotización generada exitosamente.",
                "codigo" => $quote->getCodigo()
            ]);

        } catch (Exception $e) {
            // Si algo falló, revertimos todos los cambios en la base de datos
            if (isset($db)) {
                $db->rollBack();
            }
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
}



// --- MINI ENRUTADOR API ---
if (isset($_GET['action'])) {
    $controller = new QuoteController();
    $action = $_GET['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
    }
}
?>
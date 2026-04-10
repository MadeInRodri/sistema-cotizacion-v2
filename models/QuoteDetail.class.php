<?php
require_once __DIR__ . '/../config.php';

class QuoteDetail {
    private $id;
    private $quote_id;
    private $service_id;
    private $quantity;
    private $unit_price;
    private $subtotal;
    private $service_name; 
    private $service_description; 
    private $category_name;   

    // Getters
    public function getId() { return $this->id; }
    public function getQuoteId() { return $this->quote_id; }
    public function getServiceId() { return $this->service_id; }
    public function getQuantity() { return $this->quantity; }
    public function getUnitPrice() { return $this->unit_price; }
    public function getSubtotal() { return $this->subtotal; }
    
    // Getters para la vista
    public function getServiceName() { return $this->service_name; }
    public function getServiceDescription() { return $this->service_description; } 
    public function getCategoryName() { return $this->category_name; }

    // Setters
    public function setQuoteId($quote_id) { $this->quote_id = $quote_id; }
    public function setServiceId($service_id) { $this->service_id = $service_id; }
    
    public function setQuantity($quantity) {
        if ($quantity < 1) throw new Exception("La cantidad debe ser al menos 1.");
        $this->quantity = $quantity;
        $this->calcularSubtotal(); // Auto-calcula al asignar cantidad
    }

    public function setUnitPrice($price) {
        if ($price < 0) throw new Exception("El precio no puede ser negativo.");
        $this->unit_price = $price;
        $this->calcularSubtotal(); // Auto-calcula al asignar precio
    }

    private function calcularSubtotal() {
        if ($this->unit_price !== null && $this->quantity !== null) {
            $this->subtotal = $this->unit_price * $this->quantity;
        }
    }

    // Métodos de BD
    public static function getByQuoteId($quoteId) {
        $db = Database::getConnection();
        
        // ACTUALIZACIÓN: Doble JOIN para traer todos los datos que la vista necesita
        $stmt = $db->prepare("
            SELECT qd.*, 
                   s.name as service_name,
                   s.description as service_description,
                   c.name as category_name
            FROM quote_details qd 
            JOIN services s ON qd.service_id = s.id 
            JOIN categories c ON s.category_id = c.id
            WHERE qd.quote_id = :quote_id
        ");
        $stmt->bindParam(':quote_id', $quoteId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'QuoteDetail');
    }

    public function save() {
        $db = Database::getConnection();
        
        if ($this->id) {
            $stmt = $db->prepare("UPDATE quote_details SET quote_id=:quote_id, service_id=:service_id, quantity=:quantity, unit_price=:unit_price, subtotal=:subtotal WHERE id=:id");
            $stmt->bindParam(':id', $this->id);
        } else {
            $stmt = $db->prepare("INSERT INTO quote_details (quote_id, service_id, quantity, unit_price, subtotal) VALUES (:quote_id, :service_id, :quantity, :unit_price, :subtotal)");
        }

        $stmt->bindParam(':quote_id', $this->quote_id);
        $stmt->bindParam(':service_id', $this->service_id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':unit_price', $this->unit_price);
        $stmt->bindParam(':subtotal', $this->subtotal);

        if ($stmt->execute()) {
            if (!$this->id) $this->id = $db->lastInsertId();
            return true;
        }
        return false;
    }
}
?>
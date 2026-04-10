<?php
// models/Quote.class.php
require_once __DIR__ . '/../config.php';

class Quote {
    private $id;
    private $codigo;
    private $user_id;
    private $empresa;
    private $subtotal;
    private $descuento;
    private $impuesto;
    private $total;
    private $fecha;
    private $fecha_vencimiento;

    // Getters
    public function getId() { return $this->id; }
    public function getCodigo() { return $this->codigo; }
    public function getUserId() { return $this->user_id; }
    public function getSubtotal() { return $this->subtotal; }
    public function getDescuento() { return $this->descuento; }
    public function getImpuesto() { return $this->impuesto; }
    public function getTotal() { return $this->total; }
    public function getFecha() { return $this->fecha; }
    public function getFechaVencimiento() { return $this->fecha_vencimiento; }
    public function getEmpresa() { return $this->empresa; }

    // Setters básicos
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function setEmpresa($empresa) { 
    $this->empresa = htmlspecialchars(strip_tags($empresa)); 
}
    
    // Método para calcular y setear todos los totales de un solo golpe
    public function setTotales($subtotal) {
        if ($subtotal < 0) throw new Exception("El subtotal no puede ser negativo.");
        
        $this->subtotal = $subtotal;
        $porcentaje = 0;
        
        if ($subtotal >= 2500) $porcentaje = 0.15;
        elseif ($subtotal >= 1000) $porcentaje = 0.10;
        elseif ($subtotal >= 500) $porcentaje = 0.05;

        $this->descuento = $this->subtotal * $porcentaje;
        $this->impuesto = ($this->subtotal - $this->descuento) * 0.13;
        $this->total = $this->subtotal - $this->descuento + $this->impuesto;
    }

    // Métodos de BD
    private function generarCodigo($db) {
        $anio = date('Y');
        $likeAnio = "COT-{$anio}-%";
        $stmt = $db->prepare("SELECT codigo FROM quotes WHERE codigo LIKE :anio ORDER BY id DESC LIMIT 1");
        $stmt->bindParam(':anio', $likeAnio);
        $stmt->execute();
        
        $ultimaCotizacion = $stmt->fetch(PDO::FETCH_ASSOC);
        $correlativo = 1;
        
        if ($ultimaCotizacion) {
            $partes = explode('-', $ultimaCotizacion['codigo']);
            $correlativo = intval($partes[2]) + 1;
        }
        return "COT-{$anio}-" . str_pad($correlativo, 4, '0', STR_PAD_LEFT);
    }

    public static function getByUserId($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM quotes WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Quote');
    }

    // Agrega esto en models/Quote.class.php
    public static function findByCodigo($codigo) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM quotes WHERE codigo = :codigo LIMIT 1");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Quote');
        return $stmt->fetch();
    }

    public function save() {
    $db = Database::getConnection();
    
    if (!$this->fecha) {
        $this->fecha = date('Y-m-d');
        $this->fecha_vencimiento = date('Y-m-d', strtotime('+7 days'));
        $this->codigo = $this->generarCodigo($db);
    }

    if ($this->id) {
        // UPDATE modificado
        $stmt = $db->prepare("UPDATE quotes SET user_id=:user_id, empresa=:empresa, subtotal=:subtotal, descuento=:descuento, impuesto=:impuesto, total=:total WHERE id=:id");
        $stmt->bindParam(':id', $this->id);
    } else {
        // INSERT modificado
        $stmt = $db->prepare("INSERT INTO quotes (codigo, user_id, empresa, subtotal, descuento, impuesto, total, fecha, fecha_vencimiento) VALUES (:codigo, :user_id, :empresa, :subtotal, :descuento, :impuesto, :total, :fecha, :fecha_vencimiento)");
        $stmt->bindParam(':codigo', $this->codigo);
        $stmt->bindParam(':fecha', $this->fecha);
        $stmt->bindParam(':fecha_vencimiento', $this->fecha_vencimiento);
    }

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':empresa', $this->empresa); // <-- NUEVO BIND
        $stmt->bindParam(':subtotal', $this->subtotal);
        $stmt->bindParam(':descuento', $this->descuento);
        $stmt->bindParam(':impuesto', $this->impuesto);
        $stmt->bindParam(':total', $this->total);
        
        if ($stmt->execute()) {
            if (!$this->id) $this->id = $db->lastInsertId();
            return true;
        }
        return false;
    }
}
?>
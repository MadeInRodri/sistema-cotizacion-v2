<?php
require_once __DIR__ . '/../config.php';

class Service implements JsonSerializable {
    // Propiedades 
    private $id;
    private $name;
    private $description;
    private $price;
    private $category_id;
    private $category_name;
    private $quantity = 1;

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getCategoryId() { return $this->category_id; }
    public function getCategoryName() { return $this->category_name; }
    public function getQuantity() { return $this->quantity; }
    public function getSubtotal() { return $this->price * $this->quantity; }

    // Setters con Validación
    public function setName($name) {
        if (empty(trim($name))) {
            throw new Exception("El nombre del servicio no puede estar vacío.");
        }
        $this->name = htmlspecialchars(strip_tags($name)); // Sanitización básica
    }
    public function setQuantity($quantity) { 
        $this->quantity = $quantity; 
    }

    public function setPrice($price) {
        if (!is_numeric($price) || $price < 0) {
            throw new Exception("El precio debe ser un número positivo.");
        }
        $this->price = $price;
    }

    public function setCategoryId($category_id) {
        if (!is_numeric($category_id) || $category_id <= 0) throw new Exception("ID de categoría inválido.");
        $this->category_id = $category_id;
    }

    // Métodos de Base de Datos
    
    public static function getServices() {
        $db = Database::getConnection();

        $stmt = $db->query("
            SELECT s.*, c.name AS category_name 
            FROM services s 
            JOIN categories c ON s.category_id = c.id
        ");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Service');
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT s.*, c.name AS category_name 
            FROM services s 
            JOIN categories c ON s.category_id = c.id 
            WHERE s.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Service');
        return $stmt->fetch();
    }

    //  Método para guardar 
    public function save() {
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE services SET name=:name, description=:description, price=:price, category_id=:category_id WHERE id=:id");
            $stmt->bindParam(':id', $this->id);
        } else {
            $stmt = $db->prepare("INSERT INTO services (name, description, price, category_id) VALUES (:name, :description, :price, :category_id)");
        }

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category_id', $this->category_id);

        return $stmt->execute();
    }

    // Eliminar (Solo Admin)
    public function delete($id) {
        $db = Database::getConnection();

        $stmt = $db->prepare("DELETE FROM services WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function jsonSerialize(): mixed {
        return [
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'description'   => $this->getDescription(),
            'price'         => $this->getPrice(),
            'category_id'   => $this->getCategoryId(),
            'category_name' => $this->getCategoryName(),
            'quantity'      => $this->getQuantity(),
            'subtotal'      => $this->getSubtotal()
        ];
    }
}
?>
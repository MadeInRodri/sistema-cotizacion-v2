<?php
require_once __DIR__ . '/../config.php';

class Category implements JsonSerializable {
    private $id;
    private $name;

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }

    // Setters
    public function setName($name) {
        if (empty(trim($name))) throw new Exception("El nombre de la categoría no puede estar vacío.");
        $this->name = htmlspecialchars(strip_tags($name));
    }

    // Métodos BD
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Category');
    }

    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Category');
        return $stmt->fetch();
    }

    public function save() {
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE categories SET name = :name WHERE id = :id");
            $stmt->bindParam(':id', $this->id);
        } else {
            $stmt = $db->prepare("INSERT INTO categories (name) VALUES (:name)");
        }
        $stmt->bindParam(':name', $this->name);
        
        if ($stmt->execute()) {
            if (!$this->id) $this->id = $db->lastInsertId();
            return true;
        }
        return false;
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
?>
<?php
require_once __DIR__ . '/../config.php';

class User {

    //Propiedades
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $created_at;

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters con validación
    public function setName($name) {
        if (empty(trim($name))) throw new Exception("El nombre es obligatorio.");
        $this->name = htmlspecialchars(strip_tags($name));
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo no es válido.");
        }
        $this->email = strtolower(trim($email));
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setRole($role) {
        if (!in_array($role, ['admin', 'user'])) {
            throw new Exception("Rol no válido.");
        }
        $this->role = $role;
    }

    // Métodos de BD

    //Encontrar usuario por email
    public static function findByEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }


    //Encontrar usuario por ID
    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }


    //Crear usuario
    public function save() {
        $db = Database::getConnection();
        if ($this->id) {
            $stmt = $db->prepare("UPDATE users SET name=:name, email=:email, password=:password, role=:role WHERE id=:id");
            $stmt->bindParam(':id', $this->id);
        } else {
            $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        }
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        
        if ($stmt->execute()) {
            if (!$this->id) $this->id = $db->lastInsertId();
            return true;
        }
        return false;
    }
}
?>
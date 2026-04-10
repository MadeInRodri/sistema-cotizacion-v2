<?php
// controllers/AuthController.php
require_once __DIR__ . '/../config.php';

class AuthController {

    // Mostrar la vista de Login
    public function showLogin() {
        require_once ROOT_DIR . '/views/auth.php';
    }

    // Mostrar la vista de Registro
    public function showRegister() {
        require_once ROOT_DIR . '/views/register.php';
    }

    // Procesar el formulario de Login
    public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Guardamos datos en sesión
            $_SESSION['user_id']   = $user->getId();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_role'] = $user->getRole(); 
            
            // REDIRECCIÓN SEGÚN ROL
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ../views/services.php');
            } else {
                header('Location: ../views/cart.php');
            }
            exit;
        } else {
            header('Location: AuthController.php?action=showLogin&error=1');
            exit;
        }
    }
}

    // Procesar el formulario de Registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user = new User();
                $user->setName($_POST['name']);
                $user->setEmail($_POST['email']);
                // Encriptamos la contraseña antes de mandarla al modelo
                $user->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));
                $user->setRole('user'); // Por defecto, rol de usuario común

                if ($user->save()) {
                    // Si se guardó, lo mandamos al login
                    header('Location: AuthController.php?action=showLogin&success=1');
                    exit;
                }
            } catch (Exception $e) {
                header('Location: AuthController.php?action=showRegister&error=1');
                exit;
            }
        }
    }

    // Cerrar sesión
    public function logout() {
        session_destroy();
        header('Location: AuthController.php?action=showLogin');
        exit;
    }
}

// --- MINI ENRUTADOR ---
if (isset($_GET['action'])) {
    $controller = new AuthController();
    $action = $_GET['action'];

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Acción no válida";
    }
} else {
    // Si entran directo al archivo, mostramos el login por defecto
    $controller = new AuthController();
    $controller->showLogin();
}
?>
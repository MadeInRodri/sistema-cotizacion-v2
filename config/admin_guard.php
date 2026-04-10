<?php
require_once __DIR__ . '/../config.php';

// Verificamos primero si está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../controllers/AuthController.php?action=showLogin');
    exit();
}

// Verificamos si tiene el rol de admin
if ($_SESSION['user_role'] !== 'admin') {
    // Si es un usuario común intentando entrar al panel admin,
    // lo mandamos a su sección permitida (carrito) con un aviso.
    header('Location: ../views/cart.php?error=unauthorized');
    exit();
}
?>
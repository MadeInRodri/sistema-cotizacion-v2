<?php
// index.php
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    // Si no hay sesión, al login
    header("Location: controllers/AuthController.php?action=showLogin");
} else {
    // Si hay sesión, verificamos el rol
    if ($_SESSION['user_role'] === 'admin') {

        // Los administradores van al panel de gestión de servicios
        header("Location: ../views/services.php");
    } else {
        
        // Los usuarios comunes van directo a cotizar (carrito)
        header("Location: ../views/cart.php");
    }
}
exit();
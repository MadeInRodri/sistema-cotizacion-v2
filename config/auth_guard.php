<?php
require_once __DIR__ . '/../config.php';

// Si NO hay un usuario en sesión, de vuelta al login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../controllers/AuthController.php?action=showLogin');
    exit();
}
?>
<?php
// config.php

// 1. PRIMERO definimos la raíz y llamamos al autoloader
define('ROOT_DIR', __DIR__); 
require_once ROOT_DIR . '/autoload.php';

// 2. DESPUÉS iniciamos la sesión
// De esta forma, cuando PHP intente "despertar" los datos del carrito, 
// el autoloader ya le habrá enseñado qué es la clase Service.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
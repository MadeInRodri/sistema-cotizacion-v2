<?php
// autoload.php

spl_autoload_register(function ($class_name) {
    // 1. Definimos en qué carpetas están nuestras clases (Modelos, Controladores y Configuración)
    $directorios = [
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
        __DIR__ . '/config/'
    ];

    // 2. Definimos las posibles extensiones que estás usando
    $extensiones = ['.php', '.class.php'];

    // 3. Buscamos el archivo en cada directorio
    foreach ($directorios as $directorio) {
        foreach ($extensiones as $extension) {
            $ruta_archivo = $directorio . $class_name . $extension;

            // Si el archivo existe, lo incluimos y detenemos la búsqueda
            if (file_exists($ruta_archivo)) {
                require_once $ruta_archivo;
                return;
            }
        }
    }
});
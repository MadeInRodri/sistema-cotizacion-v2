<?php
class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            // Aquí pones tus credenciales
            self::$connection = new PDO("mysql:host=localhost;dbname=sistema_cotizacion", "root", "");
        }
        return self::$connection;
    }
}
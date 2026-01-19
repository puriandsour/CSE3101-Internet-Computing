<?php
// config/Database.php

class Database
{
    // Default XAMPP credentials
    private static $host = 'localhost';
    private static $db_name = 'school_app';
    private static $username = 'root';
    private static $password = 'password'; // Empty for default XAMPP (change if you set a password) // remove if using xampp I am using direct ubuntu so remove if using xampp. 
    private static $connection = null;

    public static function connect()
    {
        if (!self::$connection) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name,
                    self::$username,
                    self::$password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}

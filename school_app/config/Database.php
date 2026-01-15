<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private static $host = 'localhost';
    private static $db_name = 'school_app';
    private static $username = 'root';
    private static $password = '';
    private static $connection = null;

    public static function connect() {
        if (!self::$connection) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name,
                    self::$username,
                    self::$password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed");
            }
        }
        return self::$connection;
    }
}

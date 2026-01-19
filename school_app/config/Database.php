<?php
// config/Database.php

class Database
{
    // Use environment variables (preferred for Docker/Railway) or fallback to defaults
    // uncomment if you are developing locally and ready to submit project and remove prod creds before submitting
    /*
    private static $host     = 'localhost';
    private static $db_name  = 'school_app';
    private static $username = 'root';
    private static $password = '';
    */

    private static $host = 'nozomi.proxy.rlwy.net:13165';
    private static $db_name = 'railway';
    private static $username = 'root';
    private static $password = 'GnsMDFykCMBCOuviaulnNKvBUoBLMVmc';
    private static $connection = null;

    public static function connect()
    {
        if (!self::$connection) {
            $host = getenv('DB_HOST') ?: self::$host;
            $db = getenv('DB_NAME') ?: self::$db_name;
            $user = getenv('DB_USER') ?: self::$username;
            $pass = getenv('DB_PASS') ?: self::$password;

            try {
                self::$connection = new PDO(
                    "mysql:host=" . $host . ";dbname=" . $db,
                    $user,
                    $pass
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

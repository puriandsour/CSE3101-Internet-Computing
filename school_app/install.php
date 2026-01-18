<?php
/**
 * Database Installation Script
 * Run this file once to set up the database and tables.
 */

$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password
$dbname = 'school_app';

echo "<h1>School Management System Installation</h1>";

try {
    // 1. Connect to MySQL Server (no DB selected)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p>Connected to MySQL server successfully.</p>";

    // 2. Create Database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>Database <strong>$dbname</strong> checked/created.</p>";

    // 3. Select the Database
    $pdo->exec("USE `$dbname`");

    // 4. Read content from schema.sql
    $schemaFile = __DIR__ . '/../schema.sql';
    if (!file_exists($schemaFile)) {
        die("<p style='color:red'>Error: schema.sql not found at $schemaFile</p>");
    }

    $sql = file_get_contents($schemaFile);

    // 5. Execute Schema (Split by semicolon to handle multiple statements if needed, 
    // but PDO can sometimes handle it in one go depending on driver. 
    // Safer to just run it generically or let PDO handle multiple statements if emulated prepares are on)

    // Enabling multiple statements
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);

    $pdo->exec($sql);

    echo "<p style='color:green'><strong>Success!</strong> Database schema imported.</p>";
    echo "<p>You can now <a href='index.php'>Go to Home</a></p>";

} catch (PDOException $e) {
    die("<p style='color:red'>DB Error: " . $e->getMessage() . "</p>");
}

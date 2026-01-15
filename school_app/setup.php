<?php
require_once 'config/Database.php';

try {
    $db = \Config\Database::connect();

    #create database if not exists 
    $db->exec("CREATE DATABASE IF NOT EXISTS school_app");
    $db->exec("USE school_app");

    #create grades table
    $db->exec("
    CREATE TABLE IF NOT EXISTS grades (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL UNIQUE
    );
    ");

    #create classes table
    $db->exec("
    CREATE TABLE IF NOT EXISTS classes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL UNIQUE,
        grade_id INT NOT NULL,
        FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE CASCADE
    );
    ");

    #create students table
    $db->exec("
    CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50),
        last_name VARCHAR(50),
        class_id INT NOT NULL,
        FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
    );
    ");

    #create subjects table
    $db->exec("
    CREATE TABLE IF NOT EXISTS subjects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        grade_id INT NOT NULL,
        FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE CASCADE
    );
    ");

    #create school_years table
    $db->exec("
    CREATE TABLE IF NOT EXISTS school_years (
        id INT AUTO_INCREMENT PRIMARY KEY,
        year VARCHAR(20) NOT NULL
    );
    ");

    #create terms table
    $db->exec("
    CREATE TABLE IF NOT EXISTS terms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        term_number INT NOT NULL,
        school_year_id INT NOT NULL,
        FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE
    );
    ");

    # create scores table
    $db->exec("
    CREATE TABLE IF NOT EXISTS scores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        subject_id INT NOT NULL,
        term_id INT NOT NULL,
        score DECIMAL(5,2) NOT NULL,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
        FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE CASCADE
    );
    ");

    #create users table
    $db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        role ENUM('admin','teacher','office') NOT NULL,
        password_hash VARCHAR(255) NOT NULL
    );
    ");

    # Insert default admin if not exists
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@school.com']);
    if (!$stmt->fetch()) {
        $passwordHash = password_hash('12345678', PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO users (name, email, role, password_hash) VALUES (?, ?, ?, ?)")
           ->execute(['Admin', 'admin@school.com', 'admin', $passwordHash]);
    }

    # Insert default grades if not exists
    $defaultGrades = ['Grade One','Grade Two','Grade Three','Grade Four','Grade Five','Grade Six'];
    foreach ($defaultGrades as $gradeName) {
        $stmt = $db->prepare("SELECT * FROM grades WHERE name = ?");
        $stmt->execute([$gradeName]);
        if (!$stmt->fetch()) {
            $db->prepare("INSERT INTO grades (name) VALUES (?)")->execute([$gradeName]);
        }
    }

    echo "<h2>Setup Complete!</h2>";
    echo "<p>All tables are created.</p>";
    echo "<p>Default admin: <b>admin@school.com</b> / <b>12345678</b></p>";
    echo "<p>Default grades: Grade One → Grade Six</p>";
    echo "<p>You can now use the app.</p>";

} catch (PDOException $e) {
    echo "Error setting up database: " . $e->getMessage();
}

<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    
    public function index() {
        // List all users (teachers page)
    }
    
    public function add() {
        // Just show the form
    }
    
    public function create($data) {
        if (empty($data['username']) || empty($data['password']) || empty($data['email']) || empty($data['role_id'])) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: index.php?controller=user&action=add");
            exit;
        }
        
        $db = Database::connect();
        
        // Check if username exists
        $checkStmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $checkStmt->execute([$data['username']]);
        if ($checkStmt->fetch()) {
            $_SESSION['error'] = "Username already exists.";
            header("Location: index.php?controller=user&action=add");
            exit;
        }
        
        // Check if email exists
        $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->execute([$data['email']]);
        if ($checkStmt->fetch()) {
            $_SESSION['error'] = "Email already exists.";
            header("Location: index.php?controller=user&action=add");
            exit;
        }
        
        // Create user
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password, first_name, last_name, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, 1, NOW())
        ");
        
        $stmt->execute([
            $data['username'],
            $data['email'],
            $hashedPassword,
            $data['first_name'],
            $data['last_name']
        ]);
        
        $userId = $db->lastInsertId();
        
        // Assign role
        $roleStmt = $db->prepare("
            INSERT INTO user_roles (user_id, role_id, created_at)
            VALUES (?, ?, NOW())
        ");
        $roleStmt->execute([$userId, $data['role_id']]);
        
        $_SESSION['success'] = "User created successfully!";
        header("Location: index.php?controller=user");
        exit;
    }
}

<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';

class UserController
{
    public function index()
    {
        $users = User::getAll();
        render_view('views/admin/users/index.php', ['users' => $users]);
    }

    public function add()
    {
        $roles = Role::getAll();
        render_view('views/admin/users/add.php', ['roles' => $roles]);
    }

    public function create($data)
    {
        $userModel = new User();

        // Find role ID by name from POST
        $roleName = $data['role'] ?? '';
        $role = Role::findByName($roleName);

        if (!$role) {
            $_SESSION['error'] = "Invalid role selected.";
            header("Location: index.php?controller=user&action=add");
            exit;
        }

        $userData = [
            'username' => htmlspecialchars($data['username']),
            'email' => htmlspecialchars($data['email']),
            'password_hash' => password_hash('welcome123', PASSWORD_DEFAULT), // Default password
            'first_name' => htmlspecialchars($data['first_name']),
            'last_name' => htmlspecialchars($data['last_name']),
            'is_active' => isset($data['is_active']) ? 1 : 0
        ];

        // Ensure we're using correct array format for the model
        if ($userModel->save(array_values($userData), $role->id)) {
            $_SESSION['success'] = "User created successfully. Default password is 'welcome123'.";
            header("Location: index.php?controller=user&action=index");
            exit;
        } else {
            // Error message is already set in User::save session usually, 
            // but let's make sure it doesn't just hang.
            header("Location: index.php?controller=user&action=add");
            exit;
        }
    }
}

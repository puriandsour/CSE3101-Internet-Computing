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

    public function edit()
    {
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "User not found.";
            header("Location: index.php?controller=user");
            exit;
        }

        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: index.php?controller=user");
            exit;
        }

        $roles = Role::getAll();
        render_view('views/admin/teachers/edit.php', ['user' => $user, 'roles' => $roles]);
    }

    public function update()
    {
        $userId = $_POST['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "User not found.";
            header("Location: index.php?controller=user");
            exit;
        }

        $db = Database::connect();

        // Update user data
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, is_active = ?";
        $params = [
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            isset($_POST['is_active']) ? 1 : 0
        ];

        // Update password if provided
        if (!empty($_POST['password'])) {
            $sql .= ", password_hash = ?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        // Update role
        if (!empty($_POST['role'])) {
            $role = Role::findByName($_POST['role']);
            if ($role) {
                // Delete old role assignment
                $db->prepare("DELETE FROM user_roles WHERE user_id = ?")->execute([$userId]);
                // Add new role
                $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)")->execute([$userId, $role->id]);
            }
        }

        $_SESSION['success'] = "User updated successfully!";
        header("Location: index.php?controller=user");
        exit;
    }

    public function delete()
    {
        $userId = $_GET['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "User not found.";
            header("Location: index.php?controller=user");
            exit;
        }

        // Don't allow deleting yourself
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete your own account!";
            header("Location: index.php?controller=user");
            exit;
        }

        $db = Database::connect();

        // Soft delete - just deactivate
        $stmt = $db->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
        $stmt->execute([$userId]);

        $_SESSION['success'] = "User deleted successfully!";
        header("Location: index.php?controller=user");
        exit;
    }
}

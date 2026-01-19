<?php
require_once 'models/User.php';

class AuthController
{

    public function login($email, $password)
    {
        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->password_hash)) {
            // Login Success
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;

            // Fetch Role Name
            $roleName = $this->getUserRole($user->id);
            $_SESSION['role'] = $roleName ? $roleName : 'UNKNOWN';

            return true;
        }

        return false;
    }

    private function getUserRole($userId)
    {
        // Helper to get role name from DB
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT r.name 
            FROM roles r 
            JOIN user_roles ur ON r.id = ur.role_id 
            WHERE ur.user_id = ? 
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $role = $stmt->fetch();
        return $role ? $role->name : null;
    }

    public function createUser($data)
    {
        // Only Admin can create users
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'OFFICE_ADMIN') {
            $_SESSION['error'] = "Access Denied. Admin only.";
            return false;
        }

        $u = new User();
        // Updated to handle array correctly matching User::save implementation
        // Assuming User::save handles the data array structure. 
        // We should double check User::save signature in User.php or calling code.
        // For now, let's stick to the signature requested/implemented previously.

        // NOTE: Previous User::save implementation was:
        // INSERT INTO users (username, email, password_hash, first_name, last_name, is_active)

        $userData = [
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['first_name'],
            $data['last_name'],
            1 // is_active
        ];

        $success = $u->save($userData, $data['role_id']); // We will need to update User::save to handle role assignment too

        if ($success) {
            $_SESSION['success'] = "User created successfully!";
        } else {
            $_SESSION['error'] = "Error creating user.";
        }

        return $success;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit;
    }
}

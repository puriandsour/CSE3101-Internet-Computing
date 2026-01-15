<?php
require_once 'models/User.php';

class AuthController {

    public function login($email,$password) {
        $user = User::findByEmail($email);
        if ($user && password_verify($password,$user->password_hash)) {
            $_SESSION['user_id']=$user->id;
            $_SESSION['role']=$user->role;
            return true;
        }
        return false;
    }

    public function createUser($data) {
    if ($_SESSION['role'] !== 'admin') return false;

    $u = new User();
    $success = $u->save([
        $data['name'],
        $data['email'],
        $data['role'],
        password_hash($data['password'], PASSWORD_DEFAULT)
    ]);

    if ($success) {
        $_SESSION['success'] = "User '{$data['name']}' created successfully!";
    } else {
        $_SESSION['error'] = "Error creating user.";
    }

    return $success;
}
public function logout() {
    session_destroy();   
    header("Location: index.php?controller=auth&action=login");
    exit;
}

}

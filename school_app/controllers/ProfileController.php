<?php
require_once 'models/User.php';

class ProfileController
{
    /**
     * Display current user profile
     */
    public function index()
    {
        $userId = $_SESSION['user_id'];
        $user = User::findById($userId);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header("Location: index.php");
            exit;
        }

        render_view('views/shared/profile.php', [
            'user' => $user
        ]);
    }

    /**
     * Show change password form
     */
    public function changePasswordForm()
    {
        render_view('views/shared/reset_password.php');
    }

    /**
     * Handle password update logic
     */
    public function updatePassword($data)
    {
        $userId = $_SESSION['user_id'];
        $currentPassword = $data['current_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        // 1. Basic Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: index.php?controller=profile&action=changePassword");
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "New passwords do not match.";
            header("Location: index.php?controller=profile&action=changePassword");
            exit;
        }

        if (strlen($newPassword) < 8) {
            $_SESSION['error'] = "Password must be at least 8 characters long.";
            header("Location: index.php?controller=profile&action=changePassword");
            exit;
        }

        // 2. Verify Current Password
        $userModel = new User();
        $user = User::findById($userId);

        if (!password_verify($currentPassword, $user->password_hash)) {
            $_SESSION['error'] = "Incorrect current password.";
            header("Location: index.php?controller=profile&action=changePassword");
            exit;
        }

        // 3. Update Password
        if ($userModel->updatePassword($userId, $newPassword)) {
            $_SESSION['success'] = "Password updated successfully.";
            header("Location: index.php?controller=profile&action=index");
            exit;
        } else {
            $_SESSION['error'] = "Failed to update password.";
            header("Location: index.php?controller=profile&action=changePassword");
            exit;
        }
    }
}

<?php

class AuthMiddleware
{

    public static function isAuthenticated()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    public static function isAdmin()
    {
        self::isAuthenticated();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'OFFICE_ADMIN') {
            die("Access Denied: You do not have permission to view this page.");
        }
    }

    public static function isTeacher()
    {
        self::isAuthenticated();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'TEACHER') {
            die("Access Denied: Teachers only.");
        }
    }
}

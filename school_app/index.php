<?php
session_start();

// Core & Config
require_once 'config/Database.php';
require_once 'core/AuthMiddleware.php';

// Controllers
require_once 'controllers/AuthController.php';
require_once 'controllers/StudentController.php';
require_once 'controllers/ScoreController.php';
require_once 'controllers/ReportController.php';

// Routing
$c = $_GET['controller'] ?? 'auth';
$a = $_GET['action'] ?? 'login';

// --- AUTHORIZATION ROUTES ---

if ($c === 'auth') {
    $auth = new AuthController();

    if ($a === 'login') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($auth->login($_POST['email'], $_POST['password'])) {
                header("Location: index.php?controller=dashboard");
                exit;
            } else {
                $_SESSION['error'] = "Invalid credentials";
                header("Location: index.php?controller=auth&action=login");
                exit;
            }
        } else {
            require 'views/auth/login.php';
        }
    } elseif ($a === 'logout') {
        $auth->logout();
    } elseif ($a === 'create_user') {
        AuthMiddleware::isAdmin(); // Protect Route
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->createUser($_POST);
            header("Location: index.php?controller=dashboard"); // Or back to user list
            exit;
        } else {
            require 'views/auth/create_user.php';
        }
    }
}

// --- PROTECTED ROUTES (Require Login) ---
elseif ($c === 'dashboard') {
    AuthMiddleware::isAuthenticated();
    require 'views/dashboard.php';
} elseif ($c === 'student') {
    AuthMiddleware::isAdmin(); // Only Admin manages students
    $s = new StudentController();

    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($s->add($_POST)) {
            $_SESSION['success'] = "Student added successfully!";
            header("Location: index.php?controller=dashboard");
            exit;
        } else {
            $_SESSION['error'] = "Error adding student.";
            require 'views/students/add.php';
        }
    } else {
        require 'views/students/add.php';
    }
} elseif ($c === 'score') {
    AuthMiddleware::isTeacher(); // Only Teachers manage scores
    $sc = new ScoreController();
    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $sc->add($_POST);
        $_SESSION['success'] = "Score added successfully!";
        header("Location: index.php?controller=dashboard");
        exit;
    } else {
        require 'views/scores/add.php';
    }
} elseif ($c === 'subject' || $c === 'class' || $c === 'schoolYear' || $c === 'term') {
    AuthMiddleware::isAdmin();

    // Dynamic controller loading for Admin CRUDs
    $controllerName = ucfirst($c) . 'Controller';
    require_once "controllers/$controllerName.php";
    $controller = new $controllerName();

    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->add($_POST);
        $_SESSION['success'] = "$c added successfully!";
        header("Location: index.php?controller=dashboard");
        exit;
    } else {
        // e.g. views/subjects/add.php
        require "views/{$c}s/add.php";
    }
} elseif ($c === 'report') {
    AuthMiddleware::isAuthenticated(); // Teacher or Admin can view reports?
    // Requirements say: Teacher: manage scores, View reports seems implied or explicit?
    // "Teacher (manage scores only)" - maybe they can't see full reports?
    // Let's assume Teachers can VIEW reports of their students/subjects.
    // Office Admin manage all other functionalities.

    $r = new ReportController();

    if ($a === 'view' && !empty($_GET['student']) && !empty($_GET['term'])) {
        $data = $r->studentReport($_GET['student'], $_GET['term']);
        require 'views/report/report_card.php';
    } else {
        require 'views/report/select_student_term.php';
    }
} else {
    // 404
    echo "Page not found.";
}

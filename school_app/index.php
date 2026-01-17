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

// --- VIEW HELPER ---
function render_view($viewPath, $data = [])
{
    extract($data);
    require 'views/layout/header.php';
    require $viewPath;
    require 'views/layout/footer.php';
}

// --- ROUTING LOGIC ---
$c = $_GET['controller'] ?? null;
$a = $_GET['action'] ?? 'index';

// 1. Handle Unauthenticated Users
if (!isset($_SESSION['user_id'])) {
    if ($c === 'auth' && $a === 'login') {
        $auth = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($auth->login($_POST['email'], $_POST['password'])) {
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: index.php");
                exit;
            }
        }
        require 'views/auth/login.php';
        exit;
    }
    require 'views/auth/login.php';
    exit;
}

// 2. Handle Authenticated Users
$role = $_SESSION['role'];

if ($c === null) {
    if ($role === 'OFFICE_ADMIN')
        header("Location: index.php?controller=admin&action=dashboard");
    else
        header("Location: index.php?controller=teacher&action=dashboard");
    exit;
}

if ($c === 'auth' && $a === 'logout') {
    (new AuthController())->logout();
}

// 3. Role-Based Routing
if ($role === 'OFFICE_ADMIN') {
    switch ($c) {
        case 'admin':
            if ($a === 'dashboard')
                render_view('views/admin/dashboard.php');
            break;
        case 'user':
            if ($a === 'add')
                render_view('views/admin/users/add.php');
            else
                render_view('views/admin/users/index.php');
            break;
        case 'student':
            if ($a === 'add')
                render_view('views/admin/students/add.php');
            elseif ($a === 'enroll')
                render_view('views/admin/students/enroll.php');
            else
                render_view('views/admin/students/index.php');
            break;
        case 'class':
            render_view('views/admin/academic/classes.php');
            break;
        case 'subject':
            render_view('views/admin/academic/subjects.php');
            break;
        case 'schoolYear':
            render_view('views/admin/academic/schoolYears.php');
            break;
        case 'term':
            render_view('views/admin/academic/terms.php');
            break;
        case 'settings':
            render_view('views/shared/settings.php');
            break;
        case 'quickactions':
            render_view('views/admin/quickActions.php');
            break;
        case 'profile':
            render_view('views/shared/profile.php');
            break;
        case 'notifications':
            render_view('views/shared/notifications.php');
            break;
        case 'help':
            render_view('views/shared/help.php');
            break;
        default:
            render_view('views/shared/404.php');
            break;
    }
} elseif ($role === 'TEACHER') {
    switch ($c) {
        case 'teacher':
            if ($a === 'dashboard')
                render_view('views/teacher/dashboard.php');
            elseif ($a === 'classes')
                render_view('views/teacher/classes.php');
            break;
        case 'score':
            if ($a === 'enter')
                render_view('views/teacher/scores/enter.php');
            break;
        case 'report':
            if ($a === 'index')
                render_view('views/teacher/reports/index.php');
            elseif ($a === 'generate')
                render_view('views/teacher/reports/generate.php');
            break;
        case 'profile':
            render_view('views/shared/profile.php');
            break;
        case 'notifications':
            render_view('views/shared/notifications.php');
            break;
        case "quickactions":
            render_view('views/teacher/quickActions.php');
            break;
        case 'help':
            render_view('views/shared/help.php');
            break;
        default:
            render_view('views/shared/404.php');
            break;
    }
} else {
    render_view('views/shared/403.php');
}

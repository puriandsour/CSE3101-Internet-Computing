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
require_once 'controllers/TeacherController.php';
require_once 'controllers/ClassController.php';
require_once 'controllers/SubjectController.php';
require_once 'controllers/UserController.php';

// Models
require_once 'models/ClassModel.php';
require_once 'models/Subject.php';
require_once 'models/Grade.php';

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
            $userController = new UserController();
            if ($a === 'add') {
                render_view('views/admin/users/add.php');
            } elseif ($a === 'create') {
                $userController->create($_POST);
            } else {
                render_view('views/admin/teachers/index.php');
            }
            break;
            
        case 'student':
            $studentController = new StudentController();
            if ($a === 'add') {
                render_view('views/admin/students/add.php');
            } elseif ($a === 'create') {
                $studentController->create($_POST);
            } elseif ($a === 'enroll') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $studentController->processEnrollment($_POST);
                } else {
                    $data = $studentController->enroll();
                    render_view('views/admin/students/enroll.php', $data);
                }
            } else {
                $data = $studentController->index();
                render_view('views/admin/students/index.php', $data);
            }
            break;
            
        case 'class':
            $classController = new ClassController();
            if ($a === 'add') {
                render_view('views/admin/classes/add.php');
            } elseif ($a === 'create') {
                $classController->create($_POST);
            } elseif ($a === 'delete') {
                $classController->delete($_GET['id']);
            } else {
                $data = $classController->index();
                render_view('views/admin/classes/index.php', $data);
            }
            break;
            
        case 'subject':
            $subjectController = new SubjectController();
            if ($a === 'add') {
                render_view('views/admin/subjects/add.php');
            } elseif ($a === 'create') {
                $subjectController->create($_POST);
            } elseif ($a === 'delete') {
                $subjectController->delete($_GET['id']);
            } else {
                $data = $subjectController->index();
                render_view('views/admin/subjects/index.php', $data);
            }
            break;
            
        case 'schoolYear':
            render_view('views/admin/school_years/index.php');
            break;
            
        case 'term':
            render_view('views/admin/terms/index.php');
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
    // TEACHER ROUTING
    switch ($c) {
        case 'teacher':
            $teacherController = new TeacherController();
            if ($a === 'dashboard') {
                $data = $teacherController->dashboard();
                render_view('views/teacher/dashboard.php', $data);
            } elseif ($a === 'classes') {
                $data = $teacherController->classes();
                render_view('views/teacher/classes.php', $data);
            } elseif ($a === 'viewClass') {
                $data = $teacherController->viewClass($_GET['id']);
                render_view('views/teacher/viewClass.php', $data);
            } elseif ($a === 'getClassStudents') {
                $teacherController->getClassStudents();
            }
            break;
            
        case 'score':
            $scoreController = new ScoreController();
            if ($a === 'enter') {
                $data = $scoreController->enter();
                render_view('views/teacher/scores/enter.php', $data);
            } elseif ($a === 'manage') {
                render_view('views/teacher/scores/manage.php');
            } elseif ($a === 'getStudents') {
                $scoreController->getStudents();
            } elseif ($a === 'save') {
                $scoreController->save();
            } elseif ($a === 'delete') {
                $scoreController->delete($_GET['id']);
            }
            break;
            
        case 'report':
            $reportController = new ReportController();
            if ($a === 'index') {
                $data = $reportController->index();
                render_view('views/teacher/reports/index.php', $data);
            } elseif ($a === 'generate') {
                $data = $reportController->generate();
                render_view('views/teacher/reports/generate.php', $data);
            } elseif ($a === 'create') {
                $reportController->create($_POST);
            } elseif ($a === 'view') {
                render_view('views/teacher/reports/view.php');
            }
            break;
            
        case 'profile':
            render_view('views/shared/profile.php');
            break;
            
        case 'notifications':
            render_view('views/shared/notifications.php');
            break;
            
        case 'settings':
            render_view('views/shared/settings.php');
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

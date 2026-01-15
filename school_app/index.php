<?php
session_start();

#CSS pasges link 
echo "<link rel='stylesheet' href='public/css/style.css'>";

#controllers
require_once 'controllers/AuthController.php';
require_once 'controllers/StudentController.php';
require_once 'controllers/ScoreController.php';
require_once 'controllers/ReportController.php';

$c = $_GET['controller'] ?? 'auth';
$a = $_GET['action'] ?? 'login';

#deals with login, logout, and user creation
if ($c === 'auth') {
    $auth = new AuthController();

    if ($a === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($auth->login($_POST['email'], $_POST['password'])) {
            header("Location:index.php?controller=dashboard");
            exit;
        } else {
            echo "<p class='error'>Login failed</p>";
        }
    }
    elseif ($a === 'logout') {
        $auth->logout();
        header("Location:index.php?controller=auth");
        exit;
    }
    elseif ($a === 'createUser' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth->createUser($_POST);
        header("Location:index.php?controller=dashboard");
        exit;
    }
    elseif ($a === 'createUserForm') {
        require 'views/auth/create_user.php';
    }
    else {
        require 'views/auth/login.php';
    }
}

#deals with dashboard
elseif ($c === 'dashboard') {
    require 'views/dashboard.php';
}

#deals with students
elseif ($c === 'student') {
    $s = new StudentController();

    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $success = $s->add($_POST);
        if ($success) {
            $_SESSION['success'] = "Student added successfully!";
            header("Location:index.php?controller=dashboard");
            exit;
        } else {
            $_SESSION['error'] = "Error adding student.";
            require 'views/students/add.php';
        }
    } else {
        require 'views/students/add.php';
    }
}

#deals with scores
elseif ($c === 'score') {
    $sc = new ScoreController();
    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $sc->add($_POST);
        $_SESSION['success'] = "Score added successfully!";
        header("Location:index.php?controller=dashboard");
        exit;
    } else {
        require 'views/scores/add.php';
    }
}

#deals with subjects
elseif ($c === 'subject' && $_SESSION['role'] === 'admin') {
    require_once 'controllers/SubjectController.php';
    $sub = new SubjectController();

    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $sub->add($_POST);
        $_SESSION['success'] = "Subject added successfully!";
        header("Location:index.php?controller=dashboard");
        exit;
    } else {
        require 'views/subjects/add.php';
    }
}

#deals with classes
elseif ($c === 'class' && $_SESSION['role'] === 'admin') {
    require_once 'controllers/ClassController.php';
    $cc = new ClassController();

    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $cc->add($_POST);
        $_SESSION['success'] = "Class added successfully!";
        header("Location:index.php?controller=dashboard");
        exit;
    } else {
        require 'views/classes/add.php';
    }
}

#deals with school years
elseif ($c === 'schoolYear') {
    require_once 'controllers/SchoolYearController.php';
    $sy = new SchoolYearController();

    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $sy->add($_POST);
        $_SESSION['success'] = "School year added successfully!";
        header("Location:index.php?controller=dashboard");
        exit;
    } else {
        require 'views/school_years/add.php';
    }
}

#deals with terms
elseif ($c === 'term') {
    require_once 'controllers/TermController.php';
    $t = new TermController();

    if ($a === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $t->add($_POST);
        $_SESSION['success'] = "Term added successfully!";
        header("Location:index.php?controller=dashboard");
        exit;
    } else {
        require 'views/terms/add.php';
    }
}

#deals with reports
elseif ($c === 'report') {
    $r = new ReportController();

    if ($a === 'view' && !empty($_GET['student']) && !empty($_GET['term'])) {
        $data = $r->studentReport($_GET['student'], $_GET['term']);
        require 'views/report/report_card.php';
    } else {
        require 'views/report/select_student_term.php';
    }
}

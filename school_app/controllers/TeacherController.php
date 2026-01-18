<?php
// controllers/TeacherController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';

class TeacherController
{
    /**
     * Display the list of teachers
     */
    public function index()
    {
        $search = $_GET['search'] ?? '';

        // Fetch users with role 'TEACHER'
        $teachers = User::getAll([
            'role' => 'TEACHER',
            'search' => $search
        ]);

        return [
            'teachers' => $teachers,
            'filters' => [
                'search' => $search
            ]
        ];
    }

    /**
     * Show the form to add a new teacher
     */
    public function add()
    {
        return render_view('views/admin/teachers/add.php');
    }

    /**
     * Handle the POST request to create a teacher
     */
    public function create($data)
    {
        // 1. Validate Password presence
        if (empty($data['password'])) {
            $_SESSION['error'] = "Password is required.";
            header("Location: index.php?controller=teacher&action=add");
            exit;
        }

        // 2. Find the Teacher Role
        $role = Role::findByName('TEACHER');
        if (!$role) {
            $_SESSION['error'] = "System Error: Teacher role not found.";
            header("Location: index.php?controller=teacher&action=add");
            exit;
        }

        // 3. Prepare User Data for User::save()
        // save() method expects: [username, email, password_hash, first_name, last_name, is_active]
        $userData = [
            $data['username'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['first_name'],
            $data['last_name'],
            1 // is_active
        ];

        $userModel = new User();
        $userId = $userModel->save($userData, $role->id);

        if ($userId) {
            $_SESSION['success'] = "Teacher added successfully.";
            header("Location: index.php?controller=teacher&action=index");
            exit;
        } else {
            // User::save() should have set $_SESSION['error']
            header("Location: index.php?controller=teacher&action=add");
            exit;
        }
    }
}

<?php
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Grade.php';

class ClassController
{
    /**
     * List all classes
     */
    public function index()
    {
        $selectedGrade = $_GET['grade_id'] ?? null;
        $filters = [];
        if ($selectedGrade) {
            $filters['grade_id'] = $selectedGrade;
        }

        $classes = ClassModel::getAllWithStudentCounts($filters);
        $grades = Grade::getAll();

        render_view('views/admin/classes/index.php', [
            'classes' => $classes,
            'grades' => $grades,
            'selectedGrade' => $selectedGrade
        ]);
    }

    /**
     * Show create form
     */
    public function add()
    {
        $grades = Grade::getAll();
        render_view('views/admin/classes/add.php', [
            'grades' => $grades
        ]);
    }

    /**
     * Handle class creation
     */
    public function create($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=class&action=index");
            exit;
        }

        $classModel = new ClassModel();
        $classData = [
            'name' => htmlspecialchars($data['name'] ?? ''),
            'grade_id' => $data['grade_id'] ?? '',
            'room' => htmlspecialchars($data['room'] ?? '')
        ];

        // Validation
        if (empty($classData['name']) || empty($classData['grade_id'])) {
            $_SESSION['error'] = "Name and Grade are required.";
            header("Location: index.php?controller=class&action=add");
            exit;
        }

        if ($classModel->create($classData)) {
            $_SESSION['success'] = "Class created successfully.";
            header("Location: index.php?controller=class&action=index");
            exit;
        } else {
            // Error set in ClassModel
            header("Location: index.php?controller=class&action=add");
            exit;
        }
    }
}

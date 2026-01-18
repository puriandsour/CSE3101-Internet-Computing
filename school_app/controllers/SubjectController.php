<?php
require_once __DIR__ . '/../models/Subject.php';

class SubjectController
{

    public function index()
    {
        $selectedGrade = $_GET['grade_id'] ?? null;
        $filters = [];
        if ($selectedGrade) {
            $filters['grade_id'] = $selectedGrade;
        }

        $subjects = Subject::getAll($filters);
        $grades = Grade::getAll();

        render_view('views/admin/subjects/index.php', [
            'subjects' => $subjects,
            'grades' => $grades,
            'selectedGrade' => $selectedGrade
        ]);
    }

    public function add()
    {
        render_view('views/admin/subjects/add.php', [
            'grades' => Grade::getAll()
        ]);
    }

    public function create($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=subject&action=index");
            exit;
        }

        $subjectModel = new Subject();
        $subjectData = [
            'name' => htmlspecialchars($data['name'] ?? ''),
            'grade_id' => $data['grade_id'] ?? '',
            'code' => htmlspecialchars($data['code'] ?? ''),
            'is_active' => isset($data['is_active']) ? 1 : 0
        ];

        // Validation
        if (empty($subjectData['name']) || empty($subjectData['grade_id'])) {
            $_SESSION['error'] = "Name and Grade are required.";
            header("Location: index.php?controller=subject&action=add");
            exit;
        }

        if ($subjectModel->create($subjectData)) {
            $_SESSION['success'] = "Subject created successfully.";
            header("Location: index.php?controller=subject&action=index");
            exit;
        } else {
            // Error set in Subject model
            header("Location: index.php?controller=subject&action=add");
            exit;
        }
    }
}

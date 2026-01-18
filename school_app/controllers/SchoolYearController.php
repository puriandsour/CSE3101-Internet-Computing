<?php
require_once __DIR__ . '/../models/SchoolYear.php';

class SchoolYearController
{
    /**
     * List all academic years
     */
    public function index()
    {
        $years = SchoolYear::getAll();
        render_view('views/admin/school_years/index.php', [
            'years' => $years
        ]);
    }

    /**
     * Show create form
     */
    public function add()
    {
        render_view('views/admin/school_years/add.php');
    }

    /**
     * Handle creation with automatic terms
     */
    public function create($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=schoolYear&action=index");
            exit;
        }

        $model = new SchoolYear();
        $yearData = [
            'name' => htmlspecialchars($data['name'] ?? ''),
            'start_date' => $data['start_date'] ?? '',
            'end_date' => $data['end_date'] ?? ''
        ];

        // Validation
        if (empty($yearData['name']) || empty($yearData['start_date']) || empty($yearData['end_date'])) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: index.php?controller=schoolYear&action=add");
            exit;
        }

        if ($model->createWithTerms($yearData)) {
            $_SESSION['success'] = "School year and 3 terms created successfully.";
            header("Location: index.php?controller=schoolYear&action=index");
            exit;
        } else {
            header("Location: index.php?controller=schoolYear&action=add");
            exit;
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $year = SchoolYear::find($id);
        if (!$year) {
            $_SESSION['error'] = "School year not found.";
            header("Location: index.php?controller=schoolYear&action=index");
            exit;
        }
        render_view('views/admin/school_years/edit.php', [
            'year' => $year
        ]);
    }

    /**
     * Handle update
     */
    public function update($id, $data)
    {
        $model = new SchoolYear();
        if ($model->update($id, $data)) {
            $_SESSION['success'] = "School year updated.";
            header("Location: index.php?controller=schoolYear&action=index");
            exit;
        } else {
            header("Location: index.php?controller=schoolYear&action=edit&id=$id");
            exit;
        }
    }

    /**
     * Set as current year
     */
    public function setAsCurrent($id)
    {
        $model = new SchoolYear();
        if ($model->setAsCurrent($id)) {
            $_SESSION['success'] = "Active school year updated.";
        }
        header("Location: index.php?controller=schoolYear&action=index");
    }
}

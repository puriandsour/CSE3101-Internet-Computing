<?php
require_once __DIR__ . '/../models/Term.php';
require_once __DIR__ . '/../models/SchoolYear.php';

class TermController
{
    /**
     * List terms for a specific school year
     */
    public function index($schoolYearId)
    {
        $year = SchoolYear::find($schoolYearId);
        if (!$year) {
            $_SESSION['error'] = "School year not found.";
            header("Location: index.php?controller=schoolYear&action=index");
            exit;
        }

        $terms = Term::getBySchoolYear($schoolYearId);
        render_view('views/admin/terms/index.php', [
            'year' => $year,
            'terms' => $terms
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $term = Term::find($id);
        if (!$term) {
            $_SESSION['error'] = "Term not found.";
            header("Location: index.php?controller=schoolYear&action=index");
            exit;
        }
        render_view('views/admin/terms/edit.php', [
            'term' => $term
        ]);
    }

    /**
     * Handle update
     */
    public function update($id, $data)
    {
        $model = new Term();
        if ($model->update($id, $data)) {
            $_SESSION['success'] = "Term updated.";
            $term = Term::find($id);
            header("Location: index.php?controller=term&action=index&id=" . $term->school_year_id);
            exit;
        } else {
            header("Location: index.php?controller=term&action=edit&id=$id");
            exit;
        }
    }
}

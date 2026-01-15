<?php
require_once __DIR__ . '/../models/SchoolYear.php';

class SchoolYearController {

    public function add($data) {
        $success = SchoolYear::create($data['name']);
        if ($success) {
            $_SESSION['success'] = "School Year '{$data['name']}' added successfully!";
        } else {
            $_SESSION['error'] = "Error adding school year.";
        }
        return $success;
    }

    public function all() {
        return SchoolYear::all();
    }
}

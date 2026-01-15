<?php
require_once __DIR__ . '/../models/ClassModel.php';

class ClassController {

    public function add($data) {
        $db = \Config\Database::connect();

        // Optional: check grade exists
        $stmt = $db->prepare("SELECT * FROM grades WHERE id=?");
        $stmt->execute([$data['grade_id']]);
        if (!$stmt->fetch(PDO::FETCH_OBJ)) {
            $GLOBALS['error'] = "Error: Grade ID {$data['grade_id']} does not exist.";
            return false;
        }

        // Add class
        $success = ClassModel::create(
            htmlspecialchars($data['name']),
            $data['grade_id']
        );

        if ($success) {
            $GLOBALS['success'] = "Class added successfully!";
        } else {
            $GLOBALS['error'] = "Error adding class.";
        }

        return $success;
    }

    public function all() {
        return ClassModel::all();
    }
}

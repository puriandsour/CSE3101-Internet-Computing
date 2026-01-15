<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/ClassModel.php';

class StudentController {

    public function add($data) {
        // Check if the class exists
        $class = ClassModel::find($data['class_id']);
        if (!$class) {
            echo "Error: Class ID {$data['class_id']} does not exist.";
            return false;
        }

        // Add student
        $success = Student::create(
            $data['first_name'],
            $data['last_name'],
            $data['class_id']
        );

        if ($success) {
            // Only success message before redirect
            echo "Student added successfully!";
            return true;
        } else {
            echo "Error adding student.";
            return false;
        }
    }
}

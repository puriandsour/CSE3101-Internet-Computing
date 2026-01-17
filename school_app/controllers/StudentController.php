<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/ClassModel.php';

class StudentController
{

    public function add($data)
    {
        // 1. Check if the class exists
        $class = ClassModel::find($data['class_id']);
        if (!$class) {
            $_SESSION['error'] = "Error: Class ID {$data['class_id']} does not exist.";
            return false;
        }

        // 2. Get Current School Year
        $db = Database::connect();
        $stmt = $db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
        $currentYear = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$currentYear) {
            $_SESSION['error'] = "Error: No active school year found. Please set one first.";
            return false;
        }

        // 3. Create Student
        // Instantiate Student model
        $studentModel = new Student();

        // Student::create expects an array
        $studentData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'admission_no' => $data['admission_no'] ?? null
        ];

        $studentId = $studentModel->create($studentData);

        if ($studentId) {
            // 4. Enroll Student
            require_once 'models/Enrollment.php';
            $enrollment = new Enrollment();
            $enrollmentId = $enrollment->create($studentId, $data['class_id'], $currentYear->id);

            if ($enrollmentId) {
                return true;
            } else {
                // Rollback student creation? Ideally yes, but for now just error.
                // In a real app we'd wrap this all in a transaction in the Controller or a Service class.
                $_SESSION['error'] .= " (Student created but enrollment failed)";
                return false;
            }
        } else {
            // Student::create sets its own error in $_SESSION usually
            return false;
        }
    }
}

<?php
// models/Student.php
require_once __DIR__ . '/Model.php';

class Student extends Model
{
    // ========================================
    // RETRIEVE METHODS
    // ========================================

    /**
     * Get paginated students with search and filters
     */
    public static function list($filters = [], $page = 1, $limit = 10)
    {
        $db = Database::connect();
        $offset = ($page - 1) * $limit;

        $sql = "SELECT DISTINCT
                    s.*,
                    c.name as class_name,
                    g.name as grade_name,
                    g.grade_number
                FROM students s
                LEFT JOIN enrollments e ON s.id = e.student_id 
                    AND e.status = 'ACTIVE'
                    AND e.school_year_id = (SELECT id FROM school_years WHERE is_current = 1 LIMIT 1)
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN grades g ON c.grade_id = g.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['search'])) {
            $searchTerm = "%{$filters['search']}%";
            $sql .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.admission_no LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['grade_id'])) {
            $sql .= " AND g.id = ?";
            $params[] = $filters['grade_id'];
        }

        if (!empty($filters['class_id'])) {
            $sql .= " AND c.id = ?";
            $params[] = $filters['class_id'];
        }

        $sql .= " ORDER BY s.admission_no ASC";

        // Count for pagination
        $countSql = "SELECT COUNT(DISTINCT s.id) FROM students s
                     LEFT JOIN enrollments e ON s.id = e.student_id 
                        AND e.status = 'ACTIVE'
                        AND e.school_year_id = (SELECT id FROM school_years WHERE is_current = 1 LIMIT 1)
                     LEFT JOIN classes c ON e.class_id = c.id
                     LEFT JOIN grades g ON c.grade_id = g.id
                     WHERE 1=1";

        if (!empty($filters['search'])) {
            $countSql .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.admission_no LIKE ?)";
        }
        if (!empty($filters['grade_id'])) {
            $countSql .= " AND g.id = ?";
        }
        if (!empty($filters['class_id'])) {
            $countSql .= " AND c.id = ?";
        }

        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return [
            'data' => $stmt->fetchAll(PDO::FETCH_OBJ),
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ];
    }

    /**
     * Get all students with optional filters
     * 
     * @param array $filters Optional: ['is_active', 'grade_id', 'class_id', 'school_year_id']
     * @return array Array of student objects with enrollment info
     */
    public static function getAll($filters = [])
    {
        $db = Database::connect();

        $sql = "SELECT DISTINCT
                    s.*,
                    e.id as enrollment_id,
                    c.name as class_name,
                    c.id as class_id,
                    g.name as grade_name,
                    g.grade_number,
                    sy.name as school_year_name,
                    e.status as enrollment_status
                FROM students s
                LEFT JOIN enrollments e ON s.id = e.student_id
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN grades g ON c.grade_id = g.id
                LEFT JOIN school_years sy ON e.school_year_id = sy.id
                WHERE 1=1";

        $params = [];

        if (isset($filters['is_active'])) {
            $sql .= " AND s.is_active = ?";
            $params[] = $filters['is_active'];
        }

        if (!empty($filters['school_year_id'])) {
            $sql .= " AND e.school_year_id = ?";
            $params[] = $filters['school_year_id'];
        } else {
            // Default to current year if not specified
            $sql .= " AND (sy.is_current = 1 OR sy.is_current IS NULL)";
        }

        if (!empty($filters['grade_id'])) {
            $sql .= " AND g.id = ?";
            $params[] = $filters['grade_id'];
        }

        if (!empty($filters['class_id'])) {
            $sql .= " AND c.id = ?";
            $params[] = $filters['class_id'];
        }

        $sql .= " ORDER BY s.last_name, s.first_name";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find student by ID with current enrollment info
     * 
     * @param int $id Student ID
     * @return object|false Student object or false
     */
    public static function find($id)
    {
        $db = Database::connect();

        $sql = "SELECT 
                    s.*,
                    e.id as enrollment_id,
                    c.name as class_name,
                    c.id as class_id,
                    g.name as grade_name,
                    g.grade_number,
                    sy.name as school_year_name
                FROM students s
                LEFT JOIN enrollments e ON s.id = e.student_id 
                    AND e.status = 'ACTIVE'
                LEFT JOIN school_years sy ON e.school_year_id = sy.id 
                    AND sy.is_current = 1
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN grades g ON c.grade_id = g.id
                WHERE s.id = ?
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get all students in a specific class
     * 
     * @param int $classId Class ID
     * @param int|null $schoolYearId School year ID (null = current year)
     * @return array Array of student objects
     */
    public static function getByClass($classId, $schoolYearId = null)
    {
        $db = Database::connect();

        if ($schoolYearId === null) {
            // Get current school year
            $yearStmt = $db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
            $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);

            if (!$currentYear) {
                return [];
            }

            $schoolYearId = $currentYear->id;
        }

        $sql = "SELECT 
                    s.*,
                    e.id as enrollment_id,
                    e.enrolled_at,
                    e.status as enrollment_status
                FROM students s
                JOIN enrollments e ON s.id = e.student_id
                WHERE e.class_id = ? 
                  AND e.school_year_id = ?
                  AND e.status = 'ACTIVE'
                  AND s.is_active = 1
                ORDER BY s.last_name, s.first_name";

        $stmt = $db->prepare($sql);
        $stmt->execute([$classId, $schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all students in a specific grade
     * 
     * @param int $gradeId Grade ID
     * @param int|null $schoolYearId School year ID (null = current year)
     * @return array Array of student objects
     */
    public static function getByGrade($gradeId, $schoolYearId = null)
    {
        $db = Database::connect();

        if ($schoolYearId === null) {
            $yearStmt = $db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
            $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);

            if (!$currentYear) {
                return [];
            }

            $schoolYearId = $currentYear->id;
        }

        $sql = "SELECT 
                    s.*,
                    c.name as class_name,
                    e.id as enrollment_id
                FROM students s
                JOIN enrollments e ON s.id = e.student_id
                JOIN classes c ON e.class_id = c.id
                WHERE c.grade_id = ? 
                  AND e.school_year_id = ?
                  AND e.status = 'ACTIVE'
                ORDER BY c.name, s.last_name, s.first_name";

        $stmt = $db->prepare($sql);
        $stmt->execute([$gradeId, $schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Search students by name or admission number
     * 
     * @param string $query Search query
     * @return array Array of matching students
     */
    public static function search($query)
    {
        $db = Database::connect();

        $searchTerm = "%{$query}%";

        $sql = "SELECT 
                    s.*,
                    c.name as class_name,
                    g.name as grade_name
                FROM students s
                LEFT JOIN enrollments e ON s.id = e.student_id 
                    AND e.status = 'ACTIVE'
                    AND e.school_year_id = (SELECT id FROM school_years WHERE is_current = 1 LIMIT 1)
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN grades g ON c.grade_id = g.id
                WHERE s.first_name LIKE ? 
                   OR s.last_name LIKE ?
                   OR s.admission_no LIKE ?
                ORDER BY s.last_name, s.first_name
                LIMIT 50";

        $stmt = $db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get students without enrollment for a school year
     * 
     * @param int|null $schoolYearId School year ID (null = current year)
     * @return array Array of unenrolled students
     */
    public static function getWithoutEnrollment($schoolYearId = null)
    {
        $db = Database::connect();

        if ($schoolYearId === null) {
            $yearStmt = $db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
            $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);

            if (!$currentYear) {
                return [];
            }

            $schoolYearId = $currentYear->id;
        }

        $sql = "SELECT s.*
                FROM students s
                WHERE s.is_active = 1
                  AND s.id NOT IN (
                      SELECT student_id 
                      FROM enrollments 
                      WHERE school_year_id = ?
                  )
                ORDER BY s.last_name, s.first_name";

        $stmt = $db->prepare($sql);
        $stmt->execute([$schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ========================================
    // CREATE METHODS
    // ========================================

    /**
     * Create a new student
     * 
     * @param array $studentData Array with: first_name, last_name, date_of_birth, gender, admission_no (optional)
     * @return int|false Student ID on success, false on failure
     */
    public function create($studentData)
    {
        try {
            // Validate student data
            $validation = $this->validateStudentData($studentData);
            if ($validation !== true) {
                $_SESSION['error'] = $validation;
                return false;
            }

            // Auto-generate admission number if not provided
            if (empty($studentData['admission_no'])) {
                $studentData['admission_no'] = $this->generateAdmissionNumber();
            }

            // Check if admission number already exists
            if ($this->admissionNumberExists($studentData['admission_no'])) {
                $_SESSION['error'] = "Admission number already exists.";
                return false;
            }

            $sql = "INSERT INTO students 
                    (admission_no, first_name, last_name, date_of_birth, gender, is_active) 
                    VALUES (?, ?, ?, ?, ?, 1)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $studentData['admission_no'],
                $studentData['first_name'],
                $studentData['last_name'],
                $studentData['date_of_birth'] ?? null,
                $studentData['gender'] ?? null
            ]);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error creating student: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Generate unique admission number
     * Format: YEAR-XXXX (e.g., 2025-0001)
     * 
     * @return string Generated admission number
     */
    private function generateAdmissionNumber()
    {
        $year = date('Y');

        // Get the last admission number for this year
        $sql = "SELECT admission_no 
                FROM students 
                WHERE admission_no LIKE ? 
                ORDER BY admission_no DESC 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(["{$year}-%"]);
        $lastAdmission = $stmt->fetch(PDO::FETCH_OBJ);

        if ($lastAdmission) {
            // Extract number part and increment
            $parts = explode('-', $lastAdmission->admission_no);
            $number = intval($parts[1]) + 1;
        } else {
            // First student of the year
            $number = 1;
        }

        return sprintf("%s-%04d", $year, $number);
    }

    // ========================================
    // UPDATE METHODS
    // ========================================

    /**
     * Update student information
     * 
     * @param int $studentId Student ID
     * @param array $studentData Data to update
     * @return bool Success status
     */
    public function update($studentId, $studentData)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($studentData['first_name'])) {
                $updates[] = "first_name = ?";
                $params[] = $studentData['first_name'];
            }

            if (isset($studentData['last_name'])) {
                $updates[] = "last_name = ?";
                $params[] = $studentData['last_name'];
            }

            if (isset($studentData['date_of_birth'])) {
                $updates[] = "date_of_birth = ?";
                $params[] = $studentData['date_of_birth'];
            }

            if (isset($studentData['gender'])) {
                $updates[] = "gender = ?";
                $params[] = $studentData['gender'];
            }

            if (isset($studentData['admission_no'])) {
                // Check if new admission number already exists
                if ($this->admissionNumberExists($studentData['admission_no'], $studentId)) {
                    $_SESSION['error'] = "Admission number already exists.";
                    return false;
                }
                $updates[] = "admission_no = ?";
                $params[] = $studentData['admission_no'];
            }

            if (empty($updates)) {
                return false;
            }

            $params[] = $studentId;

            $sql = "UPDATE students SET " . implode(', ', $updates) . " WHERE id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating student: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Toggle student active status
     * 
     * @param int $studentId Student ID
     * @return bool Success status
     */
    public function toggleActive($studentId)
    {
        try {
            $sql = "UPDATE students SET is_active = NOT is_active WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$studentId]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error toggling student status: " . $e->getMessage();
            return false;
        }
    }

    // ========================================
    // DELETE METHODS
    // ========================================

    /**
     * Soft delete student (set is_active = 0)
     * 
     * @param int $studentId Student ID
     * @return bool Success status
     */
    public function delete($studentId)
    {
        try {
            // Check if student has scores
            $scoreStmt = $this->db->prepare(
                "SELECT COUNT(*) as count 
                 FROM scores sc
                 JOIN enrollments e ON sc.enrollment_id = e.id
                 WHERE e.student_id = ?"
            );
            $scoreStmt->execute([$studentId]);
            $scoreCount = $scoreStmt->fetch(PDO::FETCH_OBJ);

            if ($scoreCount->count > 0) {
                $_SESSION['error'] = "Cannot delete student with recorded scores. Consider deactivating instead.";
                return false;
            }

            // Soft delete
            $sql = "UPDATE students SET is_active = 0 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$studentId]);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting student: " . $e->getMessage();
            return false;
        }
    }

    // ========================================
    // ENROLLMENT HELPER METHODS
    // ========================================

    /**
     * Get student's current enrollment
     * 
     * @param int $studentId Student ID
     * @param int|null $schoolYearId School year ID (null = current year)
     * @return object|false Enrollment object or false
     */
    public function getCurrentEnrollment($studentId, $schoolYearId = null)
    {
        require_once __DIR__ . '/Enrollment.php';
        return Enrollment::getByStudent($studentId, $schoolYearId);
    }

    /**
     * Get student's enrollment history
     * 
     * @param int $studentId Student ID
     * @return array Array of enrollment objects
     */
    public function getEnrollmentHistory($studentId)
    {
        $sql = "SELECT 
                    e.*,
                    c.name as class_name,
                    g.name as grade_name,
                    sy.name as school_year_name
                FROM enrollments e
                JOIN classes c ON e.class_id = c.id
                JOIN grades g ON c.grade_id = g.id
                JOIN school_years sy ON e.school_year_id = sy.id
                WHERE e.student_id = ?
                ORDER BY sy.start_date DESC, e.enrolled_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ========================================
    // VALIDATION METHODS
    // ========================================

    /**
     * Validate student data
     * 
     * @param array $data Student data
     * @return bool|string True if valid, error message if invalid
     */
    public function validateStudentData($data)
    {
        if (empty($data['first_name']) || strlen($data['first_name']) < 2) {
            return "First name is required (minimum 2 characters).";
        }

        if (empty($data['last_name']) || strlen($data['last_name']) < 2) {
            return "Last name is required (minimum 2 characters).";
        }

        if (!empty($data['date_of_birth'])) {
            $date = DateTime::createFromFormat('Y-m-d', $data['date_of_birth']);
            if (!$date || $date->format('Y-m-d') !== $data['date_of_birth']) {
                return "Invalid date of birth format. Use YYYY-MM-DD.";
            }
        }

        if (!empty($data['gender'])) {
            if (!in_array($data['gender'], ['M', 'F', 'OTHER'])) {
                return "Invalid gender. Must be M, F, or OTHER.";
            }
        }

        return true;
    }

    /**
     * Check if admission number exists
     * 
     * @param string $admissionNo Admission number
     * @param int|null $excludeId Student ID to exclude from check
     * @return bool True if exists
     */
    private function admissionNumberExists($admissionNo, $excludeId = null)
    {
        $sql = "SELECT id FROM students WHERE admission_no = ?";
        $params = [$admissionNo];

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }
}
<?php
// models/Enrollment.php
require_once __DIR__ . '/Model.php';

/**
 * Enrollment Model
 * 
 * Manages student enrollments in classes for specific school years.
 * This is the bridge between Students and Classes.
 * 
 * Key Rules:
 * - One student can only be enrolled in ONE class per school year
 * - Enrollments have status: ACTIVE, COMPLETED, TRANSFERRED, DROPPED
 */
class Enrollment extends Model
{
    // ========================================
    // RETRIEVE METHODS
    // ========================================

    /**
     * Get all enrollments with optional filters
     * 
     * @param array $filters Optional filters: ['school_year_id', 'class_id', 'student_id', 'status']
     * @return array Array of enrollment objects with student, class, and grade info
     */
    public static function getAll($filters = [])
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    e.*,
                    s.first_name, s.last_name, s.admission_no,
                    c.name as class_name,
                    g.name as grade_name, g.grade_number
                FROM enrollments e
                JOIN students s ON e.student_id = s.id
                JOIN classes c ON e.class_id = c.id
                JOIN grades g ON c.grade_id = g.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['school_year_id'])) {
            $sql .= " AND e.school_year_id = ?";
            $params[] = $filters['school_year_id'];
        }
        
        if (!empty($filters['class_id'])) {
            $sql .= " AND e.class_id = ?";
            $params[] = $filters['class_id'];
        }
        
        if (!empty($filters['student_id'])) {
            $sql .= " AND e.student_id = ?";
            $params[] = $filters['student_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = ?";
            $params[] = $filters['status'];
        }
        
        $sql .= " ORDER BY g.grade_number, c.name, s.last_name, s.first_name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find enrollment by ID
     * 
     * @param int $id Enrollment ID
     * @return object|false Enrollment object or false
     */
    public static function find($id)
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    e.*,
                    s.first_name, s.last_name, s.admission_no,
                    c.name as class_name, c.grade_id,
                    g.name as grade_name, g.grade_number,
                    sy.name as school_year_name
                FROM enrollments e
                JOIN students s ON e.student_id = s.id
                JOIN classes c ON e.class_id = c.id
                JOIN grades g ON c.grade_id = g.id
                JOIN school_years sy ON e.school_year_id = sy.id
                WHERE e.id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get enrollment(s) for a student in a specific school year
     * 
     * @param int $studentId Student ID
     * @param int|null $schoolYearId School year ID (null = current year)
     * @return object|false Enrollment object or false
     */
    public static function getByStudent($studentId, $schoolYearId = null)
    {
        $db = Database::connect();
        
        if ($schoolYearId === null) {
            // Get current school year
            $yearStmt = $db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
            $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);
            
            if (!$currentYear) {
                return false; // No current year set
            }
            
            $schoolYearId = $currentYear->id;
        }
        
        $sql = "SELECT 
                    e.*,
                    c.name as class_name, c.grade_id,
                    g.name as grade_name, g.grade_number
                FROM enrollments e
                JOIN classes c ON e.class_id = c.id
                JOIN grades g ON c.grade_id = g.id
                WHERE e.student_id = ? AND e.school_year_id = ?
                LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$studentId, $schoolYearId]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get all enrollments for a specific class in a school year
     * 
     * @param int $classId Class ID
     * @param int $schoolYearId School year ID
     * @param string $status Filter by status (default: ACTIVE)
     * @return array Array of enrollment objects
     */
    public static function getByClass($classId, $schoolYearId, $status = 'ACTIVE')
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    e.*,
                    s.first_name, s.last_name, s.admission_no, s.gender
                FROM enrollments e
                JOIN students s ON e.student_id = s.id
                WHERE e.class_id = ? 
                  AND e.school_year_id = ? 
                  AND e.status = ?
                ORDER BY s.last_name, s.first_name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$classId, $schoolYearId, $status]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all active enrollments for a school year
     * 
     * @param int|null $schoolYearId School year ID (null = current year)
     * @return array Array of enrollment objects
     */
    public static function getActiveEnrollments($schoolYearId = null)
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
        
        return self::getAll([
            'school_year_id' => $schoolYearId,
            'status' => 'ACTIVE'
        ]);
    }

    // ========================================
    // CREATE METHODS
    // ========================================

    /**
     * Create a new enrollment
     * 
     * @param int $studentId Student ID
     * @param int $classId Class ID
     * @param int $schoolYearId School year ID
     * @param string|null $enrolledAt Enrollment date (YYYY-MM-DD) or null for today
     * @return int|false Enrollment ID on success, false on failure
     */
    public function create($studentId, $classId, $schoolYearId, $enrolledAt = null)
    {
        try {
            // Validate the enrollment
            $validation = $this->validateEnrollment($studentId, $classId, $schoolYearId);
            if ($validation !== true) {
                $_SESSION['error'] = $validation; // Store error message
                return false;
            }
            
            // Use today's date if not provided
            if ($enrolledAt === null) {
                $enrolledAt = date('Y-m-d');
            }
            
            $sql = "INSERT INTO enrollments 
                    (student_id, class_id, school_year_id, enrolled_at, status) 
                    VALUES (?, ?, ?, ?, 'ACTIVE')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$studentId, $classId, $schoolYearId, $enrolledAt]);
            
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            // Check for unique constraint violation
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Student is already enrolled in a class for this school year.";
            } else {
                $_SESSION['error'] = "Error creating enrollment: " . $e->getMessage();
            }
            return false;
        }
    }

    // ========================================
    // UPDATE METHODS
    // ========================================

    /**
     * Update enrollment details
     * 
     * @param int $enrollmentId Enrollment ID
     * @param array $data Data to update (class_id, status, enrolled_at)
     * @return bool Success status
     */
    public function update($enrollmentId, $data)
    {
        try {
            $updates = [];
            $params = [];
            
            if (isset($data['class_id'])) {
                $updates[] = "class_id = ?";
                $params[] = $data['class_id'];
            }
            
            if (isset($data['status'])) {
                $updates[] = "status = ?";
                $params[] = $data['status'];
            }
            
            if (isset($data['enrolled_at'])) {
                $updates[] = "enrolled_at = ?";
                $params[] = $data['enrolled_at'];
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $params[] = $enrollmentId;
            
            $sql = "UPDATE enrollments SET " . implode(', ', $updates) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating enrollment: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Transfer student to a different class
     * 
     * @param int $enrollmentId Enrollment ID
     * @param int $newClassId New class ID
     * @return bool Success status
     */
    public function changeClass($enrollmentId, $newClassId)
    {
        try {
            // Get the enrollment to validate
            $enrollment = self::find($enrollmentId);
            if (!$enrollment) {
                $_SESSION['error'] = "Enrollment not found.";
                return false;
            }
            
            // Validate the new class exists and is active
            $classStmt = $this->db->prepare("SELECT id FROM classes WHERE id = ? AND is_active = 1");
            $classStmt->execute([$newClassId]);
            if (!$classStmt->fetch()) {
                $_SESSION['error'] = "Invalid or inactive class.";
                return false;
            }
            
            // Update the enrollment
            $sql = "UPDATE enrollments SET class_id = ?, status = 'TRANSFERRED' WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            if ($stmt->execute([$newClassId, $enrollmentId])) {
                $_SESSION['success'] = "Student transferred to new class successfully.";
                return true;
            }
            
            return false;
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error changing class: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update enrollment status
     * 
     * @param int $enrollmentId Enrollment ID
     * @param string $status New status (ACTIVE, COMPLETED, TRANSFERRED, DROPPED)
     * @return bool Success status
     */
    public function updateStatus($enrollmentId, $status)
    {
        $validStatuses = ['ACTIVE', 'COMPLETED', 'TRANSFERRED', 'DROPPED'];
        
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = "Invalid status.";
            return false;
        }
        
        try {
            $sql = "UPDATE enrollments SET status = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $enrollmentId]);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating status: " . $e->getMessage();
            return false;
        }
    }

    // ========================================
    // DELETE METHODS
    // ========================================

    /**
     * Delete an enrollment
     * WARNING: This will cascade delete all scores for this enrollment!
     * 
     * @param int $enrollmentId Enrollment ID
     * @return bool Success status
     */
    public function delete($enrollmentId)
    {
        try {
            // Check if enrollment has scores
            $scoreStmt = $this->db->prepare("SELECT COUNT(*) as count FROM scores WHERE enrollment_id = ?");
            $scoreStmt->execute([$enrollmentId]);
            $scoreCount = $scoreStmt->fetch(PDO::FETCH_OBJ);
            
            if ($scoreCount->count > 0) {
                $_SESSION['error'] = "Cannot delete enrollment. Student has {$scoreCount->count} score(s) recorded.";
                return false;
            }
            
            $sql = "DELETE FROM enrollments WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$enrollmentId]);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting enrollment: " . $e->getMessage();
            return false;
        }
    }

    // ========================================
    // VALIDATION METHODS
    // ========================================

    /**
     * Validate enrollment data
     * 
     * @param int $studentId Student ID
     * @param int $classId Class ID
     * @param int $schoolYearId School year ID
     * @return bool|string True if valid, error message if invalid
     */
    public function validateEnrollment($studentId, $classId, $schoolYearId)
    {
        // Check if student exists and is active
        $studentStmt = $this->db->prepare("SELECT id, is_active FROM students WHERE id = ?");
        $studentStmt->execute([$studentId]);
        $student = $studentStmt->fetch(PDO::FETCH_OBJ);
        
        if (!$student) {
            return "Student not found.";
        }
        
        if (!$student->is_active) {
            return "Cannot enroll inactive student.";
        }
        
        // Check if class exists and is active
        $classStmt = $this->db->prepare("SELECT id, is_active FROM classes WHERE id = ?");
        $classStmt->execute([$classId]);
        $class = $classStmt->fetch(PDO::FETCH_OBJ);
        
        if (!$class) {
            return "Class not found.";
        }
        
        if (!$class->is_active) {
            return "Cannot enroll in inactive class.";
        }
        
        // Check if school year exists
        $yearStmt = $this->db->prepare("SELECT id FROM school_years WHERE id = ?");
        $yearStmt->execute([$schoolYearId]);
        if (!$yearStmt->fetch()) {
            return "School year not found.";
        }
        
        // Check if student already enrolled for this year
        if ($this->hasExistingEnrollment($studentId, $schoolYearId)) {
            return "Student is already enrolled in a class for this school year.";
        }
        
        return true;
    }

    /**
     * Check if student already has an enrollment for a school year
     * 
     * @param int $studentId Student ID
     * @param int $schoolYearId School year ID
     * @return bool True if enrollment exists
     */
    private function hasExistingEnrollment($studentId, $schoolYearId)
    {
        $sql = "SELECT id FROM enrollments 
                WHERE student_id = ? AND school_year_id = ? 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId, $schoolYearId]);
        
        return $stmt->fetch() !== false;
    }

    // ========================================
    // BUSINESS LOGIC METHODS
    // ========================================

    /**
     * Bulk promote students from one class to another (for end of year)
     * 
     * @param int $fromClassId Source class ID
     * @param int $toClassId Destination class ID
     * @param int $fromYearId Source school year ID
     * @param int $toYearId Destination school year ID
     * @return int|false Number of students promoted or false on failure
     */
    public function promoteStudents($fromClassId, $toClassId, $fromYearId, $toYearId)
    {
        try {
            $this->db->beginTransaction();
            
            // Get all active students in the source class
            $students = self::getByClass($fromClassId, $fromYearId, 'ACTIVE');
            
            $promotedCount = 0;
            
            foreach ($students as $enrollment) {
                // Mark old enrollment as COMPLETED
                $this->updateStatus($enrollment->id, 'COMPLETED');
                
                // Create new enrollment in new class/year
                $newEnrollmentId = $this->create(
                    $enrollment->student_id,
                    $toClassId,
                    $toYearId,
                    date('Y-m-d')
                );
                
                if ($newEnrollmentId) {
                    $promotedCount++;
                }
            }
            
            $this->db->commit();
            return $promotedCount;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error promoting students: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Get count of students enrolled in a class for a school year
     * 
     * @param int $classId Class ID
     * @param int $schoolYearId School year ID
     * @param string $status Filter by status (default: ACTIVE)
     * @return int Count of enrollments
     */
    public function getEnrollmentCount($classId, $schoolYearId, $status = 'ACTIVE')
    {
        $sql = "SELECT COUNT(*) as count 
                FROM enrollments 
                WHERE class_id = ? AND school_year_id = ? AND status = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classId, $schoolYearId, $status]);
        
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }
}
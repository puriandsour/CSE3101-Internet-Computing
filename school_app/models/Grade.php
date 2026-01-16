<?php
// models/Grade.php
require_once __DIR__ . '/Model.php';

class Grade extends Model
{
    /**
     * Get all grades (1-6)
     * NOTE: Grades are predefined and cannot be created/modified/deleted
     */
    public static function getAll()
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM grades ORDER BY grade_number";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find grade by ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM grades WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find grade by number (1-6)
     */
    public static function findByNumber($gradeNumber)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM grades WHERE grade_number = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$gradeNumber]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get all classes in this grade
     */
    public function getClasses($gradeId, $activeOnly = true)
    {
        $sql = "SELECT * FROM classes WHERE grade_id = ?";
        $params = [$gradeId];
        
        if ($activeOnly) {
            $sql .= " AND is_active = 1";
        }
        
        $sql .= " ORDER BY name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all subjects for this grade
     */
    public function getSubjects($gradeId, $activeOnly = true)
    {
        $sql = "SELECT * FROM subjects WHERE grade_id = ?";
        $params = [$gradeId];
        
        if ($activeOnly) {
            $sql .= " AND is_active = 1";
        }
        
        $sql .= " ORDER BY name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Count students in this grade for a school year
     */
    public function getStudentCount($gradeId, $schoolYearId = null)
    {
        if ($schoolYearId === null) {
            // Get current school year
            $yearStmt = $this->db->query("SELECT id FROM school_years WHERE is_current = 1 LIMIT 1");
            $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);
            
            if (!$currentYear) {
                return 0;
            }
            
            $schoolYearId = $currentYear->id;
        }
        
        $sql = "SELECT COUNT(*) as count
                FROM enrollments e
                JOIN classes c ON e.class_id = c.id
                WHERE c.grade_id = ? 
                  AND e.school_year_id = ?
                  AND e.status = 'ACTIVE'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$gradeId, $schoolYearId]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->count;
    }
}

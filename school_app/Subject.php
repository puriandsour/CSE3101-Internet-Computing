<?php
// models/Subject.php
require_once __DIR__ . '/Model.php';

class Subject extends Model
{
    /**
     * Get all subjects with optional filters
     */
    public static function getAll($filters = [])
    {
        $db = Database::connect();
        
        $sql = "SELECT s.*, g.name as grade_name, g.grade_number
                FROM subjects s
                JOIN grades g ON s.grade_id = g.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['grade_id'])) {
            $sql .= " AND s.grade_id = ?";
            $params[] = $filters['grade_id'];
        }
        
        if (isset($filters['is_active'])) {
            $sql .= " AND s.is_active = ?";
            $params[] = $filters['is_active'];
        }
        
        $sql .= " ORDER BY g.grade_number, s.name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find subject by ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        
        $sql = "SELECT s.*, g.name as grade_name, g.grade_number
                FROM subjects s
                JOIN grades g ON s.grade_id = g.id
                WHERE s.id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get subjects by grade
     */
    public static function getByGrade($gradeId, $activeOnly = true)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM subjects WHERE grade_id = ?";
        $params = [$gradeId];
        
        if ($activeOnly) {
            $sql .= " AND is_active = 1";
        }
        
        $sql .= " ORDER BY name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get subjects for a class (through its grade)
     */
    public static function getByClass($classId)
    {
        $db = Database::connect();
        
        $sql = "SELECT s.*
                FROM subjects s
                JOIN classes c ON s.grade_id = c.grade_id
                WHERE c.id = ? AND s.is_active = 1
                ORDER BY s.name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Create new subject
     */
    public function create($subjectData)
    {
        try {
            // Check if subject name already exists in this grade
            $checkSql = "SELECT id FROM subjects WHERE name = ? AND grade_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$subjectData['name'], $subjectData['grade_id']]);
            
            if ($checkStmt->fetch()) {
                $_SESSION['error'] = "Subject already exists in this grade.";
                return false;
            }
            
            $sql = "INSERT INTO subjects (name, grade_id, code, is_active) 
                    VALUES (?, ?, ?, 1)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $subjectData['name'],
                $subjectData['grade_id'],
                $subjectData['code'] ?? null
            ]);
            
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error creating subject: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update subject
     */
    public function update($subjectId, $subjectData)
    {
        try {
            $updates = [];
            $params = [];
            
            if (isset($subjectData['name'])) {
                // Get current grade_id
                $currentSql = "SELECT grade_id FROM subjects WHERE id = ?";
                $currentStmt = $this->db->prepare($currentSql);
                $currentStmt->execute([$subjectId]);
                $current = $currentStmt->fetch(PDO::FETCH_OBJ);
                
                // Check uniqueness
                $checkSql = "SELECT id FROM subjects WHERE name = ? AND grade_id = ? AND id != ?";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute([$subjectData['name'], $current->grade_id, $subjectId]);
                
                if ($checkStmt->fetch()) {
                    $_SESSION['error'] = "Subject name already exists in this grade.";
                    return false;
                }
                
                $updates[] = "name = ?";
                $params[] = $subjectData['name'];
            }
            
            if (isset($subjectData['code'])) {
                $updates[] = "code = ?";
                $params[] = $subjectData['code'];
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $params[] = $subjectId;
            
            $sql = "UPDATE subjects SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating subject: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Toggle subject active status
     */
    public function toggleActive($subjectId)
    {
        try {
            $sql = "UPDATE subjects SET is_active = NOT is_active WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$subjectId]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error toggling subject status: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Delete subject (only if no scores)
     */
    public function delete($subjectId)
    {
        try {
            // Check if subject has scores
            $scoreSql = "SELECT COUNT(*) as count FROM scores WHERE subject_id = ?";
            $scoreStmt = $this->db->prepare($scoreSql);
            $scoreStmt->execute([$subjectId]);
            $scoreCount = $scoreStmt->fetch(PDO::FETCH_OBJ);
            
            if ($scoreCount->count > 0) {
                $_SESSION['error'] = "Cannot delete subject with recorded scores.";
                return false;
            }
            
            $sql = "DELETE FROM subjects WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$subjectId]);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting subject: " . $e->getMessage();
            return false;
        }
    }
}
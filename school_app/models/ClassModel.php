<?php
// models/ClassModel.php
require_once __DIR__ . '/Model.php';

class ClassModel extends Model
{
    /**
     * Get all classes with grade information
     */
    public static function getAll($filters = [])
    {
        $db = Database::connect();

        $sql = "SELECT c.*, g.name as grade_name, g.grade_number
                FROM classes c
                JOIN grades g ON c.grade_id = g.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['grade_id'])) {
            $sql .= " AND c.grade_id = ?";
            $params[] = $filters['grade_id'];
        }

        if (isset($filters['is_active'])) {
            $sql .= " AND c.is_active = ?";
            $params[] = $filters['is_active'];
        }

        $sql .= " ORDER BY g.grade_number, c.name";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find class by ID with grade info
     */
    public static function find($id)
    {
        $db = Database::connect();

        $sql = "SELECT c.*, g.name as grade_name, g.grade_number
                FROM classes c
                JOIN grades g ON c.grade_id = g.id
                WHERE c.id = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get all classes for a specific grade
     */
    public static function getByGrade($gradeId, $activeOnly = true)
    {
        $db = Database::connect();

        $sql = "SELECT * FROM classes WHERE grade_id = ?";
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
     * Get all active classes
     */
    public static function getActiveClasses()
    {
        $db = Database::connect();

        $sql = "SELECT c.*, g.name as grade_name, g.grade_number
                FROM classes c
                JOIN grades g ON c.grade_id = g.id
                WHERE c.is_active = 1
                ORDER BY g.grade_number, c.name";

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Create new class
     */
    public function create($classData)
    {
        try {
            // Check if grade can have more classes (max 6)
            $countSql = "SELECT COUNT(*) as count FROM classes WHERE grade_id = ?";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute([$classData['grade_id']]);
            $count = $countStmt->fetch(PDO::FETCH_OBJ);

            if ($count->count >= 6) {
                $_SESSION['error'] = "Grade already has maximum of 6 classes.";
                return false;
            }

            // Check if class name is unique within this grade
            $nameSql = "SELECT id FROM classes WHERE name = ? AND grade_id = ?";
            $nameStmt = $this->db->prepare($nameSql);
            $nameStmt->execute([$classData['name'], $classData['grade_id']]);

            if ($nameStmt->fetch()) {
                $_SESSION['error'] = "Class name already exists in this grade.";
                return false;
            }

            $sql = "INSERT INTO classes (name, grade_id, room, is_active) 
                    VALUES (?, ?, ?, 1)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $classData['name'],
                $classData['grade_id'],
                $classData['room'] ?? null
            ]);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error creating class: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update class (can only update name and room, NOT grade)
     */
    public function update($classId, $classData)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($classData['name'])) {
                // Check name uniqueness within the grade
                // First get the grade_id of the class
                $gradeStmt = $this->db->prepare("SELECT grade_id FROM classes WHERE id = ?");
                $gradeStmt->execute([$classId]);
                $gradeInfo = $gradeStmt->fetch(PDO::FETCH_OBJ);

                $nameSql = "SELECT id FROM classes WHERE name = ? AND grade_id = ? AND id != ?";
                $nameStmt = $this->db->prepare($nameSql);
                $nameStmt->execute([$classData['name'], $gradeInfo->grade_id, $classId]);

                if ($nameStmt->fetch()) {
                    $_SESSION['error'] = "Class name already exists in this grade.";
                    return false;
                }

                $updates[] = "name = ?";
                $params[] = $classData['name'];
            }

            if (isset($classData['room'])) {
                $updates[] = "room = ?";
                $params[] = $classData['room'];
            }

            if (empty($updates)) {
                return false;
            }

            $params[] = $classId;

            $sql = "UPDATE classes SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute($params);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating class: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Toggle class active status
     */
    public function toggleActive($classId)
    {
        try {
            $sql = "UPDATE classes SET is_active = NOT is_active WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$classId]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error toggling class status: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Delete class (only if no enrollments)
     */
    public function delete($classId)
    {
        try {
            // Check if class has enrollments
            $enrollStmt = $this->db->prepare("SELECT COUNT(*) as count FROM enrollments WHERE class_id = ?");
            $enrollStmt->execute([$classId]);
            $enrollCount = $enrollStmt->fetch(PDO::FETCH_OBJ);

            if ($enrollCount->count > 0) {
                $_SESSION['error'] = "Cannot delete class with enrolled students.";
                return false;
            }

            $sql = "DELETE FROM classes WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$classId]);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting class: " . $e->getMessage();
            return false;
        }
    }
}
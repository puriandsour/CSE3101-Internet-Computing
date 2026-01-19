<?php
// models/Term.php
require_once __DIR__ . '/Model.php';

class Term extends Model
{
    /**
     * Get all terms with optional filters
     */
    public static function getAll($filters = [])
    {
        $db = Database::connect();
        
        $sql = "SELECT t.*, sy.name as school_year_name
                FROM terms t
                JOIN school_years sy ON t.school_year_id = sy.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['school_year_id'])) {
            $sql .= " AND t.school_year_id = ?";
            $params[] = $filters['school_year_id'];
        }
        
        $sql .= " ORDER BY sy.start_date DESC, t.term_number";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find term by ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        
        $sql = "SELECT t.*, sy.name as school_year_name
                FROM terms t
                JOIN school_years sy ON t.school_year_id = sy.id
                WHERE t.id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get all terms for a school year
     */
    public static function getBySchoolYear($schoolYearId)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM terms 
                WHERE school_year_id = ? 
                ORDER BY term_number";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get current term (based on dates and current year)
     */
    public static function getCurrent()
    {
        $db = Database::connect();
        
        $today = date('Y-m-d');
        
        $sql = "SELECT t.*
                FROM terms t
                JOIN school_years sy ON t.school_year_id = sy.id
                WHERE sy.is_current = 1
                  AND t.start_date <= ?
                  AND t.end_date >= ?
                LIMIT 1";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$today, $today]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find term by year and number
     */
    public static function findByYearAndNumber($schoolYearId, $termNumber)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM terms 
                WHERE school_year_id = ? AND term_number = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$schoolYearId, $termNumber]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Create a term
     */
    public function create($termData)
    {
        try {
            // Check if term number already exists for this year
            $checkSql = "SELECT id FROM terms 
                         WHERE school_year_id = ? AND term_number = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$termData['school_year_id'], $termData['term_number']]);
            
            if ($checkStmt->fetch()) {
                $_SESSION['error'] = "Term number already exists for this school year.";
                return false;
            }
            
            // Validate term number (1-3)
            if ($termData['term_number'] < 1 || $termData['term_number'] > 3) {
                $_SESSION['error'] = "Term number must be between 1 and 3.";
                return false;
            }
            
            $sql = "INSERT INTO terms (school_year_id, term_number, name, start_date, end_date) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $termData['school_year_id'],
                $termData['term_number'],
                $termData['name'],
                $termData['start_date'],
                $termData['end_date']
            ]);
            
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error creating term: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update term
     */
    public function update($termId, $termData)
    {
        try {
            $updates = [];
            $params = [];
            
            if (isset($termData['name'])) {
                $updates[] = "name = ?";
                $params[] = $termData['name'];
            }
            
            if (isset($termData['start_date'])) {
                $updates[] = "start_date = ?";
                $params[] = $termData['start_date'];
            }
            
            if (isset($termData['end_date'])) {
                $updates[] = "end_date = ?";
                $params[] = $termData['end_date'];
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $params[] = $termId;
            
            $sql = "UPDATE terms SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating term: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Delete term (only if no scores)
     */
    public function delete($termId)
    {
        try {
            // Check if term has scores
            $scoreSql = "SELECT COUNT(*) as count FROM scores WHERE term_id = ?";
            $scoreStmt = $this->db->prepare($scoreSql);
            $scoreStmt->execute([$termId]);
            $scoreCount = $scoreStmt->fetch(PDO::FETCH_OBJ);
            
            if ($scoreCount->count > 0) {
                $_SESSION['error'] = "Cannot delete term with recorded scores.";
                return false;
            }
            
            $sql = "DELETE FROM terms WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$termId]);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting term: " . $e->getMessage();
            return false;
        }
    }
}
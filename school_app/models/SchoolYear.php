<?php
// models/SchoolYear.php
require_once __DIR__ . '/Model.php';

class SchoolYear extends Model
{
    /**
     * Get all school years
     */
    public static function getAll($activeOnly = false)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM school_years";
        
        if ($activeOnly) {
            $sql .= " WHERE is_current = 1";
        }
        
        $sql .= " ORDER BY start_date DESC";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find school year by ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM school_years WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get current school year
     */
    public static function getCurrent()
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM school_years WHERE is_current = 1 LIMIT 1";
        $stmt = $db->query($sql);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find by name
     */
    public static function findByName($name)
    {
        $db = Database::connect();
        
        $sql = "SELECT * FROM school_years WHERE name = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get terms for this school year
     */
    public function getTerms($schoolYearId)
    {
        $sql = "SELECT * FROM terms WHERE school_year_id = ? ORDER BY term_number";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Create school year
     */
    public function create($yearData)
    {
        try {
            // Check if name already exists
            $checkSql = "SELECT id FROM school_years WHERE name = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$yearData['name']]);
            
            if ($checkStmt->fetch()) {
                $_SESSION['error'] = "School year name already exists.";
                return false;
            }
            
            $sql = "INSERT INTO school_years (name, start_date, end_date, is_current) 
                    VALUES (?, ?, ?, 0)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $yearData['name'],
                $yearData['start_date'],
                $yearData['end_date']
            ]);
            
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error creating school year: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Create school year with 3 terms automatically
     */
    public function createWithTerms($yearData)
    {
        try {
            $this->db->beginTransaction();
            
            // Create the school year
            $yearId = $this->create($yearData);
            
            if (!$yearId) {
                $this->db->rollBack();
                return false;
            }
            
            // Calculate term dates (divide year into 3 equal parts)
            $startDate = new DateTime($yearData['start_date']);
            $endDate = new DateTime($yearData['end_date']);
            
            $totalDays = $startDate->diff($endDate)->days;
            $termDays = floor($totalDays / 3);
            
            // Create 3 terms
            for ($i = 1; $i <= 3; $i++) {
                $termStart = clone $startDate;
                $termStart->modify('+' . (($i - 1) * $termDays) . ' days');
                
                if ($i == 3) {
                    $termEnd = $endDate;
                } else {
                    $termEnd = clone $termStart;
                    $termEnd->modify('+' . ($termDays - 1) . ' days');
                }
                
                $termSql = "INSERT INTO terms (school_year_id, term_number, name, start_date, end_date) 
                            VALUES (?, ?, ?, ?, ?)";
                $termStmt = $this->db->prepare($termSql);
                $termStmt->execute([
                    $yearId,
                    $i,
                    "Term $i",
                    $termStart->format('Y-m-d'),
                    $termEnd->format('Y-m-d')
                ]);
            }
            
            $this->db->commit();
            return $yearId;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error creating school year with terms: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update school year
     */
    public function update($schoolYearId, $yearData)
    {
        try {
            $updates = [];
            $params = [];
            
            if (isset($yearData['name'])) {
                // Check name uniqueness
                $checkSql = "SELECT id FROM school_years WHERE name = ? AND id != ?";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute([$yearData['name'], $schoolYearId]);
                
                if ($checkStmt->fetch()) {
                    $_SESSION['error'] = "School year name already exists.";
                    return false;
                }
                
                $updates[] = "name = ?";
                $params[] = $yearData['name'];
            }
            
            if (isset($yearData['start_date'])) {
                $updates[] = "start_date = ?";
                $params[] = $yearData['start_date'];
            }
            
            if (isset($yearData['end_date'])) {
                $updates[] = "end_date = ?";
                $params[] = $yearData['end_date'];
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $params[] = $schoolYearId;
            
            $sql = "UPDATE school_years SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating school year: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Set a school year as current (only one can be current)
     */
    public function setAsCurrent($schoolYearId)
    {
        try {
            $this->db->beginTransaction();
            
            // Unset all other years
            $sql1 = "UPDATE school_years SET is_current = 0";
            $this->db->exec($sql1);
            
            // Set this year as current
            $sql2 = "UPDATE school_years SET is_current = 1 WHERE id = ?";
            $stmt = $this->db->prepare($sql2);
            $stmt->execute([$schoolYearId]);
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error setting current year: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Delete school year (only if no enrollments)
     */
    public function delete($schoolYearId)
    {
        try {
            // Check if year has enrollments
            $enrollSql = "SELECT COUNT(*) as count FROM enrollments WHERE school_year_id = ?";
            $enrollStmt = $this->db->prepare($enrollSql);
            $enrollStmt->execute([$schoolYearId]);
            $enrollCount = $enrollStmt->fetch(PDO::FETCH_OBJ);
            
            if ($enrollCount->count > 0) {
                $_SESSION['error'] = "Cannot delete school year with enrollments.";
                return false;
            }
            
            $this->db->beginTransaction();
            
            // Delete terms first
            $termSql = "DELETE FROM terms WHERE school_year_id = ?";
            $termStmt = $this->db->prepare($termSql);
            $termStmt->execute([$schoolYearId]);
            
            // Delete school year
            $sql = "DELETE FROM school_years WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$schoolYearId]);
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error deleting school year: " . $e->getMessage();
            return false;
        }
    }
}
<?php
// models/Score.php
require_once __DIR__ . '/Model.php';

class Score extends Model
{
    /**
     * Get all scores with optional filters
     */
    public static function getAll($filters = [])
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    sc.*,
                    s.first_name, s.last_name, s.admission_no,
                    sub.name as subject_name,
                    t.name as term_name,
                    c.name as class_name,
                    u.first_name as teacher_first_name,
                    u.last_name as teacher_last_name
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id
                JOIN students s ON e.student_id = s.id
                JOIN subjects sub ON sc.subject_id = sub.id
                JOIN terms t ON sc.term_id = t.id
                JOIN classes c ON e.class_id = c.id
                JOIN users u ON sc.teacher_user_id = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['term_id'])) {
            $sql .= " AND sc.term_id = ?";
            $params[] = $filters['term_id'];
        }
        
        if (!empty($filters['class_id'])) {
            $sql .= " AND e.class_id = ?";
            $params[] = $filters['class_id'];
        }
        
        if (!empty($filters['subject_id'])) {
            $sql .= " AND sc.subject_id = ?";
            $params[] = $filters['subject_id'];
        }
        
        if (!empty($filters['student_id'])) {
            $sql .= " AND e.student_id = ?";
            $params[] = $filters['student_id'];
        }
        
        $sql .= " ORDER BY s.last_name, s.first_name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find score by ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        
        $sql = "SELECT sc.*, 
                       s.first_name, s.last_name,
                       sub.name as subject_name,
                       t.name as term_name
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id
                JOIN students s ON e.student_id = s.id
                JOIN subjects sub ON sc.subject_id = sub.id
                JOIN terms t ON sc.term_id = t.id
                WHERE sc.id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get scores for a student
     */
    public static function getByStudent($studentId, $termId = null, $subjectId = null)
    {
        $db = Database::connect();
        
        $sql = "SELECT sc.*, 
                       sub.name as subject_name,
                       t.name as term_name,
                       t.term_number
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id
                JOIN subjects sub ON sc.subject_id = sub.id
                JOIN terms t ON sc.term_id = t.id
                WHERE e.student_id = ?";
        
        $params = [$studentId];
        
        if ($termId !== null) {
            $sql .= " AND sc.term_id = ?";
            $params[] = $termId;
        }
        
        if ($subjectId !== null) {
            $sql .= " AND sc.subject_id = ?";
            $params[] = $subjectId;
        }
        
        $sql .= " ORDER BY t.term_number, sub.name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get scores by enrollment
     */
    public static function getByEnrollment($enrollmentId, $termId = null)
    {
        $db = Database::connect();
        
        $sql = "SELECT sc.*, sub.name as subject_name
                FROM scores sc
                JOIN subjects sub ON sc.subject_id = sub.id
                WHERE sc.enrollment_id = ?";
        
        $params = [$enrollmentId];
        
        if ($termId !== null) {
            $sql .= " AND sc.term_id = ?";
            $params[] = $termId;
        }
        
        $sql .= " ORDER BY sub.name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get scores for a class
     */
    public static function getByClass($classId, $termId, $subjectId = null)
    {
        $db = Database::connect();
        
        $sql = "SELECT sc.*, 
                       s.first_name, s.last_name, s.admission_no,
                       sub.name as subject_name
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id
                JOIN students s ON e.student_id = s.id
                JOIN subjects sub ON sc.subject_id = sub.id
                WHERE e.class_id = ? AND sc.term_id = ?";
        
        $params = [$classId, $termId];
        
        if ($subjectId !== null) {
            $sql .= " AND sc.subject_id = ?";
            $params[] = $subjectId;
        }
        
        $sql .= " ORDER BY s.last_name, s.first_name, sub.name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all scores for a subject in a term
     */
    public static function getBySubject($subjectId, $termId)
    {
        $db = Database::connect();
        
        $sql = "SELECT sc.*, s.first_name, s.last_name
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id
                JOIN students s ON e.student_id = s.id
                WHERE sc.subject_id = ? AND sc.term_id = ?
                ORDER BY s.last_name, s.first_name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$subjectId, $termId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Check if score exists
     */
    public function exists($enrollmentId, $subjectId, $termId)
    {
        $sql = "SELECT id FROM scores 
                WHERE enrollment_id = ? AND subject_id = ? AND term_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$enrollmentId, $subjectId, $termId]);
        
        return $stmt->fetch() !== false;
    }

    /**
     * Add a score
     */
    public function add($scoreData)
    {
        try {
            // Validate score is between 0-100
            if ($scoreData['score'] < 0 || $scoreData['score'] > 100) {
                $_SESSION['error'] = "Score must be between 0 and 100.";
                return false;
            }
            
            // Check if score already exists
            if ($this->exists($scoreData['enrollment_id'], $scoreData['subject_id'], $scoreData['term_id'])) {
                $_SESSION['error'] = "Score already exists for this student/subject/term.";
                return false;
            }
            
            $sql = "INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $scoreData['enrollment_id'],
                $scoreData['subject_id'],
                $scoreData['term_id'],
                $scoreData['teacher_user_id'],
                $scoreData['score'],
                $scoreData['remarks'] ?? null
            ]);
            
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error adding score: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update a score
     */
    public function update($scoreId, $scoreData)
    {
        try {
            $updates = [];
            $params = [];
            
            if (isset($scoreData['score'])) {
                if ($scoreData['score'] < 0 || $scoreData['score'] > 100) {
                    $_SESSION['error'] = "Score must be between 0 and 100.";
                    return false;
                }
                $updates[] = "score = ?";
                $params[] = $scoreData['score'];
            }
            
            if (isset($scoreData['remarks'])) {
                $updates[] = "remarks = ?";
                $params[] = $scoreData['remarks'];
            }
            
            if (empty($updates)) {
                return false;
            }
            
            $params[] = $scoreId;
            
            $sql = "UPDATE scores SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating score: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update or create score (upsert)
     */
    public function updateOrCreate($enrollmentId, $subjectId, $termId, $score, $teacherId, $remarks = null)
    {
        try {
            // Check if exists
            $checkSql = "SELECT id FROM scores 
                         WHERE enrollment_id = ? AND subject_id = ? AND term_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$enrollmentId, $subjectId, $termId]);
            $existing = $checkStmt->fetch(PDO::FETCH_OBJ);
            
            if ($existing) {
                // Update
                return $this->update($existing->id, ['score' => $score, 'remarks' => $remarks]);
            } else {
                // Create
                return $this->add([
                    'enrollment_id' => $enrollmentId,
                    'subject_id' => $subjectId,
                    'term_id' => $termId,
                    'teacher_user_id' => $teacherId,
                    'score' => $score,
                    'remarks' => $remarks
                ]);
            }
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error saving score: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Batch insert scores
     */
    public function addBatch($scoresArray)
    {
        try {
            $this->db->beginTransaction();
            
            $successCount = 0;
            
            foreach ($scoresArray as $scoreData) {
                if ($this->updateOrCreate(
                    $scoreData['enrollment_id'],
                    $scoreData['subject_id'],
                    $scoreData['term_id'],
                    $scoreData['score'],
                    $scoreData['teacher_user_id'],
                    $scoreData['remarks'] ?? null
                )) {
                    $successCount++;
                }
            }
            
            $this->db->commit();
            return $successCount;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error batch adding scores: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Delete score
     */
    public function delete($scoreId)
    {
        try {
            $sql = "DELETE FROM scores WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$scoreId]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting score: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Calculate average score for a subject in a term
     */
    public function calculateAverage($subjectId, $termId, $gradeId = null)
    {
        $sql = "SELECT AVG(sc.score) as average
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id";
        
        $params = [$subjectId, $termId];
        
        if ($gradeId !== null) {
            $sql .= " JOIN classes c ON e.class_id = c.id
                      WHERE sc.subject_id = ? AND sc.term_id = ? AND c.grade_id = ?";
            $params[] = $gradeId;
        } else {
            $sql .= " WHERE sc.subject_id = ? AND sc.term_id = ?";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->average ? round($result->average, 2) : 0;
    }

    /**
     * Calculate student's average for a term
     */
    public function getStudentAverage($studentId, $termId)
    {
        $sql = "SELECT AVG(sc.score) as average
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id
                WHERE e.student_id = ? AND sc.term_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId, $termId]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        return $result->average ? round($result->average, 2) : 0;
    }
}
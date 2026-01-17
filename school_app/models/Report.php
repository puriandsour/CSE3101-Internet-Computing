<?php
// models/Report.php
require_once __DIR__ . '/Model.php';

class Report extends Model
{
    /**
     * Generate complete student report card for a term
     * This is one of the REQUIRED reports
     */
    public static function getStudentReportCard($studentId, $termId)
    {
        $db = Database::connect();
        
        // Get student and enrollment info
        $studentSql = "SELECT 
                        s.id, s.admission_no, s.first_name, s.last_name,
                        c.name as class_name,
                        g.name as grade_name, g.grade_number,
                        t.name as term_name, t.term_number,
                        sy.name as school_year_name
                    FROM students s
                    JOIN enrollments e ON s.id = e.student_id
                    JOIN classes c ON e.class_id = c.id
                    JOIN grades g ON c.grade_id = g.id
                    JOIN terms t ON t.id = ?
                    JOIN school_years sy ON e.school_year_id = sy.id
                    WHERE s.id = ? AND e.status = 'ACTIVE' AND sy.is_current = 1
                    LIMIT 1";
        
        $studentStmt = $db->prepare($studentSql);
        $studentStmt->execute([$termId, $studentId]);
        $studentInfo = $studentStmt->fetch(PDO::FETCH_OBJ);
        
        if (!$studentInfo) {
            return null;
        }
        
        // Get all scores for this student in this term
        $scoresSql = "SELECT 
                        sub.name as subject_name,
                        sc.score,
                        sc.remarks
                    FROM scores sc
                    JOIN enrollments e ON sc.enrollment_id = e.id
                    JOIN subjects sub ON sc.subject_id = sub.id
                    WHERE e.student_id = ? AND sc.term_id = ?
                    ORDER BY sub.name";
        
        $scoresStmt = $db->prepare($scoresSql);
        $scoresStmt->execute([$studentId, $termId]);
        $scores = $scoresStmt->fetchAll(PDO::FETCH_OBJ);
        
        // Calculate average
        $avgSql = "SELECT AVG(sc.score) as average
                   FROM scores sc
                   JOIN enrollments e ON sc.enrollment_id = e.id
                   WHERE e.student_id = ? AND sc.term_id = ?";
        
        $avgStmt = $db->prepare($avgSql);
        $avgStmt->execute([$studentId, $termId]);
        $avgResult = $avgStmt->fetch(PDO::FETCH_OBJ);
        
        return [
            'student' => $studentInfo,
            'scores' => $scores,
            'average' => $avgResult->average ? round($avgResult->average, 2) : 0,
            'total_subjects' => count($scores)
        ];
    }

    /**
     * Get grade-level average by subject for a term
     * This is the REQUIRED "Average Performance Report"
     */
    public static function getGradeAveragesBySubject($gradeId, $termId)
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    sub.id as subject_id,
                    sub.name as subject_name,
                    AVG(sc.score) as average_score,
                    COUNT(DISTINCT e.student_id) as student_count,
                    MIN(sc.score) as lowest_score,
                    MAX(sc.score) as highest_score
                FROM scores sc
                JOIN enrollments e ON sc.enrollment_id = e.id
                JOIN classes c ON e.class_id = c.id
                JOIN subjects sub ON sc.subject_id = sub.id
                WHERE c.grade_id = ? 
                  AND sc.term_id = ?
                  AND e.status = 'ACTIVE'
                GROUP BY sub.id, sub.name
                ORDER BY sub.name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$gradeId, $termId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get class performance summary
     */
    public static function getClassPerformance($classId, $termId)
    {
        $db = Database::connect();
        
        // Get class info
        $classSql = "SELECT c.name as class_name, g.name as grade_name
                     FROM classes c
                     JOIN grades g ON c.grade_id = g.id
                     WHERE c.id = ?";
        $classStmt = $db->prepare($classSql);
        $classStmt->execute([$classId]);
        $classInfo = $classStmt->fetch(PDO::FETCH_OBJ);
        
        // Get subject averages for this class
        $subjectSql = "SELECT 
                        sub.name as subject_name,
                        AVG(sc.score) as average_score,
                        COUNT(*) as student_count
                    FROM scores sc
                    JOIN enrollments e ON sc.enrollment_id = e.id
                    JOIN subjects sub ON sc.subject_id = sub.id
                    WHERE e.class_id = ? AND sc.term_id = ?
                    GROUP BY sub.id, sub.name
                    ORDER BY sub.name";
        
        $subjectStmt = $db->prepare($subjectSql);
        $subjectStmt->execute([$classId, $termId]);
        $subjects = $subjectStmt->fetchAll(PDO::FETCH_OBJ);
        
        // Get overall class average
        $avgSql = "SELECT AVG(sc.score) as class_average
                   FROM scores sc
                   JOIN enrollments e ON sc.enrollment_id = e.id
                   WHERE e.class_id = ? AND sc.term_id = ?";
        
        $avgStmt = $db->prepare($avgSql);
        $avgStmt->execute([$classId, $termId]);
        $avgResult = $avgStmt->fetch(PDO::FETCH_OBJ);
        
        return [
            'class_info' => $classInfo,
            'subjects' => $subjects,
            'class_average' => $avgResult->class_average ? round($avgResult->class_average, 2) : 0
        ];
    }

    /**
     * Get class ranking for a term
     */
    public static function getClassRanking($classId, $termId)
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    s.id, s.admission_no, s.first_name, s.last_name,
                    AVG(sc.score) as average_score,
                    COUNT(sc.id) as subjects_taken
                FROM students s
                JOIN enrollments e ON s.id = e.student_id
                JOIN scores sc ON e.id = sc.enrollment_id
                WHERE e.class_id = ? AND sc.term_id = ? AND e.status = 'ACTIVE'
                GROUP BY s.id, s.admission_no, s.first_name, s.last_name
                ORDER BY average_score DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$classId, $termId]);
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Add ranking
        $rank = 1;
        foreach ($results as $result) {
            $result->rank = $rank;
            $result->average_score = round($result->average_score, 2);
            $rank++;
        }
        
        return $results;
    }

    /**
     * Get grade ranking for a term
     */
    public static function getGradeRanking($gradeId, $termId)
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    s.id, s.admission_no, s.first_name, s.last_name,
                    c.name as class_name,
                    AVG(sc.score) as average_score,
                    COUNT(sc.id) as subjects_taken
                FROM students s
                JOIN enrollments e ON s.id = e.student_id
                JOIN classes c ON e.class_id = c.id
                JOIN scores sc ON e.id = sc.enrollment_id
                WHERE c.grade_id = ? AND sc.term_id = ? AND e.status = 'ACTIVE'
                GROUP BY s.id, s.admission_no, s.first_name, s.last_name, c.name
                ORDER BY average_score DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$gradeId, $termId]);
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Add ranking
        $rank = 1;
        foreach ($results as $result) {
            $result->rank = $rank;
            $result->average_score = round($result->average_score, 2);
            $rank++;
        }
        
        return $results;
    }

    /**
     * Compare student performance across two terms
     */
    public static function compareStudentPerformance($studentId, $fromTermId, $toTermId)
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    sub.name as subject_name,
                    MAX(CASE WHEN sc.term_id = ? THEN sc.score END) as term1_score,
                    MAX(CASE WHEN sc.term_id = ? THEN sc.score END) as term2_score
                FROM subjects sub
                LEFT JOIN scores sc ON sub.id = sc.subject_id
                LEFT JOIN enrollments e ON sc.enrollment_id = e.id
                WHERE e.student_id = ? AND sc.term_id IN (?, ?)
                GROUP BY sub.id, sub.name
                ORDER BY sub.name";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$fromTermId, $toTermId, $studentId, $fromTermId, $toTermId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get subject trends across 3 terms in a year
     */
    public static function getSubjectTrends($subjectId, $schoolYearId)
    {
        $db = Database::connect();
        
        $sql = "SELECT 
                    t.term_number,
                    t.name as term_name,
                    AVG(sc.score) as average_score,
                    COUNT(sc.id) as student_count
                FROM terms t
                LEFT JOIN scores sc ON t.id = sc.term_id AND sc.subject_id = ?
                WHERE t.school_year_id = ?
                GROUP BY t.id, t.term_number, t.name
                ORDER BY t.term_number";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$subjectId, $schoolYearId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get students by performance threshold
     */
    public static function getStudentsByPerformance($termId, $threshold, $operator = '>')
    {
        $db = Database::connect();
        
        $validOperators = ['>', '<', '>=', '<=', '='];
        if (!in_array($operator, $validOperators)) {
            $operator = '>';
        }
        
        $sql = "SELECT 
                    s.id, s.admission_no, s.first_name, s.last_name,
                    c.name as class_name,
                    g.name as grade_name,
                    AVG(sc.score) as average_score
                FROM students s
                JOIN enrollments e ON s.id = e.student_id
                JOIN classes c ON e.class_id = c.id
                JOIN grades g ON c.grade_id = g.id
                JOIN scores sc ON e.id = sc.enrollment_id
                WHERE sc.term_id = ? AND e.status = 'ACTIVE'
                GROUP BY s.id, s.admission_no, s.first_name, s.last_name, c.name, g.name
                HAVING average_score $operator ?
                ORDER BY average_score DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$termId, $threshold]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
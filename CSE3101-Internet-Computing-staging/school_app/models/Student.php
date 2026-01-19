<?php
require_once __DIR__ . '/Model.php';

class Student extends Model {
    protected $table = 'students';
    
    public static function getAll($filters = []) {
        $db = Database::connect();
        $where = [];
        $params = [];
        
        if (!empty($filters['is_active'])) {
            $where[] = "s.is_active = ?";
            $params[] = $filters['is_active'];
        }
        
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $sql = "
            SELECT 
                s.*,
                g.name as grade_name,
                c.name as class_name
            FROM students s
            LEFT JOIN enrollments e ON s.id = e.student_id AND e.status = 'ACTIVE'
            LEFT JOIN classes c ON e.class_id = c.id
            LEFT JOIN grades g ON c.grade_id = g.id
            $whereClause
            ORDER BY s.admission_no
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public static function findById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public static function getByClass($classId) {
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT s.* 
            FROM students s
            JOIN enrollments e ON s.id = e.student_id
            WHERE e.class_id = ? AND e.status = 'ACTIVE' AND s.is_active = 1
            ORDER BY s.admission_no
        ");
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function create($data) {
        $db = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO students (admission_no, first_name, last_name, date_of_birth, gender, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, 1, NOW())
        ");
        
        $stmt->execute([
            $data['admission_no'],
            $data['first_name'],
            $data['last_name'],
            $data['date_of_birth'] ?? null,
            $data['gender'] ?? null
        ]);
        
        return $db->lastInsertId();
    }
    
    public function save($data) {
        return $this->create([
            'admission_no' => $data[0],
            'first_name' => $data[1],
            'last_name' => $data[2],
            'date_of_birth' => $data[3] ?? null,
            'gender' => $data[4] ?? null
        ]);
    }
}

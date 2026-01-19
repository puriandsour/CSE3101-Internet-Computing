<?php
require_once __DIR__ . '/Model.php';

class Subject extends Model {
    protected $table = 'subjects';
    
    public static function getAll() {
        $db = Database::connect();
        $stmt = $db->query("
            SELECT s.*, g.name as grade_name
            FROM subjects s
            LEFT JOIN grades g ON s.grade_id = g.id
            ORDER BY g.id, s.name
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function create($data) {
        $db = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO subjects (name, grade_id, code, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $data['name'],
            $data['grade_id'],
            $data['code'] ?? null
        ]);
        
        return $db->lastInsertId();
    }
}

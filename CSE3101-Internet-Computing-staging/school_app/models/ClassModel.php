<?php
require_once __DIR__ . '/Model.php';

class ClassModel extends Model {
    protected $table = 'classes';
    
    public static function getAll() {
        $db = Database::connect();
        $stmt = $db->query("
            SELECT c.*, g.name as grade_name
            FROM classes c
            LEFT JOIN grades g ON c.grade_id = g.id
            WHERE c.is_active = 1
            ORDER BY g.id, c.name
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public static function find($id) {
        $db = Database::connect();
        $stmt = $db->prepare("
            SELECT c.*, g.name as grade_name
            FROM classes c
            LEFT JOIN grades g ON c.grade_id = g.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function create($data) {
        $db = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO classes (name, grade_id, room, is_active, created_at)
            VALUES (?, ?, ?, 1, NOW())
        ");
        
        $stmt->execute([
            $data['name'],
            $data['grade_id'],
            $data['room'] ?? null
        ]);
        
        return $db->lastInsertId();
    }
}

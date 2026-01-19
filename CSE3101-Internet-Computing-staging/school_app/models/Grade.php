<?php
require_once __DIR__ . '/Model.php';

class Grade extends Model {
    protected $table = 'grades';
    
    public static function getAll() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM grades ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

<?php
// models/Permission.php
require_once __DIR__ . '/Model.php';

class Permission extends Model
{
    /**
     * Get all permissions
     */
    public static function getAll()
    {
        $db = Database::connect();
        $sql = "SELECT * FROM permissions ORDER BY code";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find permission by ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM permissions WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find permission by code
     */
    public static function findByCode($code)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM permissions WHERE code = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}

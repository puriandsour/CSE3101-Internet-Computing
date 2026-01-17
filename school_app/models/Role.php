<?php
// models/Role.php
require_once __DIR__ . '/Model.php';

class Role extends Model
{
    /**
     * Get all roles
     */
    public static function getAll()
    {
        $db = Database::connect();
        $sql = "SELECT * FROM roles ORDER BY name";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find role by ID
     */
    public static function find($id)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM roles WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find role by name
     */
    public static function findByName($name)
    {
        $db = Database::connect();
        $sql = "SELECT * FROM roles WHERE name = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get permissions for a role
     */
    public function getPermissions($roleId)
    {
        $sql = "SELECT p.* 
                FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                WHERE rp.role_id = ?
                ORDER BY p.code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Create new role (if needed)
     */
    public function create($name, $description = null)
    {
        try {
            $sql = "INSERT INTO roles (name, description) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name, $description]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error creating role: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Assign permission to role
     */
    public function assignPermission($roleId, $permissionId)
    {
        try {
            $sql = "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$roleId, $permissionId]);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error assigning permission: " . $e->getMessage();
            return false;
        }
    }
}

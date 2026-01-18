<?php
// models/User.php
require_once __DIR__ . '/Model.php';

class User extends Model
{
    // ========================================
    // RETRIEVE METHODS
    // ========================================

    /**
     * Get all users with their roles
     * 
     * @param array $filters Optional: ['is_active', 'role']
     * @return array Array of user objects with role information
     */
    public static function getAll($filters = [])
    {
        $db = Database::connect();

        $sql = "SELECT 
                    u.*,
                    r.id as role_id,
                    r.name as role_name,
                    r.description as role_description
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                WHERE 1=1";

        $params = [];

        if (isset($filters['is_active'])) {
            $sql .= " AND u.is_active = ?";
            $params[] = $filters['is_active'];
        }

        if (!empty($filters['role'])) {
            $sql .= " AND r.name = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['search'])) {
            $searchTerm = "%{$filters['search']}%";
            $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.username LIKE ? OR u.email LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY u.last_name, u.first_name";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find user by email
     * 
     * @param string $email User email
     * @return object|false User object or false
     */
    public static function findByEmail($email)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find user by ID
     * 
     * @param int $id User ID
     * @return object|false User object or false
     */
    public static function findById($id)
    {
        $db = Database::connect();
        $sql = "SELECT 
                    u.*,
                    r.id as role_id,
                    r.name as role_name,
                    r.description as role_description
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                WHERE u.id = ? LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find user by username
     * 
     * @param string $username Username
     * @return object|false User object or false
     */
    public static function findByUsername($username)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get all users with a specific role
     * 
     * @param int $roleId Role ID
     * @return array Array of user objects
     */
    public static function getUsersWithRole($roleId)
    {
        $db = Database::connect();

        $sql = "SELECT u.*
                FROM users u
                JOIN user_roles ur ON u.id = ur.user_id
                WHERE ur.role_id = ? AND u.is_active = 1
                ORDER BY u.last_name, u.first_name";

        $stmt = $db->prepare($sql);
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all roles for a user
     * 
     * @param int $userId User ID
     * @return array Array of role objects
     */
    public function getRoles($userId)
    {
        $sql = "SELECT r.*
                FROM roles r
                JOIN user_roles ur ON r.id = ur.role_id
                WHERE ur.user_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get all permissions for a user (through their roles)
     * 
     * @param int $userId User ID
     * @return array Array of permission objects
     */
    public function getPermissions($userId)
    {
        $sql = "SELECT DISTINCT p.*
                FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                JOIN user_roles ur ON rp.role_id = ur.role_id
                WHERE ur.user_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Check if user has a specific permission
     * 
     * @param int $userId User ID
     * @param string $permissionCode Permission code (e.g., 'MANAGE_SCORES')
     * @return bool True if user has permission
     */
    public function hasPermission($userId, $permissionCode)
    {
        $sql = "SELECT COUNT(*) as count
                FROM permissions p
                JOIN role_permissions rp ON p.id = rp.permission_id
                JOIN user_roles ur ON rp.role_id = ur.role_id
                WHERE ur.user_id = ? AND p.code = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $permissionCode]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        return $result->count > 0;
    }

    // ========================================
    // CREATE METHODS
    // ========================================

    /**
     * Create a new user with role
     * Expects array: [username, email, password_hash, first_name, last_name, is_active]
     * 
     * @param array $userData User data array
     * @param int $roleId Role ID to assign
     * @return int|false User ID on success, false on failure
     */
    public function save($userData, $roleId)
    {
        try {
            // Validate user data
            $validation = $this->validateUserData($userData);
            if ($validation !== true) {
                $_SESSION['error'] = $validation;
                return false;
            }

            $this->db->beginTransaction();

            // 1. Insert User
            $sql = "INSERT INTO users (username, email, password_hash, first_name, last_name, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($userData);

            $userId = $this->db->lastInsertId();

            // 2. Assign Role
            $sqlRole = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
            $stmtRole = $this->db->prepare($sqlRole);
            $stmtRole->execute([$userId, $roleId]);

            $this->db->commit();
            return $userId;

        } catch (PDOException $e) {
            $this->db->rollBack();

            // Check for unique constraint violation
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Username or email already exists.";
            } else {
                $_SESSION['error'] = "Error creating user: " . $e->getMessage();
            }
            return false;
        }
    }

    // ========================================
    // UPDATE METHODS
    // ========================================

    /**
     * Update user information
     * 
     * @param int $userId User ID
     * @param array $userData Data to update (username, email, first_name, last_name, is_active)
     * @return bool Success status
     */
    public function update($userId, $userData)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($userData['username'])) {
                if ($this->usernameExists($userData['username'], $userId)) {
                    $_SESSION['error'] = "Username already exists.";
                    return false;
                }
                $updates[] = "username = ?";
                $params[] = $userData['username'];
            }

            if (isset($userData['email'])) {
                if ($this->emailExists($userData['email'], $userId)) {
                    $_SESSION['error'] = "Email already exists.";
                    return false;
                }
                $updates[] = "email = ?";
                $params[] = $userData['email'];
            }

            if (isset($userData['first_name'])) {
                $updates[] = "first_name = ?";
                $params[] = $userData['first_name'];
            }

            if (isset($userData['last_name'])) {
                $updates[] = "last_name = ?";
                $params[] = $userData['last_name'];
            }

            if (isset($userData['is_active'])) {
                $updates[] = "is_active = ?";
                $params[] = $userData['is_active'];
            }

            if (empty($updates)) {
                return false;
            }

            $params[] = $userId;

            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating user: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update user password
     * 
     * @param int $userId User ID
     * @param string $newPassword New password (will be hashed)
     * @return bool Success status
     */
    public function updatePassword($userId, $newPassword)
    {
        try {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$passwordHash, $userId]);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating password: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Assign a role to a user
     * 
     * @param int $userId User ID
     * @param int $roleId Role ID
     * @return bool Success status
     */
    public function assignRole($userId, $roleId)
    {
        try {
            // Check if role already assigned
            $checkSql = "SELECT COUNT(*) as count FROM user_roles WHERE user_id = ? AND role_id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$userId, $roleId]);
            $exists = $checkStmt->fetch(PDO::FETCH_OBJ);

            if ($exists->count > 0) {
                $_SESSION['error'] = "User already has this role.";
                return false;
            }

            $sql = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$userId, $roleId]);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error assigning role: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Remove a role from a user
     * 
     * @param int $userId User ID
     * @param int $roleId Role ID
     * @return bool Success status
     */
    public function removeRole($userId, $roleId)
    {
        try {
            $sql = "DELETE FROM user_roles WHERE user_id = ? AND role_id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$userId, $roleId]);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error removing role: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Update user's primary role
     * 
     * @param int $userId User ID
     * @param int $roleId New Role ID
     * @return bool Success status
     */
    public function updateRole($userId, $roleId)
    {
        try {
            $this->db->beginTransaction();

            // 1. Delete existing roles (assuming 1-role per user for simplicity)
            $sqlDelete = "DELETE FROM user_roles WHERE user_id = ?";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->execute([$userId]);

            // 2. Assign new role
            $sqlInsert = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([$userId, $roleId]);

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Error updating user role: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Toggle user active status
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function toggleActive($userId)
    {
        try {
            $sql = "UPDATE users SET is_active = NOT is_active WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$userId]);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error toggling user status: " . $e->getMessage();
            return false;
        }
    }

    // ========================================
    // DELETE METHODS
    // ========================================

    /**
     * Delete a user (soft delete by setting is_active = 0)
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function delete($userId)
    {
        try {
            // Check if user has created scores (as a teacher)
            $scoreStmt = $this->db->prepare("SELECT COUNT(*) as count FROM scores WHERE teacher_user_id = ?");
            $scoreStmt->execute([$userId]);
            $scoreCount = $scoreStmt->fetch(PDO::FETCH_OBJ);

            if ($scoreCount->count > 0) {
                $_SESSION['error'] = "Cannot delete user who has recorded scores. Consider deactivating instead.";
                return false;
            }

            // Soft delete
            $sql = "UPDATE users SET is_active = 0 WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([$userId]);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
            return false;
        }
    }

    // ========================================
    // VALIDATION METHODS
    // ========================================

    /**
     * Validate user data
     * 
     * @param array $data User data
     * @return bool|string True if valid, error message if invalid
     */
    public function validateUserData($data)
    {
        if (empty($data[0]) || strlen($data[0]) < 3) { // username
            return "Username is required (minimum 3 characters).";
        }

        if (empty($data[1]) || !filter_var($data[1], FILTER_VALIDATE_EMAIL)) { // email
            return "Valid email is required.";
        }

        if (empty($data[2])) { // password_hash
            return "Password is required.";
        }

        if (empty($data[3]) || strlen($data[3]) < 2) { // first_name
            return "First name is required (minimum 2 characters).";
        }

        if (empty($data[4]) || strlen($data[4]) < 2) { // last_name
            return "Last name is required (minimum 2 characters).";
        }

        return true;
    }

    /**
     * Check if email exists
     * 
     * @param string $email Email address
     * @param int|null $excludeUserId User ID to exclude from check
     * @return bool True if exists
     */
    private function emailExists($email, $excludeUserId = null)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $params = [$email];

        if ($excludeUserId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }

    /**
     * Check if username exists
     * 
     * @param string $username Username
     * @param int|null $excludeUserId User ID to exclude from check
     * @return bool True if exists
     */
    private function usernameExists($username, $excludeUserId = null)
    {
        $sql = "SELECT id FROM users WHERE username = ?";
        $params = [$username];

        if ($excludeUserId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch() !== false;
    }
}
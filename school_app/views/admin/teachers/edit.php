<?php
/**
 * Edit User
 */
$db = Database::connect();
$stmt = $db->query("SELECT * FROM roles ORDER BY name");
$roles = $stmt->fetchAll(PDO::FETCH_OBJ);

// Get user's current role
$roleStmt = $db->prepare("
    SELECT r.name 
    FROM user_roles ur 
    JOIN roles r ON ur.role_id = r.id 
    WHERE ur.user_id = ?
");
$roleStmt->execute([$user->id]);
$currentRole = $roleStmt->fetch(PDO::FETCH_OBJ);
?>

<div class="edit-user-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Edit User</h1>
        <a href="index.php?controller=user" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
            Back to Users
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="padding: 12px 16px; background-color: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
        <form method="POST" action="index.php?controller=user&action=update">
            <input type="hidden" name="id" value="<?php echo $user->id; ?>">
            
            <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Personal Information</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">First Name *</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($user->first_name); ?>" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Last Name *</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($user->last_name); ?>" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Email *</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>
            </div>

            <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 24px 0 16px 0;">Account Details</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Username *</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user->username); ?>" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 14px;" readonly>
                    <small style="color: #64748b; font-size: 12px;">Username cannot be changed</small>
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">New Password</label>
                    <input type="password" name="password" minlength="8"
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                    <small style="color: #64748b; font-size: 12px;">Leave blank to keep current password</small>
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Role *</label>
                    <select name="role" required
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo htmlspecialchars($role->name); ?>" 
                                    <?php echo ($currentRole && $currentRole->name === $role->name) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; color: #1e293b; font-size: 14px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?php echo $user->is_active ? 'checked' : ''; ?>
                           style="width: 18px; height: 18px; cursor: pointer;">
                    Active User
                </label>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-primary" 
                        style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">
                    Update User
                </button>
                <a href="index.php?controller=user" class="btn-secondary" 
                   style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none; display: inline-block;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>


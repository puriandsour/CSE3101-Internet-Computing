<?php
/**
 * Add New User (Teacher/Admin)
 */
$db = Database::connect();
$stmt = $db->query("SELECT * FROM roles ORDER BY name");
$roles = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="add-user-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Add New User</h1>
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
        <form method="POST" action="index.php?controller=user&action=create">
            <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Personal Information</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">First Name *</label>
                    <input type="text" name="first_name" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Last Name *</label>
                    <input type="text" name="last_name" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Email *</label>
                    <input type="email" name="email" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>
            </div>

            <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 24px 0 16px 0;">Account Details</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Username *</label>
                    <input type="text" name="username" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Password *</label>
                    <input type="password" name="password" required minlength="8"
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                    <small style="color: #64748b; font-size: 12px;">Minimum 8 characters</small>
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Role *</label>
                    <select name="role_id" required
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role->id; ?>">
                                <?php echo htmlspecialchars($role->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-primary" 
                        style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">
                    Add User
                </button>
                <a href="index.php?controller=user" class="btn-secondary" 
                   style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none; display: inline-block;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

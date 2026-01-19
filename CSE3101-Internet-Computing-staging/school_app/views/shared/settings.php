<?php
/**
 * Settings Page
 */
$userId = $_SESSION['user_id'];
$db = Database::connect();
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_OBJ);
?>

<div class="settings-container">
    <div style="margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Settings</h1>
    </div>

    <!-- Account Settings -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Account Settings</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <div>
                <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Username</label>
                <input type="text" value="<?php echo htmlspecialchars($user->username); ?>" disabled
                       style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 14px; color: #94a3b8;">
            </div>

            <div>
                <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Email</label>
                <input type="email" value="<?php echo htmlspecialchars($user->email); ?>" disabled
                       style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 14px; color: #94a3b8;">
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Change Password</h3>
        
        <form method="POST" action="index.php?controller=settings&action=changePassword">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Current Password</label>
                    <input type="password" name="current_password" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">New Password</label>
                    <input type="password" name="new_password" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Confirm Password</label>
                    <input type="password" name="confirm_password" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>
            </div>

            <button type="submit" class="btn-primary" 
                    style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">
                Update Password
            </button>
        </form>
    </div>

    <!-- System Preferences -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
        <h3 style="font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">System Preferences</h3>
        
        <div style="margin-bottom: 12px;">
            <label style="display: flex; align-items: center; gap: 10px; color: #1e293b; font-size: 14px; cursor: pointer;">
                <input type="checkbox" style="width: 18px; height: 18px; cursor: pointer;">
                Enable email notifications
            </label>
        </div>

        <div style="margin-bottom: 12px;">
            <label style="display: flex; align-items: center; gap: 10px; color: #1e293b; font-size: 14px; cursor: pointer;">
                <input type="checkbox" checked style="width: 18px; height: 18px; cursor: pointer;">
                Show dashboard statistics
            </label>
        </div>

        <div>
            <label style="display: flex; align-items: center; gap: 10px; color: #1e293b; font-size: 14px; cursor: pointer;">
                <input type="checkbox" checked style="width: 18px; height: 18px; cursor: pointer;">
                Auto-save form data
            </label>
        </div>
    </div>
</div>

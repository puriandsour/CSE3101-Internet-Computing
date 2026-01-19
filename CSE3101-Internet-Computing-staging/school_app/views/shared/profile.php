<?php
/**
 * User Profile
 */
$userId = $_SESSION['user_id'];
$db = Database::connect();
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_OBJ);
?>

<div class="profile-container">
    <div style="margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">My Profile</h1>
    </div>

    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">First Name</label>
                <div style="padding: 10px 12px; background-color: #f8fafc; border-radius: 8px; color: #1e293b; font-size: 14px;">
                    <?php echo htmlspecialchars($user->first_name ?? 'N/A'); ?>
                </div>
            </div>

            <div>
                <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Last Name</label>
                <div style="padding: 10px 12px; background-color: #f8fafc; border-radius: 8px; color: #1e293b; font-size: 14px;">
                    <?php echo htmlspecialchars($user->last_name ?? 'N/A'); ?>
                </div>
            </div>

            <div>
                <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Username</label>
                <div style="padding: 10px 12px; background-color: #f8fafc; border-radius: 8px; color: #1e293b; font-size: 14px;">
                    <?php echo htmlspecialchars($user->username ?? 'N/A'); ?>
                </div>
            </div>

            <div>
                <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Email</label>
                <div style="padding: 10px 12px; background-color: #f8fafc; border-radius: 8px; color: #1e293b; font-size: 14px;">
                    <?php echo htmlspecialchars($user->email ?? 'N/A'); ?>
                </div>
            </div>

            <div>
                <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Role</label>
                <div style="padding: 10px 12px; background-color: #f8fafc; border-radius: 8px; color: #1e293b; font-size: 14px;">
                    <?php echo htmlspecialchars($_SESSION['role'] ?? 'N/A'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

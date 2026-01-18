<?php
/**
 * Shared Profile View
 * Data: $user (Object from User::findById)
 */
$avatar = "https://ui-avatars.com/api/?name=" . urlencode($user->first_name . ' ' . $user->last_name) . "&background=f1f5f9&color=6366f1&size=128";
?>

<div class="profile-view-container" style="max-width: 900px; margin: 0 auto; padding: 40px 20px;">

    <div style="display: flex; align-items: flex-start; gap: 40px; margin-bottom: 48px;">
        <!-- Avatar Section -->
        <div style="flex-shrink: 0;">
            <img src="<?php echo $avatar; ?>" alt="Profile"
                style="width: 160px; height: 160px; border-radius: 50%; border: 4px solid #f8fafc; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);">
        </div>

        <!-- User Info -->
        <div style="flex-grow: 1; padding-top: 10px;">
            <h1 style="font-size: 42px; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">
                <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>
            </h1>
            <div style="font-size: 18px; color: #64748b; font-weight: 600; margin-bottom: 20px;">
                <?php echo htmlspecialchars($user->role_name ?? 'User'); ?>
            </div>
            <div
                style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; background-color: #ecfdf5; color: #059669; border-radius: 20px; font-size: 14px; font-weight: 700; border: 1px solid #d1fae5;">
                <span style="width: 8px; height: 8px; background-color: #10b981; border-radius: 50%;"></span>
                Account Status:
                <?php echo ($user->is_active) ? 'Active' : 'Inactive'; ?>
            </div>
        </div>
    </div>

    <!-- Audit Info Section -->
    <div style="margin-bottom: 48px;">
        <h2
            style="font-size: 20px; font-weight: 700; color: #334155; margin-bottom: 24px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;">
            Audit Info
        </h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
            <div class="card"
                style="padding: 24px; border-radius: 16px; background-color: #f8fafc; border: 1px solid #f1f5f9;">
                <div
                    style="font-size: 14px; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">
                    Account Created</div>
                <div style="font-size: 18px; color: #1e293b; font-weight: 600;">
                    <?php echo date('F d, Y', strtotime($user->created_at)); ?>
                </div>
            </div>
            <div class="card"
                style="padding: 24px; border-radius: 16px; background-color: #f8fafc; border: 1px solid #f1f5f9;">
                <div
                    style="font-size: 14px; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">
                    Last Updated</div>
                <div style="font-size: 18px; color: #1e293b; font-weight: 600;">
                    <?php echo date('F d, Y', strtotime($user->updated_at)); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Section -->
    <div style="display: flex; gap: 16px;">
        <a href="index.php?controller=profile&action=changePassword" class="btn-primary"
            style="background-color: #2563eb; color: white; padding: 14px 28px; border-radius: 10px; font-weight: 700; text-decoration: none; border: none; cursor: pointer;">
            Reset Password
        </a>
        <button onclick="showLogoutModal()"
            style="background-color: #f1f5f9; color: #1e293b; padding: 14px 28px; border-radius: 10px; font-weight: 700; border: 1px solid #e2e8f0; cursor: pointer;">
            Logout
        </button>
    </div>
</div>
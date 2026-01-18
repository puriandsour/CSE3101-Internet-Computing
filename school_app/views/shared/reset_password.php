<?php
/**
 * Change Password View
 */
?>

<div class="reset-password-container" style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
    <h1 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Change Password</h1>
    <p style="color: #64748b; font-size: 16px; margin-bottom: 32px;">For security reasons, please update your password.
    </p>

    <form action="index.php?controller=profile&action=updatePassword" method="POST"
        style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Current Password -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">Current Password</label>
            <input type="password" name="current_password" placeholder="Enter your current password" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px;">
        </div>

        <!-- New Password -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">New Password</label>
            <input type="password" name="new_password" placeholder="Enter your new password" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px;">
        </div>

        <!-- Confirm New Password -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm your new password" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px;">
        </div>

        <!-- Hint Text -->
        <p style="font-size: 13px; color: #6366f1; line-height: 1.5; margin: 0;">
            Password must be at least 8 characters long and include a mix of uppercase and lowercase letters, numbers,
            and symbols.
        </p>

        <!-- Actions -->
        <div style="display: flex; gap: 16px; margin-top: 8px;">
            <button type="submit"
                style="padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; background-color: #1e3a8a; border: none; color: white; cursor: pointer;">
                Update Password
            </button>
            <a href="index.php?controller=profile&action=index"
                style="padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; background-color: #f1f5f9; border: none; color: #1e293b; text-decoration: none; text-align: center;">
                Cancel
            </a>
        </div>
    </form>
</div>
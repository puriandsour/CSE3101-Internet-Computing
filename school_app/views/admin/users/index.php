<?php
/**
 * User List View
 * Displays all system users (Staff/Teachers) with role info.
 */
$users = $data['users'] ?? [];
?>

<div class="user-list-header"
    style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 0;">Manage Users</h1>
        <p style="color: #64748b; margin-top: 4px;">View and manage system access for all staff members.</p>
    </div>
    <a href="index.php?controller=user&action=add" class="add-user-btn"
        style="background: #2563eb; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add staff
    </a>
</div>

<div class="user-table-card"
    style="background: white; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                    <th
                        style="padding: 16px 24px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">
                        Staff Member</th>
                    <th
                        style="padding: 16px 24px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">
                        Username / Email</th>
                    <th
                        style="padding: 16px 24px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">
                        Role</th>
                    <th
                        style="padding: 16px 24px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">
                        Status</th>
                    <th
                        style="padding: 16px 24px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em;">
                        Joined</th>
                </tr>
            </thead>
            <tbody style="color: #1e293b;">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" style="padding: 48px; text-align: center; color: #94a3b8; font-style: italic;">No
                            users found in the system.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 16px 24px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div
                                        style="width: 40px; height: 40px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2563eb; font-weight: 700;">
                                        <?php echo strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a;">
                                            <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>
                                        </div>
                                        <div style="font-size: 13px; color: #64748b;">ID: #
                                            <?php echo $user->id; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px 24px;">
                                <div style="font-weight: 600; color: #334155;">
                                    <?php echo htmlspecialchars($user->username); ?>
                                </div>
                                <div style="font-size: 13px; color: #94a3b8;">
                                    <?php echo htmlspecialchars($user->email); ?>
                                </div>
                            </td>
                            <td style="padding: 16px 24px;">
                                <?php
                                $roleColor = ($user->role_name === 'OFFICE_ADMIN') ? '#6366f1' : '#0ea5e9';
                                $roleLabel = ($user->role_name === 'OFFICE_ADMIN') ? 'Office Admin' : 'Teacher';
                                ?>
                                <span
                                    style="display: inline-flex; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; background: <?php echo $roleColor; ?>15; color: <?php echo $roleColor; ?>;">
                                    <?php echo $roleLabel; ?>
                                </span>
                            </td>
                            <td style="padding: 16px 24px;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div
                                        style="width: 8px; height: 8px; border-radius: 50%; background: <?php echo $user->is_active ? '#22c55e' : '#94a3b8'; ?>;">
                                    </div>
                                    <span
                                        style="font-size: 14px; font-weight: 600; color: <?php echo $user->is_active ? '#15803d' : '#64748b'; ?>;">
                                        <?php echo $user->is_active ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                            </td>
                            <td style="padding: 16px 24px; font-size: 14px; color: #64748b;">
                                <?php echo date('M j, Y', strtotime($user->created_at)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    tbody tr:hover {
        background: #f8fafc;
    }

    .add-user-btn:hover {
        background: #1d4ed8 !important;
        transform: translateY(-1px);
    }
</style>
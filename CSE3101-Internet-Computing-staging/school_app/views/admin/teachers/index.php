<?php
/**
 * Admin Teachers List
 */

$db = Database::connect();
$stmt = $db->query("
    SELECT 
        u.*,
        r.name as role_name
    FROM users u
    JOIN user_roles ur ON u.id = ur.user_id
    JOIN roles r ON ur.role_id = r.id
    WHERE r.name = 'TEACHER'
    ORDER BY u.first_name, u.last_name
");
$teachers = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="teachers-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Manage Users (Staff/Teachers)</h1>
        <a href="index.php?controller=user&action=add" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; text-decoration: none;">
            Add User
        </a>
    </div>

    <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Name</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Username</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Email</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Role</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($teachers)): ?>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($teacher->first_name . ' ' . $teacher->last_name); ?>
                            </td>
                            <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($teacher->username); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($teacher->email); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($teacher->role_name); ?>
                            </td>
                            <td style="padding: 16px;">
                                <?php if ($teacher->is_active): ?>
                                    <span style="padding: 4px 8px; background-color: #dcfce7; color: #16a34a; border-radius: 4px; font-size: 12px; font-weight: 500;">Active</span>
                                <?php else: ?>
                                    <span style="padding: 4px 8px; background-color: #fee2e2; color: #dc2626; border-radius: 4px; font-size: 12px; font-weight: 500;">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="padding: 48px; text-align: center; color: #94a3b8; font-size: 15px;">
                            No teachers found. <a href="index.php?controller=user&action=add" style="color: #2563eb;">Add your first teacher</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

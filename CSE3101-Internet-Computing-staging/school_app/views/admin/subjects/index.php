<?php
/**
 * Admin Subjects View
 */

$subjects = Subject::getAll();
?>

<div class="subjects-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Subjects</h1>
        <a href="index.php?controller=subject&action=add" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; text-decoration: none;">
            Add Subject
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="padding: 12px 16px; background-color: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; color: #166534; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Subject Name</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Grade</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Code</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($subject->name); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($subject->grade_name ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($subject->code ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px;">
                                <a href="index.php?controller=subject&action=delete&id=<?php echo $subject->id; ?>" 
                                   onclick="return confirm('Delete this subject?')"
                                   style="color: #dc2626; font-size: 13px; text-decoration: none;">
                                    🗑️ Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 48px; text-align: center; color: #94a3b8; font-size: 15px;">
                            No subjects found. <a href="index.php?controller=subject&action=add" style="color: #2563eb;">Add your first subject</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

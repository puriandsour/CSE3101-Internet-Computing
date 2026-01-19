<?php
/**
 * View Class Details
 * Data: $class, $students
 */
?>

<div class="view-class-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">
                <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
            </h1>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">
                Room: <?php echo htmlspecialchars($class->room ?? 'Not Set'); ?>
            </p>
        </div>
        <a href="index.php?controller=teacher&action=classes" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
            Back to Classes
        </a>
    </div>

    <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Admission No</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Name</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Gender</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($student->admission_no); ?>
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($student->first_name . ' ' . $student->last_name); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($student->gender ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo $student->date_of_birth ? date('M d, Y', strtotime($student->date_of_birth)) : 'N/A'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 48px; text-align: center; color: #94a3b8; font-size: 15px;">
                            No students in this class yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

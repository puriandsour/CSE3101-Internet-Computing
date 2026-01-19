<?php
/**
 * Teacher Classes View
 * Data provided by TeacherController: $classes
 */
?>

<div class="classes-container">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">My Classes</h1>
    </div>

    <!-- Classes Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        <?php if (!empty($classes)): ?>
            <?php foreach ($classes as $class): ?>
                <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
                    <div style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <h3 style="font-size: 16px; font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?></h3>
                            <span style="padding: 4px 8px; background-color: #dcfce7; color: #16a34a; border-radius: 4px; font-size: 12px; font-weight: 500;">Active</span>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <p style="color: #64748b; font-size: 14px; margin: 8px 0;"><strong style="color: #1e293b;">Grade:</strong> <?php echo htmlspecialchars($class->grade_name ?? 'N/A'); ?></p>
                        <p style="color: #64748b; font-size: 14px; margin: 8px 0;"><strong style="color: #1e293b;">Room:</strong> <?php echo htmlspecialchars($class->room ?? 'Not Set'); ?></p>
                        <p style="color: #64748b; font-size: 14px; margin: 8px 0;"><strong style="color: #1e293b;">Students:</strong> <?php echo $class->student_count ?? 0; ?></p>
                        <p style="color: #64748b; font-size: 14px; margin: 8px 0;"><strong style="color: #1e293b;">Subjects:</strong> <?php echo $class->subject_count ?? 0; ?></p>
                    </div>
                    <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9; display: flex; gap: 8px;">
                        <a href="index.php?controller=score&action=enter&class=<?php echo $class->id; ?>" class="btn-primary" style="padding: 8px 16px; border-radius: 6px; font-size: 13px; background-color: #2563eb; color: #fff; text-decoration: none;">Enter Scores</a>
                        <a href="index.php?controller=teacher&action=viewClass&id=<?php echo $class->id; ?>" class="btn-secondary" style="padding: 8px 16px; border-radius: 6px; font-size: 13px; background-color: #f1f5f9; color: #334155; text-decoration: none;">View Students</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card" style="padding: 48px; text-align: center; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; grid-column: 1 / -1;">
                <p style="color: #94a3b8; font-size: 15px;">No classes assigned. Please contact the administrator to assign classes to you.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

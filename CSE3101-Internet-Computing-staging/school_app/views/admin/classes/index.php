<?php
/**
 * Admin Classes View
 */

$classes = ClassModel::getAll();
?>

<div class="classes-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Classes</h1>
        <a href="index.php?controller=class&action=add" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; text-decoration: none;">
            Add Class
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="padding: 12px 16px; background-color: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; color: #166534; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        <?php if (!empty($classes)): ?>
            <?php foreach ($classes as $class): ?>
                <div class="card" style="padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h3 style="font-size: 16px; font-weight: 600; color: #1e293b;">
                            <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                        </h3>
                        <span style="padding: 4px 8px; background-color: #dcfce7; color: #16a34a; border-radius: 4px; font-size: 12px; font-weight: 500;">Active</span>
                    </div>
                    <p style="color: #64748b; font-size: 14px; margin: 6px 0;">
                        <strong>Room:</strong> <?php echo htmlspecialchars($class->room ?? 'Not Set'); ?>
                    </p>
                    <p style="color: #64748b; font-size: 14px; margin: 6px 0;">
                        <strong>Grade:</strong> <?php echo htmlspecialchars($class->grade_name); ?>
                    </p>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
                        <a href="index.php?controller=class&action=delete&id=<?php echo $class->id; ?>" 
                           onclick="return confirm('Delete this class?')"
                           style="color: #dc2626; font-size: 13px; text-decoration: none;">
                            🗑️ Delete
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card" style="padding: 48px; text-align: center; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; grid-column: 1 / -1;">
                <p style="color: #94a3b8; font-size: 15px;">No classes found. <a href="index.php?controller=class&action=add" style="color: #2563eb;">Add your first class</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>

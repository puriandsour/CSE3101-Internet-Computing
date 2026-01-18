<?php
/**
 * Admin Classes List View
 * Data: $classes, $grades, $selectedGrade
 */
?>

<div class="classes-container" style="padding: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; margin: 0;">Classes</h1>
        <a href="index.php?controller=class&action=add" class="btn-primary"
            style="background-color: #f1f5f9; color: #1e293b; padding: 10px 20px; border-radius: 8px; font-weight: 700; border: 1px solid #e2e8f0; text-decoration: none;">
            Add Class
        </a>
    </div>

    <!-- Grade Filter Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 32px; overflow-x: auto; padding-bottom: 8px;">
        <a href="index.php?controller=class&action=index"
            style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; 
                  <?php echo !$selectedGrade ? 'background-color: #e2e8f0; color: #1e293b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #f1f5f9;'; ?>">
            All Grades
        </a>
        <?php foreach ($grades as $grade): ?>
            <a href="index.php?controller=class&action=index&grade_id=<?php echo $grade->id; ?>"
                style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; 
                      <?php echo $selectedGrade == $grade->id ? 'background-color: #e2e8f0; color: #1e293b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #f1f5f9;'; ?>">
                <?php echo htmlspecialchars($grade->name); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php
    // Group classes by grade
    $groupedClasses = [];
    foreach ($classes as $class) {
        $groupedClasses[$class->grade_name][] = $class;
    }

    if (empty($groupedClasses)): ?>
        <div
            style="text-align: center; padding: 60px; background: #f8fafc; border-radius: 24px; border: 2px dashed #e2e8f0;">
            <p style="color: #64748b; font-size: 18px; font-weight: 500;">No classes found.</p>
            <a href="index.php?controller=class&action=add"
                style="color: #2563eb; font-weight: 700; text-decoration: none;">Create your first class</a>
        </div>
    <?php else: ?>
        <?php foreach ($groupedClasses as $gradeName => $gradeClasses): ?>
            <div style="margin-bottom: 48px;">
                <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 24px;">
                    <?php echo htmlspecialchars($gradeName); ?>
                </h2>
                <div class="card" style="padding: 0; overflow: hidden; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                                <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 40%;">
                                    Class Name</th>
                                <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 30%;">
                                    Grade</th>
                                <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 30%;">
                                    Student Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gradeClasses as $class): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 20px 24px; font-size: 16px; font-weight: 600; color: #1e293b;">
                                        <?php echo htmlspecialchars($class->name); ?>
                                    </td>
                                    <td style="padding: 20px 24px; font-size: 16px; color: #6366f1; font-weight: 500;">
                                        <?php echo htmlspecialchars($class->grade_name); ?>
                                    </td>
                                    <td style="padding: 20px 24px; font-size: 16px; color: #6366f1; font-weight: 500;">
                                        <?php echo $class->student_count; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
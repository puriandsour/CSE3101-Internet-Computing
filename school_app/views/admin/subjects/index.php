<?php
/**
 * Admin Subjects List View
 * Data: $subjects, $grades, $selectedGrade
 */
?>

<div class="subjects-container" style="padding: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; margin: 0;">Subjects</h1>
        <a href="index.php?controller=subject&action=add" class="btn-primary"
            style="background-color: #f1f5f9; color: #1e293b; padding: 10px 20px; border-radius: 8px; font-weight: 700; border: 1px solid #e2e8f0; text-decoration: none;">
            Add Subject
        </a>
    </div>

    <!-- Grade Filter Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 32px; overflow-x: auto; padding-bottom: 8px;">
        <a href="index.php?controller=subject&action=index"
            style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; 
                  <?php echo !$selectedGrade ? 'background-color: #e2e8f0; color: #1e293b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #f1f5f9;'; ?>">
            All Grades
        </a>
        <?php foreach ($grades as $grade): ?>
            <a href="index.php?controller=subject&action=index&grade_id=<?php echo $grade->id; ?>"
                style="padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; text-decoration: none; 
                      <?php echo $selectedGrade == $grade->id ? 'background-color: #e2e8f0; color: #1e293b;' : 'background-color: #f8fafc; color: #64748b; border: 1px solid #f1f5f9;'; ?>">
                <?php echo htmlspecialchars($grade->name); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 12px; border: 1px solid #f1f5f9;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 60%;">
                        Subject Name</th>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 40%;">Grade
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="2" style="padding: 40px; text-align: center; color: #64748b; font-size: 16px;">No
                            subjects found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subjects as $subject): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 20px 24px; font-size: 16px; font-weight: 600; color: #1e293b;">
                                <?php echo htmlspecialchars($subject->name); ?>
                            </td>
                            <td style="padding: 20px 24px; font-size: 16px; color: #6366f1; font-weight: 500;">
                                <?php echo htmlspecialchars($subject->grade_name); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
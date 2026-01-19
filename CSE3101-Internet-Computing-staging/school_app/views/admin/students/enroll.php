<?php
/**
 * Enroll Student in Class
 * Data: $student, $classes, $schoolYears
 */
?>

<div class="enroll-student-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Enroll Student</h1>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">
                <?php echo htmlspecialchars($student->first_name . ' ' . $student->last_name); ?> 
                (<?php echo htmlspecialchars($student->admission_no); ?>)
            </p>
        </div>
        <a href="index.php?controller=student" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
            Back to Students
        </a>
    </div>

    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
        <form method="POST" action="index.php?controller=student&action=enroll">
            <input type="hidden" name="student_id" value="<?php echo $student->id; ?>">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Class *</label>
                    <select name="class_id" required
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <option value="">Choose a class...</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class->id; ?>">
                                <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select School Year *</label>
                    <select name="school_year_id" required
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <option value="">Choose a school year...</option>
                        <?php if (!empty($schoolYears)): ?>
                            <?php foreach ($schoolYears as $year): ?>
                                <option value="<?php echo $year->id; ?>">
                                    <?php echo htmlspecialchars($year->name); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No school years available</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-primary" 
                        style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">
                    Enroll Student
                </button>
                <a href="index.php?controller=student" class="btn-secondary" 
                   style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none; display: inline-block;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <?php if (empty($schoolYears)): ?>
        <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff8e1; margin-top: 20px;">
            <p style="color: #f59e0b; font-size: 14px; margin: 0;">
                ⚠️ No school years available. Please create a school year first before enrolling students.
            </p>
        </div>
    <?php endif; ?>
</div>

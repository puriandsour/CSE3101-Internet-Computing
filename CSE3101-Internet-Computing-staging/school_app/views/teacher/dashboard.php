<?php
/**
 * Teacher Dashboard View
 * Data provided by TeacherController: $myClasses, $myStudents, $scoresEntered, $classes
 */
?>

<div class="dashboard-container">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Dashboard</h1>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Teacher'); ?></p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
            <div style="color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 8px;">My Classes</div>
            <div style="font-size: 36px; font-weight: 700; color: #1e293b;"><?php echo $myClasses ?? 0; ?></div>
        </div>
        <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
            <div style="color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Total Students</div>
            <div style="font-size: 36px; font-weight: 700; color: #1e293b;"><?php echo $myStudents ?? 0; ?></div>
        </div>
        <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
            <div style="color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Scores Entered</div>
            <div style="font-size: 36px; font-weight: 700; color: #1e293b;"><?php echo $scoresEntered ?? 0; ?></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Quick Actions</h2>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="index.php?controller=teacher&action=classes" class="btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; text-decoration: none;">View My Classes</a>
            <a href="index.php?controller=score&action=enter" class="btn-secondary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">Enter Scores</a>
            <a href="index.php?controller=report&action=generate" class="btn-secondary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">Generate Report</a>
        </div>
    </div>

    <!-- My Classes Grid -->
    <div>
        <h2 style="font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">My Classes</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php if (!empty($classes)): ?>
                <?php foreach ($classes as $class): ?>
                    <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;" 
                         onclick="window.location='index.php?controller=teacher&action=viewClass&id=<?php echo $class->id; ?>'"
                         onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.boxShadow='none'">
                        <div style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?></h3>
                                <span style="padding: 4px 8px; background-color: #dbeafe; color: #2563eb; border-radius: 4px; font-size: 12px; font-weight: 500;">Active</span>
                            </div>
                        </div>
                        <div style="padding: 20px;">
                            <p style="color: #64748b; font-size: 14px; margin: 4px 0;"><strong>Room:</strong> <?php echo htmlspecialchars($class->room ?? 'Not Set'); ?></p>
                            <p style="color: #64748b; font-size: 14px; margin: 4px 0;"><strong>Students:</strong> <?php echo $class->student_count ?? 0; ?></p>
                        </div>
                        <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9; background-color: #f8fafc;">
                            <a href="index.php?controller=score&action=enter&class=<?php echo $class->id; ?>" class="btn-primary" style="padding: 8px 16px; border-radius: 6px; font-size: 13px; background-color: #2563eb; color: #fff; text-decoration: none; display: inline-block;">Enter Scores</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card" style="padding: 48px; text-align: center; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
                    <p style="color: #94a3b8; font-size: 15px;">No classes assigned yet. Contact admin for class assignments.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
/**
 * Enroll Student View
 * Data: $student, $years, $classes, $randomAvatar
 */
?>

<div class="enroll-student-container" style="max-width: 800px; margin: 0 auto; padding: 40px 20px;">
    <h1 class="text-h1" style="font-weight: 700; font-size: 36px; color: #1e293b; margin-bottom: 32px;">Enroll Student
    </h1>

    <div class="student-info-section" style="margin-bottom: 32px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
            <?php echo htmlspecialchars($student->first_name . ' ' . $student->last_name); ?>
        </h2>
        <p style="color: #22c55e; font-weight: 600; font-size: 16px; margin: 4px 0 0 0;">Grade
            <?php echo htmlspecialchars($student->grade_number ?? 'N/A'); ?>
        </p>
    </div>

    <!-- Student Avatar -->
    <div class="avatar-container" style="display: flex; justify-content: flex-start; margin-bottom: 40px;">
        <div style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; border: 4px solid #f1f5f9;">
            <img src="<?php echo $randomAvatar; ?>" alt="Student Photo"
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <form action="index.php?controller=student&action=enroll&id=<?php echo $student->id; ?>" method="POST"
        class="enroll-student-form" style="display: flex; flex-direction: column; gap: 24px;">

        <!-- School Year -->
        <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 600; font-size: 16px; color: #334155;">School Year</label>
            <select name="school_year_id" class="input-field"
                style="width: 100%; padding: 14px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f0f9ff; font-size: 16px; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748b%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1.2rem top 50%; background-size: 0.65rem auto;"
                required>
                <option value="" disabled selected>Select School Year</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?php echo $year->id; ?>" <?php echo ($year->is_current) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($year->name); ?>
                        <?php echo ($year->is_current) ? '(Current)' : ''; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Class -->
        <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 600; font-size: 16px; color: #334155;">Class</label>
            <select name="class_id" class="input-field"
                style="width: 100%; padding: 14px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f0f9ff; font-size: 16px; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748b%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1.2rem top 50%; background-size: 0.65rem auto;"
                required>
                <option value="" disabled selected>Select Class</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class->id; ?>">
                        <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (isset($_SESSION['warning'])): ?>
            <p style="color: #b45309; font-size: 14px; margin: 0;">
                <?php echo $_SESSION['warning'];
                unset($_SESSION['warning']); ?>
            </p>
        <?php endif; ?>

        <!-- Enrollment Button -->
        <button type="submit" class="btn-primary"
            style="margin-top: 16px; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 16px; background-color: #1e3a8a; border: none; color: white; cursor: pointer; text-align: center;">Enroll
            Student</button>

    </form>
</div>
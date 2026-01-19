<?php
/**
 * Generate Report View
 * Data provided by ReportController: $classes, $terms
 */
?>

<div class="generate-report-container">
    <!-- Header Section -->
    <div style="margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Generate Report Card</h1>
    </div>

    <!-- Form Card -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; margin-bottom: 20px;">
        <form method="POST" action="index.php?controller=report&action=create">
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Report Parameters</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Class *</label>
                        <select name="class_id" id="classSelect" class="input-field" required onchange="loadClassStudents()"
                                style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px; color: #1e293b;">
                            <option value="">Choose class...</option>
                            <?php if (!empty($classes)): ?>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class->id; ?>">
                                        <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Student *</label>
                        <select name="student_id" id="studentSelect" class="input-field" required
                                style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px; color: #1e293b;">
                            <option value="">Choose student...</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Term *</label>
                        <select name="term_id" class="input-field" required
                                style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px; color: #1e293b;">
                            <option value="">Choose term...</option>
                            <?php if (!empty($terms)): ?>
                                <?php foreach ($terms as $term): ?>
                                    <option value="<?php echo $term->id; ?>">
                                        <?php echo htmlspecialchars($term->name . ' - ' . ($term->school_year_name ?? '')); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Report Type *</label>
                        <select name="report_type" class="input-field" required
                                style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px; color: #1e293b;">
                            <option value="full">Full Report Card</option>
                            <option value="summary">Summary Report</option>
                            <option value="progress">Progress Report</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 16px;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #1e293b; font-size: 14px; margin-bottom: 8px; cursor: pointer;">
                        <input type="checkbox" name="include_remarks" value="1" checked style="width: 16px; height: 16px; cursor: pointer;">
                        Include teacher remarks
                    </label>
                </div>

                <div>
                    <label style="display: flex; align-items: center; gap: 8px; color: #1e293b; font-size: 14px; cursor: pointer;">
                        <input type="checkbox" name="include_attendance" value="1" checked style="width: 16px; height: 16px; cursor: pointer;">
                        Include attendance record
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-primary" style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">Generate Report</button>
                <a href="index.php?controller=report&action=index" class="btn-secondary" style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none; display: inline-block;">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #f8fafc;">
        <h4 style="font-size: 15px; font-weight: 600; color: #1e293b; margin: 0 0 12px 0;">📄 Report Will Include:</h4>
        <ul style="margin: 0; padding-left: 20px; color: #64748b; font-size: 14px;">
            <li style="margin: 6px 0;">Student information and photo</li>
            <li style="margin: 6px 0;">Subject-wise grades and scores</li>
            <li style="margin: 6px 0;">Overall average and class position</li>
            <li style="margin: 6px 0;">Teacher's remarks and comments</li>
            <li style="margin: 6px 0;">Attendance summary</li>
            <li style="margin: 6px 0;">School seal and signatures</li>
        </ul>
    </div>
</div>

<script>
function loadClassStudents() {
    const classId = document.getElementById('classSelect').value;
    const studentSelect = document.getElementById('studentSelect');
    
    if (!classId) {
        studentSelect.innerHTML = '<option value="">Choose student...</option>';
        return;
    }
    
    studentSelect.innerHTML = '<option value="">Loading...</option>';
    
    fetch(`index.php?controller=teacher&action=getClassStudents&class_id=${classId}`)
        .then(response => response.json())
        .then(data => {
            studentSelect.innerHTML = '<option value="">Choose student...</option>';
            
            if (data.students && data.students.length > 0) {
                data.students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = `${student.first_name} ${student.last_name} - ${student.admission_no}`;
                    studentSelect.appendChild(option);
                });
            } else {
                studentSelect.innerHTML += '<option value="" disabled>No students in this class</option>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            studentSelect.innerHTML = '<option value="">Error loading students</option>';
        });
}
</script>

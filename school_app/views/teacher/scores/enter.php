<?php
/**
 * Enter Scores View
 * Data provided by ScoreController: $classes, $terms, $subjects
 */
?>

<div class="scores-container">
    <!-- Header Section -->
    <div style="margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Enter Student Scores</h1>
    </div>

    <!-- Form Card -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; margin-bottom: 24px;">
        <form method="POST" action="index.php?controller=score&action=save" id="scoreForm">
            <!-- Selection Section -->
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Select Class and Term</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Class *</label>
                        <select name="class_id" id="classSelect" class="input-field" required onchange="loadStudents()"
                                style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px; color: #1e293b;">
                            <option value="">Choose a class...</option>
                            <?php if (!empty($classes)): ?>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class->id; ?>">
                                        <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No classes assigned</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Term *</label>
                        <select name="term_id" id="termSelect" class="input-field" required
                                style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px; color: #1e293b;">
                            <option value="">Choose a term...</option>
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
                        <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Subject *</label>
                        <select name="subject_id" id="subjectSelect" class="input-field" required
                                style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px; color: #1e293b;">
                            <option value="">Choose a subject...</option>
                            <?php if (!empty($subjects)): ?>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo $subject->id; ?>">
                                        <?php echo htmlspecialchars($subject->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <button type="button" onclick="loadStudents()" class="btn-secondary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; border: none; cursor: pointer;">Load Students</button>
            </div>

            <!-- Students Table (Hidden by default) -->
            <div id="studentsTable" style="display: none;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Enter Scores</h3>
                <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                                <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Admission No</th>
                                <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Student Name</th>
                                <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Score (0-100)</th>
                                <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Students will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-primary" style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">Save All Scores</button>
                    <button type="button" onclick="resetForm()" class="btn-secondary" style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; border: none; cursor: pointer;">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function loadStudents() {
    const classId = document.getElementById('classSelect').value;
    const termId = document.getElementById('termSelect').value;
    const subjectId = document.getElementById('subjectSelect').value;
    
    if (!classId || !termId || !subjectId) {
        alert('Please select class, term, and subject');
        return;
    }
    
    fetch(`index.php?controller=score&action=getStudents&class_id=${classId}&term_id=${termId}&subject_id=${subjectId}`)
        .then(response => response.json())
        .then(data => {
            if (data.students && data.students.length > 0) {
                const tbody = document.getElementById('studentsTableBody');
                tbody.innerHTML = '';
                
                data.students.forEach((student, index) => {
                    const row = document.createElement('tr');
                    row.style.borderBottom = '1px solid #f1f5f9';
                    row.innerHTML = `
                        <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">${student.admission_no}</td>
                        <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">${student.first_name} ${student.last_name}</td>
                        <td style="padding: 16px;">
                            <input type="hidden" name="scores[${index}][student_id]" value="${student.id}">
                            <input type="hidden" name="scores[${index}][enrollment_id]" value="${student.enrollment_id}">
                            <input type="number" name="scores[${index}][score]" 
                                   style="width: 100%; max-width: 120px; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;"
                                   min="0" max="100" value="${student.current_score || ''}" placeholder="0-100" required>
                        </td>
                        <td style="padding: 16px;">
                            <input type="text" name="scores[${index}][remarks]" 
                                   style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;"
                                   value="${student.remarks || ''}" placeholder="Optional remarks">
                        </td>
                    `;
                    tbody.appendChild(row);
                });
                
                document.getElementById('studentsTable').style.display = 'block';
            } else {
                alert('No students found in this class');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading students. Please try again.');
        });
}

function resetForm() {
    document.getElementById('scoreForm').reset();
    document.getElementById('studentsTable').style.display = 'none';
}
</script>

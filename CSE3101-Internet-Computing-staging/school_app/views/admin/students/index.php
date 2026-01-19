<?php
/**
 * Admin Students List View
 * Data provided by StudentController: $students, $pagination, $filters, $grades, $classes
 */
?>

<div class="students-view-container">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Students</h1>
        <a href="index.php?controller=student&action=add" class="btn-secondary"
            style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px;">Add Student</a>
    </div>

    <!-- Search & Filter Section -->
    <div class="card" style="padding: 24px; margin-bottom: 24px; border: none; background-color: #f8fafc;">
        <form action="index.php" method="GET" id="studentFilterForm"
            style="display: flex; flex-direction: column; gap: 16px;">
            <input type="hidden" name="controller" value="student">
            <input type="hidden" name="action" value="index">

            <!-- Search Bar -->
            <div style="position: relative; width: 100%;">
                <input type="text" name="search" value="<?php echo htmlspecialchars($filters['search']); ?>"
                    placeholder="Search a Student..." class="input-field"
                    style="width: 100%; padding: 12px 16px 12px 44px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"
                    width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <!-- Filter Dropdowns -->
            <div style="display: flex; gap: 12px;">
                <select name="grade_id" class="input-field"
                    style="width: auto; min-width: 120px; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;"
                    onchange="this.form.submit()">
                    <option value="">Grade</option>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?php echo $grade->id; ?>" <?php echo ($filters['grade_id'] == $grade->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($grade->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="class_id" class="input-field"
                    style="width: auto; min-width: 120px; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;"
                    onchange="this.form.submit()">
                    <option value="">Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class->id; ?>" <?php echo ($filters['class_id'] == $class->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="card"
        style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Admission No</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Name</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Grade</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Class</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="4" style="padding: 48px; text-align: center; color: #94a3b8; font-size: 15px;">
                            No students found matching your criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; cursor: pointer;"
                            onclick="window.location='index.php?controller=student&action=enroll&id=<?php echo $student->id; ?>'"
                            onmouseover="this.style.backgroundColor='#f8fafc'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($student->admission_no); ?>
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($student->first_name . ' ' . $student->last_name); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($student->grade_name ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($student->class_name ?? 'N/A'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
            <div style="color: #64748b; font-size: 14px;">
                Showing
                <?php echo count($students); ?> of
                <?php echo $pagination['total']; ?> students
            </div>
            <div style="display: flex; gap: 8px;">
                <?php if ($pagination['page'] > 1): ?>
                    <a href="index.php?controller=student&action=index&page=<?php echo $pagination['page'] - 1; ?>&search=<?php echo urlencode($filters['search']); ?>&grade_id=<?php echo $filters['grade_id']; ?>&class_id=<?php echo $filters['class_id']; ?>"
                        class="btn-secondary" style="padding: 8px 16px; border-radius: 6px;">Previous</a>
                <?php endif; ?>

                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="index.php?controller=student&action=index&page=<?php echo $pagination['page'] + 1; ?>&search=<?php echo urlencode($filters['search']); ?>&grade_id=<?php echo $filters['grade_id']; ?>&class_id=<?php echo $filters['class_id']; ?>"
                        class="btn-primary" style="padding: 8px 16px; border-radius: 6px; background-color: #2563eb;">Next</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Add debounce to search input
    let timeout = null;
    document.querySelector('input[name="search"]').addEventListener('input', functio n () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>
<?php
/**
 * Add New Subject
 */
$grades = Grade::getAll();
?>

<div class="add-subject-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Add New Subject</h1>
        <a href="index.php?controller=subject" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
            Back to Subjects
        </a>
    </div>

    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
        <form method="POST" action="index.php?controller=subject&action=create">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Subject Name *</label>
                    <input type="text" name="name" required placeholder="e.g., Mathematics"
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Grade *</label>
                    <select name="grade_id" required
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <option value="">Select Grade</option>
                        <?php foreach ($grades as $grade): ?>
                            <option value="<?php echo $grade->id; ?>"><?php echo htmlspecialchars($grade->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Subject Code</label>
                    <input type="text" name="code" placeholder="e.g., MATH101"
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-primary" 
                        style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">
                    Add Subject
                </button>
                <a href="index.php?controller=subject" class="btn-secondary" 
                   style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none; display: inline-block;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

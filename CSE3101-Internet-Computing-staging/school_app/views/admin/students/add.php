<?php
/**
 * Add New Student
 */
?>

<div class="add-student-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Add New Student</h1>
        <a href="index.php?controller=student" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
            Back to Students
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="padding: 12px 16px; background-color: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
        <form method="POST" action="index.php?controller=student&action=create">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Admission Number *</label>
                    <input type="text" name="admission_no" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">First Name *</label>
                    <input type="text" name="first_name" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Last Name *</label>
                    <input type="text" name="last_name" required
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Date of Birth</label>
                    <input type="date" name="date_of_birth"
                           style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Gender</label>
                    <select name="gender"
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <option value="">Select Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-primary" 
                        style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">
                    Add Student
                </button>
                <a href="index.php?controller=student" class="btn-secondary" 
                   style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none; display: inline-block;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

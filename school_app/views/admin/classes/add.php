<?php
/**
 * Admin Create Class View
 * Data: $grades
 */
?>

<div class="add-class-container" style="max-width: 650px; padding: 40px;">
    <h1 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 48px;">Create New Class</h1>

    <form action="index.php?controller=class&action=create" method="POST"
        style="display: flex; flex-direction: column; gap: 32px;">

        <!-- Grade Select -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">Grade</label>
            <div style="position: relative;">
                <select name="grade_id" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b; appearance: none;">
                    <option value="" disabled selected>Select</option>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?php echo $grade->id; ?>">
                            <?php echo htmlspecialchars($grade->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div
                    style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #2563eb;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Class Name -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">Class Name</label>
            <input type="text" name="name" placeholder="Enter Class Name" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
        </div>

        <!-- Room Number -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">Room Number</label>
            <input type="text" name="room" placeholder="Enter Room Number"
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
        </div>

        <!-- Hint Text -->
        <p style="font-size: 14px; color: #6366f1; font-weight: 600; margin: 0;">
            Maximum 6 classes per grade allowed
        </p>

        <!-- Actions -->
        <div style="display: flex; gap: 16px; margin-top: 16px;">
            <button type="submit"
                style="background-color: #2563eb; color: white; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; border: none; cursor: pointer;">
                Save
            </button>
            <a href="index.php?controller=class&action=index"
                style="background-color: #f1f5f9; color: #1e293b; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; text-decoration: none; text-align: center;">
                Cancel
            </a>
        </div>
    </form>
</div>
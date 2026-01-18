<?php
/**
 * Admin Edit School Year View
 * Data: $year
 */
?>

<div class="edit-year-container" style="max-width: 700px; padding: 40px;">
    <h1 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 48px;">Edit Academic School Year</h1>

    <form action="index.php?controller=schoolYear&action=update&id=<?php echo $year->id; ?>" method="POST"
        style="display: flex; flex-direction: column; gap: 32px;">

        <!-- Year Name -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">School Year Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($year->name); ?>" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <!-- Start Date -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label style="font-weight: 700; font-size: 16px; color: #334155;">Start Date</label>
                <input type="date" name="start_date" value="<?php echo $year->start_date; ?>" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
            </div>

            <!-- End Date -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label style="font-weight: 700; font-size: 16px; color: #334155;">End Date</label>
                <input type="date" name="end_date" value="<?php echo $year->end_date; ?>" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
            </div>
        </div>

        <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px;">
            <p style="color: #92400e; font-size: 14px; margin: 0;">
                <strong>Note:</strong> Editing the year dates will not automatically adjust already generated term
                dates.
                Please update terms manually from the year list view.
            </p>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 16px; margin-top: 16px;">
            <button type="submit"
                style="background-color: #1e3a8a; color: white; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; border: none; cursor: pointer;">
                Update Year
            </button>
            <a href="index.php?controller=schoolYear&action=index"
                style="background-color: #f1f5f9; color: #1e293b; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; text-decoration: none; text-align: center;">
                Cancel
            </a>
        </div>
    </form>
</div>
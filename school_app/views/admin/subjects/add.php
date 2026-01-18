<?php
/**
 * Admin Create Subject View
 * Data: $grades
 */
?>

<div class="add-subject-container" style="max-width: 650px; padding: 40px;">
    <h1 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 48px;">Add New Subject</h1>

    <form action="index.php?controller=subject&action=create" method="POST"
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

        <!-- Subject Name -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">Subject Name</label>
            <input type="text" name="name" placeholder="Enter Subject Name" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
        </div>

        <!-- Subject Code (Optional) -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">Subject Code</label>
            <input type="text" name="code" placeholder="Enter Subject Code (Optional)"
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
        </div>

        <!-- Status Toggle -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
            <span style="font-weight: 700; font-size: 16px; color: #334155;">Active</span>
            <input type="checkbox" name="is_active" id="activeToggle" checked style="display: none;">
            <div id="toggleTrack" onclick="toggleActiveStatus()"
                style="width: 48px; height: 24px; background-color: #2563eb; border-radius: 12px; position: relative; cursor: pointer; transition: background-color 0.3s ease;">
                <div id="toggleKnob"
                    style="width: 20px; height: 20px; background-color: white; border-radius: 50%; position: absolute; left: 26px; top: 2px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: left 0.3s ease;">
                </div>
            </div>
        </div>

        <script>
            function toggleActiveStatus() {
                const checkbox = document.getElementById('activeToggle');
                const track = document.getElementById('toggleTrack');
                const knob = document.getElementById('toggleKnob');
                
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    track.style.backgroundColor = '#2563eb';
                    knob.style.left = '26px';
                } else {
                    track.style.backgroundColor = '#e2e8f0';
                    knob.style.left = '2px';
                }
            }
        </script>

        <!-- Actions -->
        <div style="display: flex; gap: 16px; margin-top: 16px;">
            <button type="submit"
                style="background-color: #1e3a8a; color: white; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; border: none; cursor: pointer;">
                Save Subject
            </button>
            <a href="index.php?controller=subject&action=index"
                style="background-color: #f1f5f9; color: #1e293b; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; text-decoration: none; text-align: center;">
                Cancel
            </a>
        </div>
    </form>
</div>
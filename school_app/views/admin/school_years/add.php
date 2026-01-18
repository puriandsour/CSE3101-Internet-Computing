<?php
/**
 * Admin Create School Year View
 */
?>

<div class="add-year-container" style="max-width: 700px; padding: 40px;">
    <h1 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 48px;">Create Academic School Year</h1>

    <form action="index.php?controller=schoolYear&action=create" method="POST"
        style="display: flex; flex-direction: column; gap: 32px;">

        <!-- Year Name -->
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <label style="font-weight: 700; font-size: 16px; color: #334155;">School Year Name</label>
            <input type="text" name="name" placeholder="e.g., 2025/2026" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <!-- Start Date -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label style="font-weight: 700; font-size: 16px; color: #334155;">Start Date</label>
                <input type="date" name="start_date" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
            </div>

            <!-- End Date -->
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <label style="font-weight: 700; font-size: 16px; color: #334155;">End Date</label>
                <input type="date" name="end_date" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc; font-size: 16px; color: #1e293b;">
            </div>
        </div>

        <!-- Status Toggle (Current Year) -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 0;">
            <span style="font-weight: 700; font-size: 16px; color: #334155;">Set as Current Year</span>
            <input type="checkbox" name="is_current" id="currentYearToggle" style="display: none;">
            <div id="toggleTrack" onclick="toggleCurrentYear()"
                style="width: 48px; height: 24px; background-color: #e2e8f0; border-radius: 12px; position: relative; cursor: pointer; transition: background-color 0.3s ease;">
                <div id="toggleKnob"
                    style="width: 20px; height: 20px; background-color: white; border-radius: 50%; position: absolute; left: 2px; top: 2px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: left 0.3s ease;">
                </div>
            </div>
        </div>

        <script>
            function toggleCurrentYear() {
                const checkbox = document.getElementById('currentYearToggle');
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

        <p style="font-size: 14px; color: #64748b; line-height: 1.5; margin: 0;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"
                style="display: inline-block; vertical-align: middle; margin-right: 4px; color: #2563eb;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Saving will automatically generate 3 default terms for this academic year.
        </p>

        <!-- Actions -->
        <div style="display: flex; gap: 16px; margin-top: 16px;">
            <button type="submit"
                style="background-color: #2563eb; color: white; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; border: none; cursor: pointer;">
                Save
            </button>
            <a href="index.php?controller=schoolYear&action=index"
                style="background-color: #f1f5f9; color: #1e293b; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; text-decoration: none; text-align: center;">
                Cancel
            </a>
        </div>
    </form>
</div>
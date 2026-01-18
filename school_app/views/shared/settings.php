<?php
/**
 * Settings View - Admin Hub
 */
?>

<div class="settings-container" style="padding: 40px;">
    <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; margin-bottom: 48px;">Settings</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">

        <!-- Academic Structure Card -->
        <div class="card"
            style="padding: 32px; border-radius: 20px; display: flex; flex-direction: column; gap: 20px; transition: transform 0.2s ease; cursor: pointer;"
            onclick="window.location.href='index.php?controller=schoolYear&action=index'">
            <div
                style="width: 56px; height: 56px; background-color: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #1e3a8a;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Academic Years</h3>
                <p style="color: #64748b; font-size: 14px; line-height: 1.5; margin: 0;">Manage school years, terms, and
                    set the active academic period.</p>
            </div>
            <div
                style="margin-top: auto; display: flex; align-items: center; gap: 8px; color: #2563eb; font-weight: 700; font-size: 14px;">
                Manage Years
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </div>

        <!-- Grade Management (Predefined but viewable) -->
        <div class="card"
            style="padding: 32px; border-radius: 20px; display: flex; flex-direction: column; gap: 20px; opacity: 0.7;">
            <div
                style="width: 56px; height: 56px; background-color: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Grade Structure</h3>
                <p style="color: #94a3b8; font-size: 14px; line-height: 1.5; margin: 0;">Standard Primary Level (Grades
                    1-6) as per institutional policy.</p>
            </div>
        </div>

    </div>
</div>
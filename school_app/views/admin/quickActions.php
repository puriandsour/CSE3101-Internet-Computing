<?php
/**
 * Quick Actions View
 * Displays a grid of management shortcuts.
 */
?>

<div class="quick-actions-header" style="margin-bottom: 40px;">
    <h1 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 0;">Quick Actions</h1>
</div>

<div class="quick-actions-grid"
    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px;">
    <!-- Add User -->
    <a href="index.php?controller=user&action=add" class="action-card"
        style="text-decoration: none; display: block; background: white; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: all 0.2s ease;">
        <div
            style="width: 48px; height: 48px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #475569; margin-bottom: 16px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <line x1="19" y1="8" x2="19" y2="14"></line>
                <line x1="16" y1="11" x2="22" y2="11"></line>
            </svg>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">Add User</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0; line-height: 1.5;">Create a new user account</p>
    </a>

    <!-- Add School Year -->
    <a href="index.php?controller=schoolYear&action=add" class="action-card"
        style="text-decoration: none; display: block; background: white; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: all 0.2s ease;">
        <div
            style="width: 48px; height: 48px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #475569; margin-bottom: 16px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">Add School Year</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0; line-height: 1.5;">Set up a new academic year</p>
    </a>

    <!-- Add Teacher -->
    <a href="index.php?controller=teacher&action=add" class="action-card"
        style="text-decoration: none; display: block; background: white; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: all 0.2s ease;">
        <div
            style="width: 48px; height: 48px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #475569; margin-bottom: 16px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">Add Teacher</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0; line-height: 1.5;">Register a new teaching staff</p>
    </a>

    <!-- Add Student -->
    <a href="index.php?controller=student&action=add" class="action-card"
        style="text-decoration: none; display: block; background: white; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: all 0.2s ease;">
        <div
            style="width: 48px; height: 48px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #475569; margin-bottom: 16px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
            </svg>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">Add Student</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0; line-height: 1.5;">Enroll a new student</p>
    </a>

    <!-- Add Subject -->
    <a href="index.php?controller=subject&action=add" class="action-card"
        style="text-decoration: none; display: block; background: white; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: all 0.2s ease;">
        <div
            style="width: 48px; height: 48px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #475569; margin-bottom: 16px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">Add Subject</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0; line-height: 1.5;">Introduce a new subject</p>
    </a>

    <!-- Add Class -->
    <a href="index.php?controller=class&action=add" class="action-card"
        style="text-decoration: none; display: block; background: white; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: all 0.2s ease;">
        <div
            style="width: 48px; height: 48px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #475569; margin-bottom: 16px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;">Add Class</h3>
        <p style="font-size: 14px; color: #64748b; margin: 0; line-height: 1.5;">Form a new class group</p>
    </a>
</div>

<style>
    .action-card:hover {
        transform: translateY(-4px);
        border-color: #2563eb !important;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important;
    }

    .action-card:hover h3 {
        color: #2563eb !important;
    }
</style>
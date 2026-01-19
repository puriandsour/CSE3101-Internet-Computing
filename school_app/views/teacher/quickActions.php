<?php
/**
 * Teacher Quick Actions View
 */
?>

<div class="quick-actions-container">
    <!-- Header Section -->
    <div style="margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Quick Actions</h1>
    </div>

    <!-- Actions Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        <a href="index.php?controller=teacher&action=classes" style="text-decoration: none;">
            <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;" 
                 onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='none'">
                <div style="font-size: 32px; margin-bottom: 12px;">🏫</div>
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">My Classes</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">View all your assigned classes and students</p>
            </div>
        </a>

        <a href="index.php?controller=score&action=enter" style="text-decoration: none;">
            <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='none'">
                <div style="font-size: 32px; margin-bottom: 12px;">📊</div>
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Enter Scores</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Enter student scores and assessments</p>
            </div>
        </a>

        <a href="index.php?controller=score&action=manage" style="text-decoration: none;">
            <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='none'">
                <div style="font-size: 32px; margin-bottom: 12px;">🗑️</div>
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Manage Scores</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">View and delete entered scores</p>
            </div>
        </a>

        <a href="index.php?controller=report&action=generate" style="text-decoration: none;">
            <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='none'">
                <div style="font-size: 32px; margin-bottom: 12px;">📄</div>
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Generate Report</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Create student report cards</p>
            </div>
        </a>

        <a href="index.php?controller=report&action=index" style="text-decoration: none;">
            <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='none'">
                <div style="font-size: 32px; margin-bottom: 12px;">📋</div>
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">View Reports</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Access previously generated reports</p>
            </div>
        </a>

        <a href="index.php?controller=profile" style="text-decoration: none;">
            <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='none'">
                <div style="font-size: 32px; margin-bottom: 12px;">👤</div>
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">My Profile</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">View and edit your profile information</p>
            </div>
        </a>

        <a href="index.php?controller=help" style="text-decoration: none;">
            <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; cursor: pointer; transition: box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='none'">
                <div style="font-size: 32px; margin-bottom: 12px;">❓</div>
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Help & Support</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Access help documentation and support</p>
            </div>
        </a>
    </div>
</div>

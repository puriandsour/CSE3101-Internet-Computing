<?php
/**
 * Admin Dashboard View
 * Displays high-level summaries and recent activity.
 */

$yearName = $data['yearName'] ?? '2025-2026';
$studentCount = $data['studentCount'] ?? 0;
$teacherCount = $data['teacherCount'] ?? 0;
$classCount = $data['classCount'] ?? 0;
$activities = $data['activities'] ?? [];
$adminName = $data['adminName'] ?? 'Admin';

function time_ago($datetime)
{
    if (!$datetime)
        return "some time ago";
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60)
        return "Just now";
    if ($diff < 3600)
        return floor($diff / 60) . " mins ago";
    if ($diff < 86400)
        return floor($diff / 3600) . " hours ago";
    if ($diff < 604800)
        return floor($diff / 86400) . " days ago";
    return date("M j, Y", $time);
}
?>

<div class="dashboard-header" style="margin-bottom: 32px;">
    <h1 style="font-size: 32px; font-weight: 800; color: #0f172a; margin: 0;">Dashboard</h1>
    <p style="color: #64748b; margin-top: 8px;">Welcome back,
        <?php echo htmlspecialchars($adminName); ?>
    </p>
</div>

<!-- School Year Badge -->
<div class="year-badge"
    style="background: #f1f5f9; padding: 12px 24px; border-radius: 12px; display: inline-block; margin-bottom: 40px; font-weight: 600; color: #475569;">
    <?php echo htmlspecialchars($yearName); ?>
</div>

<!-- Summary Section -->
<div class="section-title" style="margin-bottom: 20px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Summary</h3>
</div>

<div class="summary-grid"
    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 48px;">
    <!-- Total Students -->
    <div class="summary-card"
        style="background: white; padding: 32px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: transform 0.2s ease;">
        <p
            style="color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; margin: 0 0 12px 0;">
            Total Students</p>
        <h4 style="font-size: 36px; font-weight: 800; color: #0f172a; margin: 0;">
            <?php echo number_format($studentCount); ?>
        </h4>
    </div>

    <!-- Teachers -->
    <div class="summary-card"
        style="background: white; padding: 32px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: transform 0.2s ease;">
        <p
            style="color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; margin: 0 0 12px 0;">
            Teachers</p>
        <h4 style="font-size: 36px; font-weight: 800; color: #0f172a; margin: 0;">
            <?php echo number_format($teacherCount); ?>
        </h4>
    </div>

    <!-- Classes -->
    <div class="summary-card"
        style="background: white; padding: 32px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); transition: transform 0.2s ease;">
        <p
            style="color: #64748b; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; margin: 0 0 12px 0;">
            Classes</p>
        <h4 style="font-size: 36px; font-weight: 800; color: #0f172a; margin: 0;">
            <?php echo number_format($classCount); ?>
        </h4>
    </div>
</div>

<!-- Quick Actions -->
<div class="section-title" style="margin-bottom: 20px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Quick Actions</h3>
</div>

<div class="actions-group" style="display: flex; gap: 16px; margin-bottom: 48px;">
    <a href="index.php?controller=student&action=add" class="btn"
        style="background: #1e3a8a; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
        Add Student
    </a>
    <a href="index.php?controller=user&action=add" class="btn"
        style="background: #f1f5f9; color: #475569; padding: 12px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
        Add User
    </a>
</div>

<!-- Recent Activity -->
<div class="section-title" style="margin-bottom: 24px;">
    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">Recent Activity</h3>
</div>

<div class="activity-timeline" style="position: relative; padding-left: 32px;">
    <?php if (empty($activities)): ?>
        <p style="color: #64748b; font-style: italic;">No recent activity recorded.</p>
    <?php else: ?>
        <!-- Vertical Line -->
        <div style="position: absolute; left: 11px; top: 8px; bottom: 8px; width: 2px; background: #f1f5f9;"></div>

        <?php foreach ($activities as $activity): ?>
            <div class="activity-item" style="position: relative; margin-bottom: 32px;">
                <!-- Dot -->
                <div
                    style="position: absolute; left: -26px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: white; border: 2px solid #cbd5e1; z-index: 1;">
                </div>

                <h5 style="font-size: 16px; font-weight: 600; color: #1e293b; margin: 0;">
                    <?php echo htmlspecialchars($activity['desc']); ?>
                </h5>
                <p style="font-size: 14px; color: #94a3b8; margin: 4px 0 0 0;">
                    <?php echo time_ago($activity['time']); ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .summary-card:hover {
        transform: translateY(-4px);
        border-color: #e2e8f0;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>
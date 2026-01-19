<?php
/**
 * Teacher Reports List View
 * Data provided by ReportController: $reports
 */
?>

<div class="reports-container">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Reports</h1>
        <a href="index.php?controller=report&action=generate" class="btn-primary" style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; text-decoration: none;">Generate New Report</a>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Student</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Admission No</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Class</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Term</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Generated On</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($report->first_name . ' ' . $report->last_name); ?>
                            </td>
                            <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($report->admission_no); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($report->class_name ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($report->term_name ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo isset($report->generated_at) ? date('M d, Y', strtotime($report->generated_at)) : 'N/A'; ?>
                            </td>
                            <td style="padding: 16px;">
                                <a href="index.php?controller=report&action=view&student_id=<?php echo $report->student_id ?? 0; ?>&term_id=1" 
                                   style="color: #2563eb; font-size: 13px; text-decoration: none; margin-right: 12px;">
                                    👁️ View
                                </a>
                                <button onclick="window.print()" style="background: none; border: none; color: #64748b; font-size: 13px; cursor: pointer;">
                                    ⬇️ Download
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 48px; text-align: center; color: #94a3b8; font-size: 15px;">
                            No reports generated yet. <a href="index.php?controller=report&action=generate" style="color: #2563eb; text-decoration: underline;">Generate your first report</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
/**
 * Admin School Years List View
 * Data: $years
 */
?>

<div class="school-years-container" style="padding: 40px;">
    <div style="margin-bottom: 32px; display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px;">
        <span style="color: #0f172a; font-weight: 600;">Academic Years</span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; margin: 0;">Academic Years</h1>
        <a href="index.php?controller=schoolYear&action=add" class="btn-primary"
            style="background-color: #f1f5f9; color: #1e293b; padding: 10px 20px; border-radius: 8px; font-weight: 700; border: 1px solid #e2e8f0; text-decoration: none;">
            Add School Year
        </a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 12px; border: 1px solid #f1f5f9;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 25%;">Year
                        Name</th>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 20%;">Start
                        Date</th>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 20%;">End
                        Date</th>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 20%;">
                        Current Year</th>
                    <th
                        style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 15%; text-align: right;">
                        Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($years)): ?>
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #64748b; font-size: 16px;">No
                            academic years found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($years as $year): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 20px 24px; font-size: 16px; font-weight: 600; color: #1e293b;">
                                <a href="index.php?controller=term&action=index&id=<?php echo $year->id; ?>"
                                    style="color: #1e293b; text-decoration: none; hover: color: #2563eb;">
                                    <?php echo htmlspecialchars($year->name); ?>
                                </a>
                            </td>
                            <td style="padding: 20px 24px; font-size: 16px; color: #6366f1; font-weight: 500;">
                                <?php echo $year->start_date; ?>
                            </td>
                            <td style="padding: 20px 24px; font-size: 16px; color: #6366f1; font-weight: 500;">
                                <?php echo $year->end_date; ?>
                            </td>
                            <td style="padding: 20px 24px;">
                                <?php if ($year->is_current): ?>
                                    <span
                                        style="background-color: #e2e8f0; color: #0f172a; padding: 6px 16px; border-radius: 6px; font-size: 14px; font-weight: 700;">Current</span>
                                <?php else: ?>
                                    <a href="index.php?controller=schoolYear&action=setCurrent&id=<?php echo $year->id; ?>"
                                        style="background-color: #f8fafc; color: #94a3b8; padding: 6px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; border: 1px solid #f1f5f9;">Set
                                        Current</a>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 20px 24px; text-align: right;">
                                <a href="index.php?controller=schoolYear&action=edit&id=<?php echo $year->id; ?>"
                                    style="color: #64748b; text-decoration: none;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
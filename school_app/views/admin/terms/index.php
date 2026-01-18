<?php
/**
 * Admin Term Management List View
 * Data: $year, $terms
 */
?>

<div class="terms-container" style="padding: 40px;">
    <div style="margin-bottom: 32px; display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px;">
        <a href="index.php?controller=schoolYear&action=index" style="color: #64748b; text-decoration: none;">Academic
            Years</a>
        <span>/</span>
        <span style="color: #0f172a; font-weight: 600;">Terms: <?php echo htmlspecialchars($year->name); ?></span>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="font-size: 32px; font-weight: 700; color: #0f172a; margin: 0;">Term Management:
            <?php echo htmlspecialchars($year->name); ?>
        </h1>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 12px; border: 1px solid #f1f5f9;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 15%;">Term
                        Number</th>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 25%;">Name
                    </th>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 20%;">Start
                        Date</th>
                    <th style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 20%;">End
                        Date</th>
                    <th
                        style="padding: 16px 24px; font-size: 14px; font-weight: 700; color: #64748b; width: 20%; text-align: right;">
                        Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($terms as $term): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 24px; font-size: 16px; color: #6366f1; font-weight: 600;">
                            <?php echo $term->term_number; ?>
                        </td>
                        <td style="padding: 24px; font-size: 16px; font-weight: 600; color: #1e293b;">
                            <?php echo htmlspecialchars($term->name); ?>
                        </td>
                        <td style="padding: 24px; font-size: 16px; color: #64748b; font-weight: 500;">
                            <?php echo $term->start_date; ?>
                        </td>
                        <td style="padding: 24px; font-size: 16px; color: #64748b; font-weight: 500;">
                            <?php echo $term->end_date; ?>
                        </td>
                        <td style="padding: 24px; text-align: right;">
                            <a href="index.php?controller=term&action=edit&id=<?php echo $term->id; ?>"
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
            </tbody>
        </table>
    </div>
</div>
<?php
/**
 * Admin Teachers List View
 * Data: $teachers, $filters
 */
?>

<div class="teachers-view-container">
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Teachers</h1>
        <a href="index.php?controller=teacher&action=add" class="btn-secondary"
            style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px;">Add Teacher</a>
    </div>

    <!-- Search Section -->
    <div class="card" style="padding: 24px; margin-bottom: 24px; border: none; background-color: #f8fafc;">
        <form action="index.php" method="GET" style="position: relative; width: 100%;">
            <input type="hidden" name="controller" value="teacher">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" value="<?php echo htmlspecialchars($filters['search']); ?>"
                placeholder="Search a Teacher..." class="input-field"
                style="width: 100%; padding: 12px 16px 12px 44px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
            <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8;"
                width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </form>
    </div>

    <!-- Table Section -->
    <div class="card"
        style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        ID</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Full Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="2" style="padding: 48px; text-align: center; color: #94a3b8; font-size: 15px;">
                            No teachers found matching your criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s;"
                            onmouseover="this.style.backgroundColor='#f8fafc'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($teacher->id); ?>
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($teacher->first_name . ' ' . $teacher->last_name); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Simple debounce for search
    let searchTimeout = null;
    document.querySelector('input[name="search"]').addEventListener('input', fun ction () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>
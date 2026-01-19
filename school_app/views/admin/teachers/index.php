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

    <?php if (isset($_SESSION['success'])): ?>
        <div style="padding: 12px 16px; background-color: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; color: #166534; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="padding: 12px 16px; background-color: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; color: #dc2626; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

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
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Email</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Status</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-transform: none;">
                        Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="5" style="padding: 48px; text-align: center; color: #94a3b8; font-size: 15px;">
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
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($teacher->email ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px;">
                                <?php if ($teacher->is_active): ?>
                                    <span style="padding: 4px 8px; background-color: #dcfce7; color: #16a34a; border-radius: 4px; font-size: 12px; font-weight: 500;">Active</span>
                                <?php else: ?>
                                    <span style="padding: 4px 8px; background-color: #fee2e2; color: #dc2626; border-radius: 4px; font-size: 12px; font-weight: 500;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 16px;">
                                <a href="index.php?controller=user&action=edit&id=<?php echo $teacher->id; ?>" 
                                   style="color: #2563eb; font-size: 13px; text-decoration: none; margin-right: 12px;">
                                    ✏️ Edit
                                </a>
                                <a href="index.php?controller=user&action=delete&id=<?php echo $teacher->id; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this user?')"
                                   style="color: #dc2626; font-size: 13px; text-decoration: none;">
                                    🗑️ Delete
                                </a>
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
    document.querySelector('input[name="search"]').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>

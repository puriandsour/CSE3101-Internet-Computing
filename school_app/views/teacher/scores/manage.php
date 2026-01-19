<?php
/**
 * Manage Scores - View and Delete
 */
$db = Database::connect();

// Get all classes
$classes = ClassModel::getAll();
$terms = Term::getAll();

// If class and term selected, get scores
$scores = [];
if (!empty($_GET['class_id']) && !empty($_GET['term_id'])) {
    $stmt = $db->prepare("
        SELECT 
            sc.*,
            s.first_name,
            s.last_name,
            s.admission_no,
            sub.name as subject_name,
            t.name as term_name
        FROM scores sc
        JOIN enrollments e ON sc.enrollment_id = e.id
        JOIN students s ON e.student_id = s.id
        JOIN subjects sub ON sc.subject_id = sub.id
        JOIN terms t ON sc.term_id = t.id
        WHERE e.class_id = ? AND sc.term_id = ?
        ORDER BY s.admission_no, sub.name
    ");
    $stmt->execute([$_GET['class_id'], $_GET['term_id']]);
    $scores = $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>

<div class="manage-scores-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Manage Scores</h1>
        <a href="index.php?controller=score&action=enter" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; text-decoration: none;">
            Enter New Scores
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="padding: 12px 16px; background-color: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; color: #166534; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Filter Form -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; margin-bottom: 20px;">
        <form method="GET" action="index.php">
            <input type="hidden" name="controller" value="score">
            <input type="hidden" name="action" value="manage">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Class</label>
                    <select name="class_id" required
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <option value="">Choose a class...</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class->id; ?>" <?php echo (isset($_GET['class_id']) && $_GET['class_id'] == $class->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($class->grade_name . ' - ' . $class->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display: block; color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Select Term</label>
                    <select name="term_id" required
                            style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid #e2e8f0; background-color: #f1f5f9; font-size: 14px;">
                        <option value="">Choose a term...</option>
                        <?php foreach ($terms as $term): ?>
                            <option value="<?php echo $term->id; ?>" <?php echo (isset($_GET['term_id']) && $_GET['term_id'] == $term->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($term->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-secondary" 
                    style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; border: none; cursor: pointer;">
                Load Scores
            </button>
        </form>
    </div>

    <!-- Scores Table -->
    <?php if (!empty($scores)): ?>
        <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                        <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Admission No</th>
                        <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Student</th>
                        <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Subject</th>
                        <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Score</th>
                        <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Remarks</th>
                        <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $score): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; color: #6366f1; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($score->admission_no); ?>
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 500; font-size: 14px;">
                                <?php echo htmlspecialchars($score->first_name . ' ' . $score->last_name); ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($score->subject_name); ?>
                            </td>
                            <td style="padding: 16px; color: #1e293b; font-weight: 600; font-size: 14px;">
                                <?php echo $score->score; ?>
                            </td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;">
                                <?php echo htmlspecialchars($score->remarks ?? 'N/A'); ?>
                            </td>
                            <td style="padding: 16px;">
                                <a href="index.php?controller=score&action=delete&id=<?php echo $score->id; ?>" 
                                   onclick="return confirm('Delete this score?')"
                                   style="color: #dc2626; font-size: 13px; text-decoration: none;">
                                    🗑️ Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif (!empty($_GET['class_id']) && !empty($_GET['term_id'])): ?>
        <div class="card" style="padding: 48px; text-align: center; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
            <p style="color: #94a3b8; font-size: 15px;">No scores found for this class and term.</p>
        </div>
    <?php endif; ?>
</div>

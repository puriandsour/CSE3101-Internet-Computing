<?php
/**
 * View Report Card
 */
$studentId = $_GET['student_id'] ?? null;
$termId = $_GET['term_id'] ?? null;

if (!$studentId || !$termId) {
    echo "<p>Invalid report request.</p>";
    exit;
}

$db = Database::connect();

// Get student info
$stmt = $db->prepare("
    SELECT s.*, c.name as class_name, g.name as grade_name
    FROM students s
    LEFT JOIN enrollments e ON s.id = e.student_id AND e.status = 'ACTIVE'
    LEFT JOIN classes c ON e.class_id = c.id
    LEFT JOIN grades g ON c.grade_id = g.id
    WHERE s.id = ?
");
$stmt->execute([$studentId]);
$student = $stmt->fetch(PDO::FETCH_OBJ);

// Get scores for this term
$stmt = $db->prepare("
    SELECT 
        sub.name as subject_name,
        sc.score,
        sc.remarks
    FROM scores sc
    JOIN subjects sub ON sc.subject_id = sub.id
    JOIN enrollments e ON sc.enrollment_id = e.id
    WHERE e.student_id = ? AND sc.term_id = ?
    ORDER BY sub.name
");
$stmt->execute([$studentId, $termId]);
$scores = $stmt->fetchAll(PDO::FETCH_OBJ);

// Calculate average
$total = 0;
$count = count($scores);
foreach ($scores as $score) {
    $total += $score->score;
}
$average = $count > 0 ? round($total / $count, 2) : 0;
?>

<div class="report-card">
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Report Card</h1>
        <a href="index.php?controller=report&action=index" class="btn-secondary" 
           style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
            Back to Reports
        </a>
    </div>

    <!-- Student Info -->
    <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff; margin-bottom: 20px;">
        <h3 style="font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Student Information</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 4px;">Name</p>
                <p style="color: #1e293b; font-size: 14px; font-weight: 500;">
                    <?php echo htmlspecialchars($student->first_name . ' ' . $student->last_name); ?>
                </p>
            </div>
            <div>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 4px;">Admission No</p>
                <p style="color: #1e293b; font-size: 14px; font-weight: 500;">
                    <?php echo htmlspecialchars($student->admission_no); ?>
                </p>
            </div>
            <div>
                <p style="color: #64748b; font-size: 13px; margin-bottom: 4px;">Class</p>
                <p style="color: #1e293b; font-size: 14px; font-weight: 500;">
                    <?php echo htmlspecialchars($student->grade_name . ' - ' . $student->class_name); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Scores Table -->
    <div class="card" style="padding: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #fff; margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #fff;">
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-align: left;">Subject</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-align: center;">Score</th>
                    <th style="padding: 16px; color: #64748b; font-size: 13px; font-weight: 600; text-align: left;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($scores)): ?>
                    <?php foreach ($scores as $score): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; color: #1e293b; font-size: 14px;"><?php echo htmlspecialchars($score->subject_name); ?></td>
                            <td style="padding: 16px; color: #1e293b; font-size: 14px; text-align: center; font-weight: 600;"><?php echo $score->score; ?></td>
                            <td style="padding: 16px; color: #64748b; font-size: 14px;"><?php echo htmlspecialchars($score->remarks ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="background-color: #f8fafc;">
                        <td style="padding: 16px; color: #1e293b; font-size: 14px; font-weight: 600;">AVERAGE</td>
                        <td style="padding: 16px; color: #2563eb; font-size: 16px; text-align: center; font-weight: 700;"><?php echo $average; ?>%</td>
                        <td style="padding: 16px;"></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding: 48px; text-align: center; color: #94a3b8;">No scores recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Download Button -->
    <div style="text-align: center;">
        <button onclick="window.print()" class="btn-primary" 
                style="padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; border: none; cursor: pointer;">
            🖨️ Print Report Card
        </button>
    </div>
</div>

<div class="container">
<h2>Student Report Card</h2>

<?php if (!empty($data)): ?>
    <p><strong>Name:</strong> <?= $data[0]['first_name'] ?> <?= $data[0]['last_name'] ?></p>
    <p><strong>Class:</strong> <?= $data[0]['class_name'] ?></p>
    <p><strong>Term:</strong> <?= $data[0]['term_number'] ?></p>

    <table border="1" width="100%" cellpadding="8">
        <tr>
            <th>Subject</th>
            <th>Score</th>
        </tr>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['subject_name'] ?></td>
            <td><?= $row['score'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php 
    $totals = \Report::studentTotals($_GET['student'], $_GET['term']);
    $overallPercent = $totals['average_score']; // simple percentage
    ?>
    <p><strong>Total Score:</strong> <?= $totals['total_score'] ?></p>
    <p><strong>Average Score:</strong> <?= round($totals['average_score'], 2) ?></p>
    <p><strong>Overall Percentage:</strong> <?= round($overallPercent, 2) ?>%</p>

<?php else: ?>
    <p style="text-align:center;">No scores found for this student and term.</p>
<?php endif; ?>

<a class="back-link" href="index.php?controller=dashboard">Back</a>
</div>

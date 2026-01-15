<?php
require_once __DIR__ . '/../../models/Student.php';
require_once __DIR__ . '/../../models/Term.php';

$students = Student::all();
$terms = Term::all();
?>

<div class="container">
<h2>Generate Report Card</h2>

<form method="GET" action="index.php">
    <input type="hidden" name="controller" value="report">
    <input type="hidden" name="action" value="view">

    <select name="student" required>
        <option value="">Select Student</option>
        <?php foreach($students as $s): ?>
            <option value="<?= $s->id ?>"><?= $s->first_name ?> <?= $s->last_name ?></option>
        <?php endforeach; ?>
    </select>

    <select name="term" required>
        <option value="">Select Term</option>
        <?php foreach($terms as $t): ?>
            <option value="<?= $t->id ?>">Term <?= $t->term_number ?></option>
        <?php endforeach; ?>
    </select>

    <button>Generate Report</button>
</form>
<a class="back-link" href="index.php?controller=dashboard">Back</a>

</div>

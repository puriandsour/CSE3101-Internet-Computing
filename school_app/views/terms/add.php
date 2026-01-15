<?php
require_once __DIR__ . '/../../models/SchoolYear.php';
$years = SchoolYear::all();
?>

<div class="container">
<h2>Add Term</h2>

<form method="POST">
    <input name="term_number" placeholder="Term 1" required>

    <select name="school_year_id" required>
        <option value="">Select School Year</option>
        <?php foreach ($years as $y): ?>
            <option value="<?= $y->id ?>"><?= $y->name ?></option>
        <?php endforeach; ?>
    </select>

    <button>Add</button>
</form>

<a class="back-link" href="index.php?controller=dashboard">Back</a>

</div>

<?php
require_once __DIR__ . '/../../models/Grade.php';
$grades = Grade::all();
?>

<div class="container">
<h2>Add Class</h2>

<form method="POST" action="index.php?controller=class&action=add">
    <input name="name" placeholder="Class Name" required>

    <select name="grade_id" required>
        <option value="">Select Grade</option>
        <?php foreach ($grades as $grade): ?>
            <option value="<?= $grade->id ?>">
                <?= $grade->name ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button>Add Class</button>
</form>
<a class="back-link" href="index.php?controller=dashboard">Back</a>

</div>

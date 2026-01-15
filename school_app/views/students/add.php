<?php
require_once __DIR__ . '/../../models/ClassModel.php';
$classes = ClassModel::all();
?>

<div class="container">
<h2>Add Student</h2>

<form method="POST" action="index.php?controller=student&action=add">
    <input name="first_name" placeholder="First Name" required>
    <input name="last_name" placeholder="Last Name" required>

    <select name="class_id" required>
        <option value="">Select Class</option>
        <?php foreach ($classes as $class): ?>
            <option value="<?= $class->id ?>">
                <?= $class->name ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button>Add Student</button>
</form>

<a class="back-link" href="index.php?controller=dashboard">Back</a>

</div>

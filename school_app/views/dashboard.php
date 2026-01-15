<!DOCTYPE html>
<html>
<head>
    <title>School Dashboard</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<div class="container">
    <h2>School Dashboard</h2>

    <?php if(!empty($_SESSION['success'])): ?>
        <p class="success"><?= $_SESSION['success'] ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if(!empty($_SESSION['error'])): ?>
        <p class="error"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="dashboard-links">
        <?php if($_SESSION['role']=='admin'): ?>
            <a href="index.php?controller=auth&action=createUserForm">Create User</a>
            <a href="index.php?controller=schoolYear&action=add">Add School Year</a>
            <a href="index.php?controller=term&action=add">Add Term</a>
            <a href="index.php?controller=student&action=add">Add Student</a>
            <a href="index.php?controller=subject&action=add">Add Subject</a>
        <?php endif; ?>

        <?php if($_SESSION['role']=='teacher'): ?>
            <a href="index.php?controller=score&action=add">Add Scores</a>
        <?php endif; ?>

        <a href="index.php?controller=class&action=add">Add Class</a>
        <a href="index.php?controller=report">Report Card</a>
    </div>
    
<a class="back-link" href="index.php?controller=auth&action=logout">Logout</a>

</div>

</body>
</html>

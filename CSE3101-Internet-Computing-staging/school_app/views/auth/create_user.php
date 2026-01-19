<div class="container">
<h2>Create User</h2>

<form method="POST" action="index.php?controller=auth&action=createUser">
    <input name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input name="role" placeholder="Role (admin/teacher)" required>
    <input type="password" name="password" placeholder="Password" required>
    <button>Create User</button>
</form>
<a class="back-link" href="index.php?controller=dashboard">Back</a>

</div>


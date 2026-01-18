<?php
/**
 * Add New Teacher View
 */
?>

<div class="add-teacher-container" style="max-width: 800px; margin: 0 auto; padding: 40px 20px;">
    <h1 style="font-size: 36px; font-weight: 700; color: #1e293b; margin-bottom: 32px;">Add New Teacher</h1>

    <form action="index.php?controller=teacher&action=create" method="POST"
        style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Names Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">First Name</label>
                <input type="text" name="first_name" placeholder="Enter first name" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">Last Name</label>
                <input type="text" name="last_name" placeholder="Enter last name" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;">
            </div>
        </div>

        <!-- Credentials Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">Username</label>
                <input type="text" name="username" placeholder="Enter username" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">Email</label>
                <input type="email" name="email" placeholder="Enter email" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;">
            </div>
        </div>

        <!-- Password -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 600; font-size: 16px; color: #334155;">Initial Password</label>
            <input type="password" name="password" placeholder="Enter initial password" required
                style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;">
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 16px; margin-top: 16px;">
            <button type="submit"
                style="padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; background-color: #1e3a8a; border: none; color: white; cursor: pointer;">
                Add Teacher
            </button>
            <a href="index.php?controller=teacher&action=index"
                style="padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 16px; background-color: #f1f5f9; border: none; color: #1e293b; text-decoration: none; text-align: center;">
                Cancel
            </a>
        </div>
    </form>
</div>
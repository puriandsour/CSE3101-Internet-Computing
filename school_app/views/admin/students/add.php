<?php
/**
 * Create New Student View
 */
?>

<div class="add-student-container" style="max-width: 800px; margin: 0 auto; padding: 40px 20px;">
    <h1 class="text-h1" style="font-weight: 700; font-size: 36px; color: #1e293b; margin-bottom: 32px;">Create New
        Student</h1>

    <form action="index.php?controller=student&action=create" method="POST" class="add-student-form"
        style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Admission Number -->
        <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 600; font-size: 16px; color: #334155;">Admission Number</label>
            <input type="text" name="admission_no" placeholder="Enter Admission Number" class="input-field"
                style="width: 100%; padding: 14px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;"
                required>
        </div>

        <!-- Names Row -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">First Name</label>
                <input type="text" name="first_name" placeholder="Enter First Name" class="input-field"
                    style="width: 100%; padding: 14px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;"
                    required>
            </div>
            <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">Last Name</label>
                <input type="text" name="last_name" placeholder="Enter Last Name" class="input-field"
                    style="width: 100%; padding: 14px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;"
                    required>
            </div>
        </div>

        <!-- Date of Birth -->
        <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 600; font-size: 16px; color: #334155;">Date of Birth</label>
            <div style="position: relative; width: 100%;">
                <input type="date" name="date_of_birth" class="input-field"
                    style="width: 100%; padding: 14px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;"
                    required>
                <!-- We can't easily put an icon over a native date picker without more CSS effort, 
                     but we can style the container or rely on the native icon in many browsers -->
            </div>
        </div>

        <!-- Gender -->
        <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
            <label style="font-weight: 600; font-size: 16px; color: #334155;">Gender</label>
            <select name="gender" class="input-field"
                style="width: 100%; padding: 14px 20px; border-radius: 10px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748b%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1.2rem top 50%; background-size: 0.65rem auto;"
                required>
                <option value="" disabled selected>Select</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
                <option value="OTHER">Other</option>
            </select>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 16px; margin-top: 16px;">
            <button type="submit" class="btn-primary"
                style="padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 16px; background-color: #1e3a8a; border: none; color: white; cursor: pointer;">Save
                Student</button>
            <a href="index.php?controller=student" class="btn-tertiary"
                style="padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 16px; background-color: #e2e8f0; border: none; color: #1e293b; text-decoration: none; text-align: center;">Cancel</a>
        </div>

    </form>
</div>
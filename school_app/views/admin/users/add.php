<?php
/**
 * Create Staff User View
 * Updated high-fidelity form for adding new system users with improved consistency.
 */
?>

<div class="add-user-container" style="max-width: 800px; margin: 0 auto; padding: 40px 20px;">
    <h1 style="font-size: 36px; font-weight: 700; color: #1e293b; margin-bottom: 32px;">Create Staff User</h1>

    <form action="index.php?controller=user&action=create" method="POST"
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
                <label style="font-weight: 600; font-size: 16px; color: #334155;">Email Address</label>
                <input type="email" name="email" placeholder="Enter email" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px;">
            </div>
        </div>

        <!-- Role & Status Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: end;">
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">Role</label>
                <select name="role" required
                    style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 16px; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2364748b%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22m6%209%206%206%206-6%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 16px center;">
                    <option value="" disabled selected>Select role</option>
                    <option value="TEACHER">Teacher</option>
                    <option value="OFFICE_ADMIN">Office Admin</option>
                </select>
            </div>

            <div
                style="display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; height: 52px;">
                <label style="font-weight: 600; font-size: 16px; color: #334155;">Active Account</label>
                <label class="switch" style="position: relative; display: inline-block; width: 48px; height: 24px;">
                    <input type="checkbox" name="is_active" checked style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

        <p style="color: #64748b; font-size: 14px; margin-top: -8px;">Default password for new staff will be set to
            <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 600;">welcome123</code>
        </p>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 16px; margin-top: 16px;">
            <button type="submit"
                style="padding: 14px 48px; border-radius: 12px; font-weight: 700; font-size: 16px; background-color: #1e3a8a; border: none; color: white; cursor: pointer; transition: all 0.2s;">
                Save User
            </button>
            <a href="index.php?controller=quickactions"
                style="padding: 14px 48px; border-radius: 12px; font-weight: 700; font-size: 16px; background-color: #f1f5f9; border: none; color: #1e293b; text-decoration: none; text-align: center; transition: all 0.2s;">
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
    /* Switch Container */
    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #22c55e;
        /* GREEN for active */
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #22c55e;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(24px);
        -ms-transform: translateX(24px);
        transform: translateX(24px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 24px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    button:hover {
        background-color: #1e40af !important;
        transform: translateY(-1px);
    }

    a:hover {
        background-color: #e2e8f0 !important;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #1e3a8a !important;
        background-color: white !important;
    }
</style>
<?php
/**
 * Admin Dashboard
 */

// Get statistics
$db = Database::connect();

// Total students
$stmt = $db->query("SELECT COUNT(*) as count FROM students WHERE is_active = 1");
$totalStudents = $stmt->fetch(PDO::FETCH_OBJ)->count;

// Total teachers
$stmt = $db->query("
    SELECT COUNT(DISTINCT u.id) as count 
    FROM users u 
    JOIN user_roles ur ON u.id = ur.user_id 
    JOIN roles r ON ur.role_id = r.id 
    WHERE r.name = 'TEACHER' AND u.is_active = 1
");
$totalTeachers = $stmt->fetch(PDO::FETCH_OBJ)->count;

// Total classes
$stmt = $db->query("SELECT COUNT(*) as count FROM classes WHERE is_active = 1");
$totalClasses = $stmt->fetch(PDO::FETCH_OBJ)->count;
?>

<div class="dashboard-container">
    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <h1 class="text-h1" style="font-weight: 700; font-size: 32px; color: var(--text-dark);">Admin Dashboard</h1>
        <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Welcome, Admin. Use the sidebar to manage the school.</p>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
            <div style="color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Total Students</div>
            <div style="font-size: 36px; font-weight: 700; color: #1e293b;"><?php echo $totalStudents; ?></div>
        </div>
        <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
            <div style="color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Teachers</div>
            <div style="font-size: 36px; font-weight: 700; color: #1e293b;"><?php echo $totalTeachers; ?></div>
        </div>
        <div class="card" style="padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #fff;">
            <div style="color: #64748b; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Classes</div>
            <div style="font-size: 36px; font-weight: 700; color: #1e293b;"><?php echo $totalClasses; ?></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h2 style="font-size: 18px; font-weight: 600; color: #1e293b; margin-bottom: 16px;">Quick Actions</h2>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="index.php?controller=student&action=add" class="btn-primary" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #2563eb; color: #fff; text-decoration: none;">
                Add Student
            </a>
            <a href="index.php?controller=user&action=add" class="btn-secondary" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
                Add Teacher
            </a>
            <a href="index.php?controller=class" class="btn-secondary" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
                Manage Classes
            </a>
            <a href="index.php?controller=subject" class="btn-secondary" 
               style="padding: 10px 20px; border-radius: 8px; font-weight: 500; font-size: 14px; background-color: #f1f5f9; color: #334155; text-decoration: none;">
                Manage Subjects
            </a>
        </div>
    </div>
</div>

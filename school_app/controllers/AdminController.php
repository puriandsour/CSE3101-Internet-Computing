<?php

class AdminController
{
    /**
     * Fetch all data needed for the admin dashboard
     */
    public function dashboard()
    {
        $db = Database::connect();

        // 1. Get Current School Year
        $yearStmt = $db->query("SELECT name FROM school_years WHERE is_current = 1 LIMIT 1");
        $currentYear = $yearStmt->fetch(PDO::FETCH_OBJ);
        $yearName = $currentYear ? $currentYear->name : "N/A";

        // 2. Summary Counts
        $studentCount = $db->query("SELECT COUNT(*) FROM students WHERE is_active = 1")->fetchColumn();
        $classCount = $db->query("SELECT COUNT(*) FROM classes WHERE is_active = 1")->fetchColumn();

        // Count users with TEACHER role
        $teacherCount = $db->query("
            SELECT COUNT(DISTINCT ur.user_id) 
            FROM user_roles ur 
            JOIN roles r ON ur.role_id = r.id 
            WHERE r.name = 'TEACHER'
        ")->fetchColumn();

        // 3. Recent Activity (Simplified)
        // We fetch the latest 5 entries from various tables and union them
        $activities = [];

        // Recent Enrolled Students
        $enrollments = $db->query("
            SELECT 'Student enrolled' as description, e.created_at, s.first_name, s.last_name
            FROM enrollments e
            JOIN students s ON e.student_id = s.id
            ORDER BY e.created_at DESC LIMIT 5
        ")->fetchAll(PDO::FETCH_OBJ);

        // Recent Scores
        $scores = $db->query("
            SELECT 'New score entered' as description, s.created_at, sub.name as subject_name
            FROM scores s
            JOIN subjects sub ON s.subject_id = sub.id
            ORDER BY s.created_at DESC LIMIT 5
        ")->fetchAll(PDO::FETCH_OBJ);

        // Recent Subjects
        $subjects = $db->query("
            SELECT 'Subject added' as description, created_at, name
            FROM subjects
            ORDER BY created_at DESC LIMIT 5
        ")->fetchAll(PDO::FETCH_OBJ);

        // Combine and sort by date
        foreach ($enrollments as $e)
            $activities[] = ['desc' => $e->description, 'time' => $e->created_at];
        foreach ($scores as $s)
            $activities[] = ['desc' => $s->description, 'time' => $s->created_at];
        foreach ($subjects as $sub)
            $activities[] = ['desc' => $sub->description, 'time' => $sub->created_at];

        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        // Take only top 5
        $activities = array_slice($activities, 0, 5);

        return [
            'yearName' => $yearName,
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'classCount' => $classCount,
            'activities' => $activities,
            'adminName' => $_SESSION['first_name'] ?? 'Admin'
        ];
    }
}

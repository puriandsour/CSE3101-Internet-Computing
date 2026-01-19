<?php
/**
 * Global Header
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack - School Management System</title>
    <link rel="stylesheet" href="assets/css/app.css">
    <!-- Inline mapping for Figma SVG icons or external fonts if needed -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Lexend:wght@400;500;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="app-container">
        <!-- Top Navigation -->
        <header class="top-nav">
            <div class="nav-brand">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <a href="index.php">
                    <div class="text-logo">EduTrack</div>
                </a>
            </div>
            <div class="nav-user">
                <a href="index.php?controller=notifications">
                    <div class="notification-bell">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </div>
                </a>
                <a href="index.php?controller=profile">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username'] ?? 'User'); ?>&background=random"
                        alt="Profile" class="profile-pic">
                </a>
            </div>
        </header>

        <div class="main-layout">
            <!-- Sidebar Sidebar -->
            <?php include 'views/layout/sidebar.php'; ?>

            <!-- Main Content Area -->
            <main class="content-area">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"
                        style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"
                        style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
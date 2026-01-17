<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduTrack</title>
    <link rel="stylesheet" href="assets/css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --login-bg: #ffffff;
            /* Matching the clean white/light gray in screenshot */
            --input-bg: #fcfcfc;
            --input-border: #e2e8f0;
            --text-muted: #94a3b8;
            --btn-blue: #1e3a8a;
            /* Dark blue from screenshot */
            --btn-hover: #1e40af;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--login-bg);
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            /* Slightly wider to match screenshot feel */
            padding: 2.5rem;
            text-align: center;
        }

        .brand-header {
            margin-bottom: 2.5rem;
        }

        .brand-name {
            font-size: 2.5rem;
            font-weight: 800;
            color: #000;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.05em;
        }

        .brand-tagline {
            font-size: 0.9rem;
            color: #718096;
            margin: 0;
            font-weight: 500;
        }

        .form-container {
            text-align: left;
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.75rem;
        }

        .input-field {
            width: 100%;
            height: 56px;
            padding: 0 1rem;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            color: #1e293b;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .input-field::placeholder {
            color: #94a3b8;
        }

        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .login-btn {
            width: 100%;
            height: 52px;
            background-color: var(--btn-blue);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            transition: background-color 0.2s;
        }

        .login-btn:hover {
            background-color: var(--btn-hover);
        }

        .footer-text {
            margin-top: 2.5rem;
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            text-align: center;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fee2e2;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="brand-header">
            <h1 class="brand-name">EduTrack</h1>
            <p class="brand-tagline">Simple. Secure. Student-Focused.</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=login" method="POST" class="form-container">
            <div class="input-group">
                <label for="username" class="input-label">Username</label>
                <input type="text" id="username" name="email" class="input-field" placeholder="Username" required
                    autofocus>
            </div>

            <div class="input-group">
                <label for="password" class="input-label">Password</label>
                <input type="password" id="password" name="password" class="input-field" placeholder="Password"
                    required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <p class="footer-text">All rights reserved for group 8 CSEProject 2026</p>
    </div>
</body>
<!-- Easter Egg If you want a project similar built reach out to the team we got you. Ensure your bread straight to -->

</html>
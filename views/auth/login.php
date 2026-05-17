<?php $pageTitle = $pageTitle ?? 'Login'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — JobPortal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="jp">
<div class="jp-auth">
    <div class="panel">
        <h1><i class="fas fa-briefcase" style="margin-right: 8px;"></i>JobPortal</h1>
        <p class="page-sub">Sign in to your account</p>

        <?php if (!empty($errors)): ?>
            <div class="pill bad" style="margin-bottom: 20px; padding: 12px; height: auto; display: flex; flex-direction: column; align-items: flex-start;">
                <?php foreach ($errors as $e): ?>
                    <div style="display:flex; align-items:center; gap:8px;"><i class="dot"></i> <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login" class="col" style="max-width: 360px;">
            <div class="field">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="input" placeholder="you@example.com" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn primary" style="justify-content: center; height: 38px; margin-top: 8px;">Sign In</button>
        </form>
        <p style="margin-top: 24px; font-size: 13px; color: var(--muted);">Don't have an account? <a href="<?= BASE_URL ?>/register" style="color: var(--ink); font-weight: 500; text-decoration: none;">Register here</a></p>
    </div>
    <div class="marketing">
        <div>
            <h1 style="font-size: 36px; margin-bottom: 16px;">Welcome Back.</h1>
            <p style="color: var(--muted); font-size: 16px; line-height: 1.6; max-width: 400px;">
                Find your dream job and connect with top employers today.
            </p>
        </div>
        <div style="font-family: var(--mono); color: var(--muted); font-size: 12px;">
            &copy; <?= date('Y') ?> JobPortal
        </div>
    </div>
</div>
</body>
</html>

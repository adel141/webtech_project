<?php $pageTitle = $pageTitle ?? 'Register'; ?>
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
        <p class="page-sub">Create your account</p>

        <?php if (!empty($errors)): ?>
            <div class="pill bad" style="margin-bottom: 20px; padding: 12px; height: auto; display: flex; flex-direction: column; align-items: flex-start;">
                <?php foreach ($errors as $e): ?>
                    <div style="display:flex; align-items:center; gap:8px;"><i class="dot"></i> <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/register" class="col" style="max-width: 400px;">
            <div class="field">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="input" placeholder="John Doe" value="<?= htmlspecialchars($name ?? '') ?>" required>
            </div>
            <div class="row">
                <div class="field" style="flex:1;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input" placeholder="you@example.com" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>
                <div class="field" style="flex:1;">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="input" placeholder="+1234567890" value="<?= htmlspecialchars($phone ?? '') ?>" required>
                </div>
            </div>
            <div class="field">
                <label for="role">I am a...</label>
                <select id="role" name="role" class="select" required>
                    <option value="">Select your role</option>
                    <option value="seeker" <?= ($role ?? '') === 'seeker' ? 'selected' : '' ?>>Job Seeker</option>
                    <option value="employer" <?= ($role ?? '') === 'employer' ? 'selected' : '' ?>>Employer</option>
                    <option value="recruiter" <?= ($role ?? '') === 'recruiter' ? 'selected' : '' ?>>Recruiter / Agency</option>
                </select>
            </div>
            <div class="row">
                <div class="field" style="flex:1;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="input" placeholder="Min 6 characters" required>
                </div>
                <div class="field" style="flex:1;">
                    <label for="confirm_password">Confirm</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="input" placeholder="Re-enter password" required>
                </div>
            </div>
            <button type="submit" class="btn primary" style="justify-content: center; height: 38px; margin-top: 8px;">Create Account</button>
        </form>
        <p style="margin-top: 24px; font-size: 13px; color: var(--muted);">Already have an account? <a href="<?= BASE_URL ?>/login" style="color: var(--ink); font-weight: 500; text-decoration: none;">Sign in</a></p>
    </div>
    <div class="marketing">
        <div>
            <h1 style="font-size: 36px; margin-bottom: 16px;">Join JobPortal.</h1>
            <p style="color: var(--muted); font-size: 16px; line-height: 1.6; max-width: 400px;">
                Your next big career move or best hire is just a click away.
            </p>
        </div>
        <div style="font-family: var(--mono); color: var(--muted); font-size: 12px;">
            &copy; <?= date('Y') ?> JobPortal
        </div>
    </div>
</div>
</body>
</html>

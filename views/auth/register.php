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
<body>
<div class="auth-wrapper">
    <div class="auth-card" style="max-width:500px;">
        <h1><i class="fas fa-briefcase"></i> JobPortal</h1>
        <p class="auth-subtitle">Create your account</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/register">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" value="<?= htmlspecialchars($name ?? '') ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="+1234567890" value="<?= htmlspecialchars($phone ?? '') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="role">I am a...</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="">Select your role</option>
                    <option value="seeker" <?= ($role ?? '') === 'seeker' ? 'selected' : '' ?>>Job Seeker</option>
                    <option value="employer" <?= ($role ?? '') === 'employer' ? 'selected' : '' ?>>Employer</option>
                    <option value="recruiter" <?= ($role ?? '') === 'recruiter' ? 'selected' : '' ?>>Recruiter / Agency</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Create Account</button>
        </form>
        <p class="auth-footer">Already have an account? <a href="<?= BASE_URL ?>/login">Sign in</a></p>
    </div>
</div>
</body>
</html>

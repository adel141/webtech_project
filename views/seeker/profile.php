<?php $activePage = $activePage ?? 'profile'; $user = Auth::user(); ?>
<div class="page-header"><h1>My Profile</h1><p>Manage your professional details</p></div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <!-- Photo & Resume -->
    <div>
        <div class="card" style="text-align:center;margin-bottom:20px;">
            <?php if ($user['profile_pic']): ?>
                <img src="<?= BASE_URL ?>/uploads/profile_pics/<?= htmlspecialchars($user['profile_pic']) ?>" class="avatar-lg" style="margin:0 auto 16px;">
            <?php else: ?>
                <div class="avatar-lg" style="margin:0 auto 16px;background:var(--accent-glow);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:2rem;font-weight:700;border-radius:50%;"><?= strtoupper(substr($user['name'],0,1)) ?></div>
            <?php endif; ?>
            <h3><?= htmlspecialchars($user['name']) ?></h3>
            <p style="color:var(--text-muted);font-size:0.85rem;"><?= htmlspecialchars($user['email']) ?></p>
            <form method="POST" action="<?= BASE_URL ?>/seeker/upload-photo" enctype="multipart/form-data" style="margin-top:16px;">
                <?= Middleware::csrfField() ?>
                <input type="file" name="photo" accept="image/*" class="form-control" style="margin-bottom:8px;">
                <button type="submit" class="btn btn-secondary btn-sm btn-block">Update Photo</button>
            </form>
        </div>
        <div class="card">
            <h3 style="margin-bottom:12px;">Resume</h3>
            <?php if (!empty($profile['resume_path'])): ?>
                <p style="color:var(--success);margin-bottom:12px;"><i class="fas fa-check-circle"></i> Resume uploaded</p>
                <a href="<?= BASE_URL ?>/uploads/resumes/<?= htmlspecialchars($profile['resume_path']) ?>" target="_blank" class="btn btn-secondary btn-sm btn-block" style="margin-bottom:8px;"><i class="fas fa-download"></i> Download</a>
            <?php else: ?>
                <p style="color:var(--text-muted);margin-bottom:12px;">No resume uploaded yet</p>
            <?php endif; ?>
            <form method="POST" action="<?= BASE_URL ?>/seeker/upload-resume" enctype="multipart/form-data">
                <?= Middleware::csrfField() ?>
                <input type="file" name="resume" accept=".pdf" class="form-control" style="margin-bottom:8px;">
                <p class="form-help">PDF only, max 5MB</p>
                <button type="submit" class="btn btn-primary btn-sm btn-block" style="margin-top:8px;">Upload Resume</button>
            </form>
        </div>
    </div>
    <!-- Profile Form -->
    <div class="card">
        <div class="card-header"><h3>Professional Details</h3></div>
        <form method="POST" action="<?= BASE_URL ?>/seeker/profile">
            <?= Middleware::csrfField() ?>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>">
            </div>
            <div class="form-group">
                <label for="headline">Headline</label>
                <input type="text" id="headline" name="headline" class="form-control" placeholder="e.g. Full Stack Developer" value="<?= htmlspecialchars($profile['headline'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="summary">Professional Summary</label>
                <textarea id="summary" name="summary" class="form-control" rows="4" placeholder="Brief overview of your experience..."><?= htmlspecialchars($profile['summary'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="skills">Skills (comma-separated)</label>
                <input type="text" id="skills" name="skills" class="form-control" placeholder="PHP, MySQL, JavaScript, React" value="<?= htmlspecialchars($profile['skills'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="years_experience">Years of Experience</label>
                    <input type="number" id="years_experience" name="years_experience" class="form-control" min="0" value="<?= (int)($profile['years_experience'] ?? 0) ?>">
                </div>
                <div class="form-group">
                    <label for="education_level">Education Level</label>
                    <select id="education_level" name="education_level" class="form-control">
                        <option value="">Select</option>
                        <?php foreach (['High School','Associate','Bachelor','Master','PhD','Other'] as $lvl): ?>
                        <option value="<?= $lvl ?>" <?= ($profile['education_level'] ?? '') === $lvl ? 'selected' : '' ?>><?= $lvl ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="current_salary">Current Salary ($)</label>
                    <input type="number" id="current_salary" name="current_salary" class="form-control" step="1000" value="<?= (float)($profile['current_salary'] ?? 0) ?>">
                </div>
                <div class="form-group">
                    <label for="expected_salary">Expected Salary ($)</label>
                    <input type="number" id="expected_salary" name="expected_salary" class="form-control" step="1000" value="<?= (float)($profile['expected_salary'] ?? 0) ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="preferred_location">Preferred Location</label>
                <input type="text" id="preferred_location" name="preferred_location" class="form-control" placeholder="e.g. New York, Remote" value="<?= htmlspecialchars($profile['preferred_location'] ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Profile</button>
        </form>
    </div>
</div>

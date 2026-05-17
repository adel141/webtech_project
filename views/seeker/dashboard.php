<?php $activePage = $activePage ?? 'dashboard'; ?>
<div class="page-header">
    <h1>Welcome back, <?= htmlspecialchars(Auth::name()) ?>!</h1>
    <p>Here's your job search overview</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-file-alt"></i></div>
        <div class="stat-value"><?= $totalApps ?></div>
        <div class="stat-label">Total Applications</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-spinner"></i></div>
        <div class="stat-value"><?= $activeApps ?></div>
        <div class="stat-label">Active Applications</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-value"><?= $interviews ?></div>
        <div class="stat-label">Interview Invites</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-bell"></i></div>
        <div class="stat-value"><?= $notifCount ?></div>
        <div class="stat-label">New Matches</div>
    </div>
</div>

<?php if ($unread > 0): ?>
<div class="alert alert-info"><i class="fas fa-envelope"></i> You have <?= $unread ?> unread message(s). <a href="<?= BASE_URL ?>/seeker/messages">View inbox</a></div>
<?php endif; ?>

<?php if (!empty($featured)): ?>
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h3><i class="fas fa-star" style="color:#f59e0b;"></i> Featured Jobs</h3><a href="<?= BASE_URL ?>/seeker/jobs" class="btn btn-secondary btn-sm">View All</a></div>
    <div class="job-grid">
        <?php foreach ($featured as $job): ?>
        <div class="job-card featured">
            <div class="job-company">
                <div class="company-logo"><?= $job['logo_path'] ? '<img src="'.BASE_URL.'/uploads/logos/'.htmlspecialchars($job['logo_path']).'">' : '<i class="fas fa-building"></i>' ?></div>
                <div><div class="company-name"><?= htmlspecialchars($job['company_name'] ?? 'Company') ?></div></div>
            </div>
            <div class="job-title"><?= htmlspecialchars($job['title']) ?></div>
            <div class="job-meta">
                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location'] ?? 'Remote') ?></span>
                <span><i class="fas fa-clock"></i> <?= htmlspecialchars($job['job_type']) ?></span>
                <?php if ($job['category_name']): ?><span><i class="fas fa-tag"></i> <?= htmlspecialchars($job['category_name']) ?></span><?php endif; ?>
            </div>
            <?php if ($job['salary_min'] || $job['salary_max']): ?>
            <div class="job-salary"><?= $job['salary_min'] ? '$'.number_format($job['salary_min']) : '' ?><?= ($job['salary_min'] && $job['salary_max']) ? ' - ' : '' ?><?= $job['salary_max'] ? '$'.number_format($job['salary_max']) : '' ?></div>
            <?php endif; ?>
            <div class="job-actions">
                <a href="<?= BASE_URL ?>/seeker/jobs/<?= $job['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3>Quick Actions</h3></div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="<?= BASE_URL ?>/seeker/jobs" class="btn btn-primary"><i class="fas fa-search"></i> Browse Jobs</a>
        <a href="<?= BASE_URL ?>/seeker/profile" class="btn btn-secondary"><i class="fas fa-user-edit"></i> Edit Profile</a>
        <a href="<?= BASE_URL ?>/seeker/alerts" class="btn btn-secondary"><i class="fas fa-bell"></i> Set Up Alerts</a>
        <a href="<?= BASE_URL ?>/seeker/applications" class="btn btn-secondary"><i class="fas fa-file-alt"></i> Track Applications</a>
    </div>
</div>

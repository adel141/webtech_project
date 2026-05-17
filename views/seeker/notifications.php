<?php $activePage = $activePage ?? 'alerts'; ?>
<div class="page-header"><h1>Notifications</h1><p>Jobs matching your alert preferences from the last 7 days</p></div>
<?php if (empty($notifications)): ?>
<div class="empty-state"><div class="empty-icon">🔔</div><h3>No new matches</h3><p>Set up job alerts to receive notifications</p><a href="<?= BASE_URL ?>/seeker/alerts" class="btn btn-primary">Create Alert</a></div>
<?php else: ?>
<div class="job-grid">
<?php foreach ($notifications as $job): ?>
<div class="job-card">
    <div class="job-company"><div class="company-logo"><i class="fas fa-building"></i></div><div><div class="company-name"><?= htmlspecialchars($job['company_name'] ?? '') ?></div></div></div>
    <a href="<?= BASE_URL ?>/seeker/jobs/<?= $job['id'] ?>" style="color:inherit;"><div class="job-title"><?= htmlspecialchars($job['title']) ?></div></a>
    <div class="job-meta">
        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location'] ?? 'Remote') ?></span>
        <span><i class="fas fa-clock"></i> <?= htmlspecialchars($job['job_type']) ?></span>
        <?php if ($job['category_name']): ?><span><i class="fas fa-tag"></i> <?= htmlspecialchars($job['category_name']) ?></span><?php endif; ?>
    </div>
    <div class="job-actions"><a href="<?= BASE_URL ?>/seeker/jobs/<?= $job['id'] ?>" class="btn btn-primary btn-sm">View & Apply</a></div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

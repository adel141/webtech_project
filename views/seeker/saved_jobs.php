<?php $activePage = $activePage ?? 'saved'; ?>
<div class="page-header"><h1>Saved Jobs</h1><p>Your bookmarked opportunities</p></div>
<?php if (empty($saved)): ?>
<div class="empty-state"><div class="empty-icon">❤️</div><h3>No saved jobs</h3><p>Bookmark jobs while browsing to find them here</p><a href="<?= BASE_URL ?>/seeker/jobs" class="btn btn-primary">Browse Jobs</a></div>
<?php else: ?>
<div class="job-grid">
<?php foreach ($saved as $s): ?>
<div class="job-card">
    <div class="job-company"><div class="company-logo"><i class="fas fa-building"></i></div><div><div class="company-name"><?= htmlspecialchars($s['company_name'] ?? '') ?></div></div></div>
    <div class="job-title"><?= htmlspecialchars($s['title']) ?></div>
    <div class="job-meta">
        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($s['location'] ?? 'Remote') ?></span>
        <span><i class="fas fa-clock"></i> <?= htmlspecialchars($s['job_type']) ?></span>
        <span class="badge <?= $s['status'] === 'active' ? 'badge-success' : 'badge-secondary' ?>"><?= ucfirst($s['status']) ?></span>
    </div>
    <?php if ($s['salary_min'] || $s['salary_max']): ?>
    <div class="job-salary">$<?= number_format($s['salary_min'] ?? 0) ?> - $<?= number_format($s['salary_max'] ?? 0) ?></div>
    <?php endif; ?>
    <div class="job-actions">
        <?php if ($s['status'] === 'active'): ?><a href="<?= BASE_URL ?>/seeker/jobs/<?= $s['job_id'] ?>" class="btn btn-primary btn-sm">View</a><?php endif; ?>
        <form method="POST" action="<?= BASE_URL ?>/seeker/toggle-save/<?= $s['job_id'] ?>" style="display:inline;"><?= Middleware::csrfField() ?><button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

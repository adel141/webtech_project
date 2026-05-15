<?php $activePage = $activePage ?? 'alerts'; ?>
<div class="page-header"><h1>Job Alerts</h1><p>Get notified when new jobs match your preferences</p></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
<div class="card">
    <div class="card-header"><h3>Create Alert</h3></div>
    <form method="POST" action="<?= BASE_URL ?>/seeker/alerts">
        <?= Middleware::csrfField() ?>
        <div class="form-group"><label>Keyword</label><input type="text" name="keyword" class="form-control" placeholder="e.g. PHP Developer"></div>
        <div class="form-group"><label>Category</label><select name="category_id" class="form-control"><option value="">Any</option><?php foreach($categories as $c): ?><option value="<?=$c['id']?>"><?=htmlspecialchars($c['name'])?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label>Location</label><input type="text" name="location" class="form-control" placeholder="e.g. New York"></div>
        <div class="form-group"><label>Job Type</label><select name="job_type" class="form-control"><option value="">Any</option><option value="full-time">Full-time</option><option value="part-time">Part-time</option><option value="remote">Remote</option><option value="contract">Contract</option></select></div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-bell"></i> Create Alert</button>
    </form>
</div>
<div>
    <h3 style="margin-bottom:16px;">Active Alerts</h3>
    <?php if (empty($alerts)): ?>
    <div class="empty-state"><div class="empty-icon">🔔</div><h3>No alerts yet</h3><p>Create an alert to get notified</p></div>
    <?php else: ?>
    <?php foreach ($alerts as $a): ?>
    <div class="card" style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <strong><?= htmlspecialchars($a['keyword'] ?: 'Any keyword') ?></strong>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-top:4px;">
                    <?php if ($a['category_name']): ?><span class="badge badge-purple"><?= htmlspecialchars($a['category_name']) ?></span><?php endif; ?>
                    <?php if ($a['location']): ?><span class="badge badge-info"><?= htmlspecialchars($a['location']) ?></span><?php endif; ?>
                    <?php if ($a['job_type']): ?><span class="badge badge-secondary"><?= htmlspecialchars($a['job_type']) ?></span><?php endif; ?>
                </div>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/seeker/alerts/delete/<?= $a['id'] ?>"><?= Middleware::csrfField() ?><button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
</div>

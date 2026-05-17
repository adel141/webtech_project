<?php $activePage = $activePage ?? 'applications'; ?>
<div class="page-header"><h1>My Applications</h1><p>Track the status of your job applications</p></div>
<?php if (empty($apps)): ?>
<div class="empty-state"><div class="empty-icon">📄</div><h3>No applications yet</h3><p>Start applying to jobs to see them here</p><a href="<?= BASE_URL ?>/seeker/jobs" class="btn btn-primary">Browse Jobs</a></div>
<?php else: ?>
<div class="table-wrapper">
<table class="table">
    <thead><tr><th>Job Title</th><th>Company</th><th>Location</th><th>Status</th><th>Applied</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($apps as $app): ?>
    <tr>
        <td><strong><?= htmlspecialchars($app['title']) ?></strong></td>
        <td><?= htmlspecialchars($app['company_name'] ?? '-') ?></td>
        <td><?= htmlspecialchars($app['location'] ?? '-') ?></td>
        <td>
            <?php $statusMap = ['submitted'=>'badge-info','reviewed'=>'badge-purple','shortlisted'=>'badge-warning','interview'=>'badge-success','rejected'=>'badge-danger','withdrawn'=>'badge-secondary']; ?>
            <span class="badge <?= $statusMap[$app['status']] ?? 'badge-secondary' ?>"><?= ucfirst($app['status']) ?></span>
        </td>
        <td style="color:var(--text-muted);font-size:0.85rem;"><?= date('M d, Y', strtotime($app['applied_at'])) ?></td>
        <td>
            <?php if ($app['status'] === 'submitted'): ?>
            <form method="POST" action="<?= BASE_URL ?>/seeker/withdraw/<?= $app['id'] ?>" style="display:inline;" onsubmit="return confirm('Withdraw this application?')">
                <?= Middleware::csrfField() ?>
                <button type="submit" class="btn btn-danger btn-sm">Withdraw</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>

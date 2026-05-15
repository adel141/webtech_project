<?php $activePage = $activePage ?? 'outreach'; ?>
<div class="page-header"><h1>Recruiter Outreach</h1><p>Messages from recruiters about job opportunities</p></div>
<?php if (empty($outreach)): ?>
<div class="empty-state"><div class="empty-icon">📨</div><h3>No outreach messages</h3><p>Recruiters may reach out to you about relevant opportunities</p></div>
<?php else: ?>
<?php foreach ($outreach as $o): ?>
<div class="card" style="margin-bottom:16px;">
    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px;">
        <div>
            <strong><?= htmlspecialchars($o['recruiter_name']) ?></strong>
            <?php if ($o['agency_name']): ?><span style="color:var(--text-muted);"> — <?= htmlspecialchars($o['agency_name']) ?></span><?php endif; ?>
        </div>
        <span class="badge <?= $o['status']==='sent'?'badge-info':($o['status']==='read'?'badge-warning':'badge-success') ?>"><?= ucfirst($o['status']) ?></span>
    </div>
    <?php if ($o['job_title']): ?><p style="margin-bottom:8px;"><strong>About:</strong> <?= htmlspecialchars($o['job_title']) ?></p><?php endif; ?>
    <p style="color:var(--text-secondary);margin-bottom:12px;"><?= nl2br(htmlspecialchars($o['message'])) ?></p>
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:0.8rem;color:var(--text-muted);"><?= date('M d, Y g:i A', strtotime($o['sent_at'])) ?></span>
        <?php if ($o['status'] !== 'responded'): ?>
        <form method="POST" action="<?= BASE_URL ?>/seeker/outreach/respond/<?= $o['id'] ?>"><?= Middleware::csrfField() ?><button class="btn btn-primary btn-sm"><i class="fas fa-reply"></i> Mark as Responded</button></form>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

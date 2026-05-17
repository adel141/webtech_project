<?php $activePage = $activePage ?? 'complaints'; ?>
<div class="page-header"><h1>Complaints</h1><p>Report issues to platform admin</p></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
<div class="card">
    <div class="card-header"><h3>Submit Complaint</h3></div>
    <form method="POST" action="<?= BASE_URL ?>/seeker/complaints">
        <?= Middleware::csrfField() ?>
        <div class="form-group"><label>Subject User ID (optional)</label><input type="number" name="subject_id" class="form-control" placeholder="ID of the user you're reporting"></div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="5" placeholder="Describe the issue in detail..." required></textarea></div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-flag"></i> Submit Complaint</button>
    </form>
</div>
<div>
    <h3 style="margin-bottom:16px;">My Complaints</h3>
    <?php if (empty($complaints)): ?>
    <div class="empty-state"><div class="empty-icon">✅</div><h3>No complaints</h3></div>
    <?php else: ?>
    <?php foreach ($complaints as $c): ?>
    <div class="card" style="margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
            <span class="badge <?= $c['status']==='open'?'badge-warning':'badge-success' ?>"><?= ucfirst($c['status']) ?></span>
            <span style="font-size:0.8rem;color:var(--text-muted);"><?= date('M d, Y', strtotime($c['created_at'])) ?></span>
        </div>
        <?php if ($c['subject_name']): ?><p style="font-size:0.85rem;color:var(--text-muted);">Against: <?= htmlspecialchars($c['subject_name']) ?></p><?php endif; ?>
        <p style="color:var(--text-secondary);font-size:0.9rem;"><?= htmlspecialchars($c['description']) ?></p>
        <?php if ($c['admin_note']): ?><div class="alert alert-info" style="margin-top:8px;margin-bottom:0;"><strong>Admin:</strong> <?= htmlspecialchars($c['admin_note']) ?></div><?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
</div>

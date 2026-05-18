<?php $activePage = $activePage ?? 'messages'; ?>
<div class="page-header"><h1>Messages</h1><p>Your conversations with employers and recruiters</p></div>
<?php if (empty($messages)): ?>
<div class="empty-state"><div class="empty-icon">✉️</div><h3>No messages</h3><p>Messages from employers will appear here</p></div>
<?php else: ?>
<div class="card">
<?php foreach ($messages as $m): ?>
<div style="display:flex;gap:12px;padding:16px;border-bottom:1px solid var(--border);<?= !$m['is_read'] ? 'background:rgba(139,92,246,0.05);' : '' ?>">
    <div class="avatar" style="background:var(--accent-glow);display:flex;align-items:center;justify-content:center;color:var(--accent);font-weight:700;flex-shrink:0;"><?= strtoupper(substr($m['sender_name'],0,1)) ?></div>
    <div style="flex:1;">
        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
            <strong><?= htmlspecialchars($m['sender_name']) ?></strong>
            <span style="font-size:0.8rem;color:var(--text-muted);"><?= date('M d, g:i A', strtotime($m['sent_at'])) ?></span>
        </div>
        <p style="color:var(--text-secondary);font-size:0.9rem;"><?= htmlspecialchars($m['body']) ?></p>
    </div>
</div>
<?php endforeach; ?>
</div>
<div class="card" style="margin-top:20px;">
    <h3 style="margin-bottom:12px;">Reply</h3>
    <form method="POST" action="<?= BASE_URL ?>/seeker/messages/reply">
        <?= Middleware::csrfField() ?>
        <input type="hidden" name="recipient_id" value="<?= $messages[0]['sender_id'] ?? '' ?>">
        <div class="form-group"><textarea name="body" class="form-control" rows="3" placeholder="Write your reply..." required></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Reply</button>
    </form>
</div>
<?php endif; ?>

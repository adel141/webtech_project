<?php $activePage=$activePage??'messages'; ?>
<div class="page-header"><h1>Messages</h1><p>Conversations with candidates</p></div>
<?php if(empty($messages)):?><div class="empty-state"><div class="empty-icon">✉️</div><h3>No messages yet</h3></div>
<?php else:?><div class="card"><?php foreach($messages as $m):?>
<div style="display:flex;gap:12px;padding:16px;border-bottom:1px solid var(--border);<?=!$m['is_read']?'background:rgba(139,92,246,0.05);':''?>">
<div class="avatar" style="background:var(--accent-glow);display:flex;align-items:center;justify-content:center;color:var(--accent);font-weight:700;flex-shrink:0;"><?=strtoupper(substr($m['sender_name'],0,1))?></div>
<div style="flex:1;"><div style="display:flex;justify-content:space-between;"><strong><?=htmlspecialchars($m['sender_name'])?></strong><span style="font-size:0.8rem;color:var(--text-muted);"><?=date('M d, g:i A',strtotime($m['sent_at']))?></span></div>
<p style="color:var(--text-secondary);font-size:0.9rem;"><?=htmlspecialchars($m['body'])?></p></div></div>
<?php endforeach;?></div><?php endif;?>

<?php $activePage=$activePage??'complaints';?>
<div class="page-header"><h1>Complaints</h1><p>Report issues to platform admin</p></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
<div class="card"><div class="card-header"><h3>Submit Complaint</h3></div>
<form method="POST" action="<?=BASE_URL?>/employer/complaints"><?=Middleware::csrfField()?>
<div class="form-group"><label>Subject User ID (optional)</label><input type="number" name="subject_id" class="form-control"></div>
<div class="form-group"><label>Description *</label><textarea name="description" class="form-control" rows="5" required></textarea></div>
<button type="submit" class="btn btn-primary btn-block"><i class="fas fa-flag"></i> Submit</button>
</form></div>
<div><h3 style="margin-bottom:16px;">History</h3>
<?php if(empty($complaints)):?><div class="empty-state"><h3>No complaints</h3></div>
<?php else: foreach($complaints as $c):?>
<div class="card" style="margin-bottom:12px;"><span class="badge <?=$c['status']==='open'?'badge-warning':'badge-success'?>"><?=ucfirst($c['status'])?></span><p style="margin-top:8px;color:var(--text-secondary);"><?=htmlspecialchars($c['description'])?></p>
<?php if($c['admin_note']):?><div class="alert alert-info" style="margin-top:8px;margin-bottom:0;"><strong>Admin:</strong> <?=htmlspecialchars($c['admin_note'])?></div><?php endif;?></div>
<?php endforeach; endif;?></div></div>

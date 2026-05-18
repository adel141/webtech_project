<?php $activePage = $activePage ?? 'jobs'; ?>
<div class="page-header">
    <a href="<?=BASE_URL?>/employer/jobs" class="btn btn-secondary btn-sm" style="margin-bottom:8px;"><i class="fas fa-arrow-left"></i> Back</a>
    <h1>Applicants — <?=htmlspecialchars($job['title'])?></h1>
</div>
<div class="filter-bar">
    <a href="<?=BASE_URL?>/employer/applicants/<?=$job['id']?>" class="btn <?=!$statusFilter?'btn-primary':'btn-secondary'?> btn-sm">All</a>
    <?php foreach(['submitted','reviewed','shortlisted','interview','rejected'] as $s):?>
    <a href="<?=BASE_URL?>/employer/applicants/<?=$job['id']?>?status=<?=$s?>" class="btn <?=$statusFilter===$s?'btn-primary':'btn-secondary'?> btn-sm"><?=ucfirst($s)?></a>
    <?php endforeach;?>
</div>
<?php if (empty($applicants)): ?>
<div class="empty-state"><div class="empty-icon">👥</div><h3>No applicants<?=$statusFilter?" with status '$statusFilter'":''?></h3></div>
<?php else: ?>
<div class="table-wrapper"><table class="table"><thead><tr><th>Candidate</th><th>Headline</th><th>Experience</th><th>Status</th><th>Applied</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($applicants as $a): ?>
<tr><td style="display:flex;align-items:center;gap:10px;">
    <?php if($a['profile_pic']):?><img src="<?=PUBLIC_URL?>/uploads/profile_pics/<?=htmlspecialchars($a['profile_pic'])?>" class="avatar-sm"><?php else:?><div class="avatar-sm" style="background:var(--accent-glow);display:flex;align-items:center;justify-content:center;color:var(--accent);font-weight:700;font-size:0.7rem;"><?=strtoupper(substr($a['seeker_name'],0,1))?></div><?php endif;?>
    <div><strong><?=htmlspecialchars($a['seeker_name'])?></strong><div style="font-size:0.8rem;color:var(--text-muted);"><?=htmlspecialchars($a['seeker_email'])?></div></div></td>
<td style="font-size:0.85rem;"><?=htmlspecialchars($a['headline']??'-')?></td>
<td><?=($a['years_experience']??0)?> yrs</td>
<td><select class="form-control" style="width:auto;padding:6px 30px 6px 10px;font-size:0.8rem;" onchange="updateAppStatus(<?=$a['id']?>,this.value)">
    <?php foreach(['submitted','reviewed','shortlisted','interview','rejected'] as $s):?><option value="<?=$s?>" <?=$a['status']===$s?'selected':''?>><?=ucfirst($s)?></option><?php endforeach;?>
</select></td>
<td style="font-size:0.85rem;color:var(--text-muted);"><?=date('M d',strtotime($a['applied_at']))?></td>
<td><a href="<?=BASE_URL?>/employer/applicant-detail/<?=$a['id']?>" class="btn btn-secondary btn-sm">Details</a></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>
<script>
function updateAppStatus(id,status){
    const csrf='<?=Middleware::generateCsrf()?>';
    APP.post('/api/update-application-status',{application_id:id,status:status,csrf_token:csrf}).then(r=>{
        APP.toast(r.message||'Updated!');
    }).catch(()=>APP.toast('Error','error'));
}
</script>

<?php $activePage=$activePage??'shortlisted';?>
<div class="page-header"><h1>Shortlisted Candidates</h1><p>Candidates at shortlisted or interview stage across all jobs</p></div>
<?php if(empty($shortlisted)):?><div class="empty-state"><div class="empty-icon">⭐</div><h3>No shortlisted candidates</h3></div>
<?php else:?><div class="table-wrapper"><table class="table"><thead><tr><th>Candidate</th><th>Job</th><th>Headline</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach($shortlisted as $s):?><tr><td><strong><?=htmlspecialchars($s['seeker_name'])?></strong><div style="font-size:0.8rem;color:var(--text-muted);"><?=htmlspecialchars($s['email'])?></div></td><td><?=htmlspecialchars($s['title'])?></td><td style="font-size:0.85rem;"><?=htmlspecialchars($s['headline']??'')?></td><td><span class="badge <?=$s['status']==='interview'?'badge-success':'badge-warning'?>"><?=ucfirst($s['status'])?></span></td><td><a href="<?=BASE_URL?>/employer/applicant-detail/<?=$s['id']?>" class="btn btn-secondary btn-sm">View</a></td></tr>
<?php endforeach;?></tbody></table></div><?php endif;?>

<?php $activePage=$activePage??'recruiters';?>
<div class="page-header"><h1>Recruiter Agencies</h1><p>Agencies posting jobs on your behalf</p></div>
<?php if(empty($recruiters)):?><div class="empty-state"><div class="empty-icon">🤝</div><h3>No recruiter partnerships</h3><p>Recruiters can add your company as a client</p></div>
<?php else:?><div class="table-wrapper"><table class="table"><thead><tr><th>Agency</th><th>Contact</th><th>Added</th></tr></thead><tbody>
<?php foreach($recruiters as $r):?><tr><td><strong><?=htmlspecialchars($r['agency_name']??$r['name'])?></strong></td><td><?=htmlspecialchars($r['name'])?></td><td style="font-size:0.85rem;color:var(--text-muted);"><?=date('M d, Y',strtotime($r['added_at']))?></td></tr>
<?php endforeach;?></tbody></table></div><?php endif;?>

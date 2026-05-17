<?php $activePage=$activePage??'analytics';?>
<div class="page-header"><h1>Recruitment Analytics</h1><p>Track your hiring performance</p></div>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon purple"><i class="fas fa-briefcase"></i></div><div class="stat-value"><?=count($jobs)?></div><div class="stat-label">Total Jobs Posted</div></div>
    <div class="stat-card"><div class="stat-icon blue"><i class="fas fa-file-alt"></i></div><div class="stat-value"><?=$totalApps?></div><div class="stat-label">Total Applications</div></div>
    <div class="stat-card"><div class="stat-icon green"><i class="fas fa-chart-line"></i></div><div class="stat-value"><?=count($jobs)>0?round($totalApps/count($jobs),1):0?></div><div class="stat-label">Avg Apps per Job</div></div>
</div>
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h3>Per-Job Analytics</h3></div>
    <div class="table-wrapper"><table class="table"><thead><tr><th>Job Title</th><th>Status</th><th>Applications</th><th>Actions</th></tr></thead><tbody>
    <?php foreach($jobs as $j):?><tr><td><?=htmlspecialchars($j['title'])?></td><td><span class="badge <?=$j['status']==='active'?'badge-success':'badge-secondary'?>"><?=ucfirst($j['status'])?></span></td><td><?=$j['app_count']?></td><td><a href="<?=BASE_URL?>/employer/analytics/<?=$j['id']?>" class="btn btn-secondary btn-sm">Funnel</a></td></tr>
    <?php endforeach;?></tbody></table></div>
</div>
<?php if(!empty($appTimeline)):?>
<div class="card"><div class="card-header"><h3>Applications Over Time</h3></div>
<div style="display:flex;align-items:flex-end;gap:4px;height:150px;padding:10px 0;">
<?php $maxCount=max(array_column($appTimeline,'count')); foreach(array_reverse($appTimeline) as $d):?>
<div style="flex:1;background:var(--gradient);border-radius:4px 4px 0 0;height:<?=$maxCount>0?round($d['count']/$maxCount*100):0?>%;min-height:4px;position:relative;" title="<?=$d['date']?>: <?=$d['count']?>"><span style="position:absolute;bottom:-20px;font-size:0.65rem;color:var(--text-muted);white-space:nowrap;transform:rotate(-45deg);"><?=date('M d',strtotime($d['date']))?></span></div>
<?php endforeach;?></div></div>
<?php endif;?>

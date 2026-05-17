<?php $activePage=$activePage??'analytics';?>
<div class="page-header"><a href="<?=BASE_URL?>/employer/analytics" class="btn btn-secondary btn-sm" style="margin-bottom:8px;"><i class="fas fa-arrow-left"></i> Back</a><h1>Job Funnel — <?=htmlspecialchars($job['title'])?></h1></div>
<div class="card">
<?php $funnelMap=[]; foreach($funnel as $f) $funnelMap[$f['status']]=$f['count']; $total=array_sum(array_column($funnel,'count'));?>
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;text-align:center;">
<?php foreach(['submitted','reviewed','shortlisted','interview','rejected'] as $s): $c=$funnelMap[$s]??0; $pct=$total>0?round($c/$total*100):0;?>
<div class="card" style="padding:16px;">
    <div style="font-size:2rem;font-weight:700;color:var(--accent);"><?=$c?></div>
    <div style="font-size:0.85rem;color:var(--text-secondary);text-transform:capitalize;"><?=$s?></div>
    <div class="pipeline" style="margin-top:8px;"><div class="pipeline-segment <?=$s?>" style="width:<?=$pct?>%;"></div></div>
    <div style="font-size:0.75rem;color:var(--text-muted);"><?=$pct?>%</div>
</div>
<?php endforeach;?>
</div></div>

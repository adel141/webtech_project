<?php $activePage = $activePage ?? 'jobs'; ?>
<div class="row" style="margin-bottom: 24px;">
    <div>
        <h2 class="page-title">Job Postings</h2>
        <p class="page-sub" style="margin-bottom:0;">Manage your open positions</p>
    </div>
    <div class="grow"></div>
    <a href="<?=BASE_URL?>/employer/jobs/create" class="btn primary"><i class="fas fa-plus"></i> Post New Job</a>
</div>

<?php if (empty($jobs)): ?>
<div class="card" style="padding: 40px; text-align: center; color: var(--muted);">
    <div style="font-size:32px; margin-bottom:12px;">📋</div>
    <h3 style="margin: 0 0 8px; color: var(--ink); font-weight:500;">No jobs posted yet</h3>
    <a href="<?=BASE_URL?>/employer/jobs/create" class="btn primary" style="margin-top:12px;">Create First Job</a>
</div>
<?php else: ?>
<div class="card flush">
    <table class="tbl">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Apps</th>
                <th>Deadline</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $j): ?>
            <tr>
                <td><strong><?=htmlspecialchars($j['title'])?></strong></td>
                <td><span class="muted"><?=htmlspecialchars($j['category_name']??'-')?></span></td>
                <td>
                    <?php
                    $statusClass = 'info';
                    if ($j['status'] === 'active') $statusClass = 'ok';
                    if ($j['status'] === 'closed') $statusClass = 'bad';
                    ?>
                    <span class="pill <?= $statusClass ?>"><i class="dot"></i> <?=ucfirst($j['status'])?></span>
                </td>
                <td class="num"><?=$j['app_count']?></td>
                <td class="muted"><?=$j['deadline']?date('M d, Y',strtotime($j['deadline'])):'-'?></td>
                <td>
                    <div class="row" style="justify-content: flex-end; gap: 6px; flex-wrap:wrap;">
                        <a href="<?=BASE_URL?>/employer/applicants/<?=$j['id']?>" class="btn sm">Applicants</a>
                        <a href="<?=BASE_URL?>/employer/jobs/edit/<?=$j['id']?>" class="btn sm icon"><i class="fas fa-edit"></i></a>
                        <button class="btn sm <?= $j['status']==='active' ? 'ghost' : 'ok' ?>" onclick="toggleJob(<?=$j['id']?>,'<?=$j['status']==='active'?'closed':'active'?>')">
                            <?=$j['status']==='active'?'Close':'Activate'?>
                        </button>
                        <?php if ($j['status']==='closed'):?>
                        <form method="POST" action="<?=BASE_URL?>/employer/jobs/repost/<?=$j['id']?>" style="margin:0;">
                            <?=Middleware::csrfField()?>
                            <button class="btn sm ok">Repost</button>
                        </form>
                        <?php endif;?>
                        <form method="POST" action="<?=BASE_URL?>/employer/jobs/delete/<?=$j['id']?>" style="margin:0;" onsubmit="return confirm('Delete?')">
                            <?=Middleware::csrfField()?>
                            <button class="btn sm icon" style="color:var(--bad);"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<script>
function toggleJob(id, status) {
    const csrf = '<?=Middleware::generateCsrf()?>';
    APP.post('/api/toggle-job-status', {job_id:id,status:status,csrf_token:csrf}).then(r => {
        APP.toast(r.message || 'Status updated!');
        setTimeout(() => location.reload(), 800);
    }).catch(() => APP.toast('Error','error'));
}
</script>

<?php $activePage = $activePage ?? 'dashboard'; ?>
<h2 class="page-title">Employer Dashboard</h2>
<p class="page-sub">Manage your recruitment pipeline</p>

<?php if (!$verified): ?>
<div class="pill warn" style="margin-bottom: 24px; padding: 8px 16px; height: auto;">
    <i class="dot"></i> Your account is pending admin verification. Some features may be limited.
</div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="stat">
        <div class="k"><i class="fas fa-briefcase muted" style="margin-right:6px;"></i>Total Jobs</div>
        <div class="v"><?=$totalJobs?></div>
    </div>
    <div class="stat">
        <div class="k"><i class="fas fa-check-circle muted" style="margin-right:6px;"></i>Active Jobs</div>
        <div class="v"><?=$activeJobs?></div>
    </div>
    <div class="stat">
        <div class="k"><i class="fas fa-file-alt muted" style="margin-right:6px;"></i>Total Applications</div>
        <div class="v"><?=$totalApps?></div>
    </div>
    <div class="stat">
        <div class="k"><i class="fas fa-envelope muted" style="margin-right:6px;"></i>Unread Messages</div>
        <div class="v"><?=$unread?></div>
    </div>
</div>

<div class="card flush" style="margin-bottom:24px;">
    <div class="card-h">
        <h3>Recent Job Postings</h3>
        <div class="grow"></div>
        <a href="<?=BASE_URL?>/employer/jobs/create" class="btn primary sm"><i class="fas fa-plus"></i> New Job</a>
    </div>
    <?php if (empty($jobs)): ?>
    <div style="padding: 40px; text-align: center; color: var(--muted);">
        <div style="font-size:32px; margin-bottom:12px;">📋</div>
        <h3 style="margin: 0 0 8px; color: var(--ink);">No jobs yet</h3>
        <a href="<?=BASE_URL?>/employer/jobs/create" class="btn primary">Post Your First Job</a>
    </div>
    <?php else: ?>
    <table class="tbl">
        <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Applications</th>
                <th>Deadline</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($jobs,0,5) as $j): ?>
            <tr>
                <td><strong><?=htmlspecialchars($j['title'])?></strong></td>
                <td>
                    <?php
                    $statusClass = 'info';
                    if ($j['status'] === 'active') $statusClass = 'ok';
                    if ($j['status'] === 'closed') $statusClass = 'bad';
                    ?>
                    <span class="pill <?= $statusClass ?>"><i class="dot"></i> <?=ucfirst($j['status'])?></span>
                </td>
                <td class="num"><?=$j['app_count']?></td>
                <td class="muted"><?=$j['deadline']?date('M d',strtotime($j['deadline'])):'-'?></td>
                <td style="text-align: right;">
                    <a href="<?=BASE_URL?>/employer/applicants/<?=$j['id']?>" class="btn sm">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

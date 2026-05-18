<?php $activePage = $activePage ?? 'jobs'; ?>
<div class="page-header">
    <a href="<?= BASE_URL ?>/seeker/jobs" class="btn btn-secondary btn-sm" style="margin-bottom:12px;"><i class="fas fa-arrow-left"></i> Back to Jobs</a>
    <h1><?= htmlspecialchars($job['title']) ?></h1>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div>
        <div class="card" style="margin-bottom:20px;">
            <div class="job-company" style="margin-bottom:20px;">
                <div class="company-logo" style="width:56px;height:56px;font-size:1.5rem;"><?= $job['logo_path'] ? '<img src="'.PUBLIC_URL.'/uploads/logos/'.htmlspecialchars($job['logo_path']).'">' : '<i class="fas fa-building"></i>' ?></div>
                <div>
                    <h3 style="margin-bottom:2px;"><?= htmlspecialchars($job['company_name'] ?? 'Company') ?></h3>
                    <span style="color:var(--text-muted);font-size:0.85rem;"><?= htmlspecialchars($job['industry'] ?? '') ?></span>
                </div>
            </div>
            <div class="job-meta" style="margin-bottom:20px;">
                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location'] ?? 'Remote') ?></span>
                <span><i class="fas fa-clock"></i> <?= htmlspecialchars($job['job_type']) ?></span>
                <span><i class="fas fa-layer-group"></i> <?= ucfirst($job['experience_level']) ?> Level</span>
                <?php if ($job['category_name']): ?><span><i class="fas fa-tag"></i> <?= htmlspecialchars($job['category_name']) ?></span><?php endif; ?>
                <?php if ($job['deadline']): ?><span><i class="fas fa-calendar"></i> Deadline: <?= date('M d, Y', strtotime($job['deadline'])) ?></span><?php endif; ?>
            </div>
            <?php if ($job['salary_min'] || $job['salary_max']): ?>
            <div class="job-salary" style="font-size:1.2rem;margin-bottom:20px;">$<?= number_format($job['salary_min'] ?? 0) ?> - $<?= number_format($job['salary_max'] ?? 0) ?></div>
            <?php endif; ?>
            <h3 style="margin-bottom:12px;">Description</h3>
            <div style="color:var(--text-secondary);margin-bottom:24px;white-space:pre-line;"><?= nl2br(htmlspecialchars($job['description'])) ?></div>
            <?php if ($job['requirements']): ?>
            <h3 style="margin-bottom:12px;">Requirements</h3>
            <div style="color:var(--text-secondary);margin-bottom:24px;white-space:pre-line;"><?= nl2br(htmlspecialchars($job['requirements'])) ?></div>
            <?php endif; ?>
            <?php if ($job['benefits']): ?>
            <h3 style="margin-bottom:12px;">Benefits</h3>
            <div style="color:var(--text-secondary);white-space:pre-line;"><?= nl2br(htmlspecialchars($job['benefits'])) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <!-- Apply Card -->
        <div class="card" style="margin-bottom:20px;">
            <?php if ($hasApplied): ?>
                <div class="alert alert-info" style="margin-bottom:0;"><i class="fas fa-check-circle"></i> You've already applied</div>
            <?php else: ?>
                <h3 style="margin-bottom:16px;">Apply Now</h3>
                <form method="POST" action="<?= BASE_URL ?>/seeker/apply/<?= $job['id'] ?>" enctype="multipart/form-data">
                    <?= Middleware::csrfField() ?>
                    <div class="form-group">
                        <label>Cover Letter</label>
                        <textarea name="cover_letter" class="form-control" rows="5" placeholder="Why are you a great fit?"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Resume (optional — uses profile resume if blank)</label>
                        <input type="file" name="resume" accept=".pdf" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fas fa-paper-plane"></i> Submit Application</button>
                </form>
            <?php endif; ?>
        </div>
        <!-- Save -->
        <div class="card" style="margin-bottom:20px;">
            <form method="POST" action="<?= BASE_URL ?>/seeker/toggle-save/<?= $job['id'] ?>">
                <?= Middleware::csrfField() ?>
                <button type="submit" class="btn <?= $isSaved ? 'btn-danger' : 'btn-secondary' ?> btn-block">
                    <i class="fas fa-heart"></i> <?= $isSaved ? 'Remove from Saved' : 'Save Job' ?>
                </button>
            </form>
        </div>
        <!-- Posted by -->
        <div class="card">
            <h4 style="margin-bottom:8px;">Posted by</h4>
            <p style="color:var(--text-secondary);"><?= $job['agency_name'] ? htmlspecialchars($job['agency_name']) . ' (Recruiter)' : htmlspecialchars($job['employer_name'] ?? 'Employer') ?></p>
            <p style="color:var(--text-muted);font-size:0.85rem;margin-top:4px;"><?= date('M d, Y', strtotime($job['created_at'])) ?></p>
        </div>
    </div>
</div>

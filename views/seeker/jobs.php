<?php $activePage = $activePage ?? 'jobs'; ?>
<div class="page-header"><h1>Browse Jobs</h1><p>Find your next opportunity</p></div>

<div class="filter-bar" id="job-filters">
    <input type="text" class="form-control" id="filter-keyword" placeholder="Search keyword...">
    <select class="form-control" id="filter-category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" class="form-control" id="filter-location" placeholder="Location..." style="min-width:120px;">
    <select class="form-control" id="filter-type">
        <option value="">All Types</option>
        <option value="full-time">Full-time</option>
        <option value="part-time">Part-time</option>
        <option value="remote">Remote</option>
        <option value="contract">Contract</option>
    </select>
    <select class="form-control" id="filter-exp">
        <option value="">Experience</option>
        <option value="entry">Entry</option>
        <option value="mid">Mid</option>
        <option value="senior">Senior</option>
    </select>
    <button class="btn btn-primary" onclick="filterJobs()"><i class="fas fa-search"></i> Search</button>
</div>

<div class="job-grid" id="job-results">
    <?php foreach ($jobs as $job): ?>
    <div class="job-card <?= $job['is_featured'] ? 'featured' : '' ?>">
        <div class="job-company">
            <div class="company-logo"><?= $job['logo_path'] ? '<img src="'.PUBLIC_URL.'/uploads/logos/'.htmlspecialchars($job['logo_path']).'">' : '<i class="fas fa-building"></i>' ?></div>
            <div><div class="company-name"><?= htmlspecialchars($job['company_name'] ?? 'Company') ?></div></div>
        </div>
        <a href="<?= BASE_URL ?>/seeker/jobs/<?= $job['id'] ?>" style="color:inherit;"><div class="job-title"><?= htmlspecialchars($job['title']) ?></div></a>
        <div class="job-meta">
            <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location'] ?? 'Remote') ?></span>
            <span><i class="fas fa-clock"></i> <?= htmlspecialchars($job['job_type']) ?></span>
            <span><i class="fas fa-layer-group"></i> <?= htmlspecialchars($job['experience_level']) ?></span>
            <?php if ($job['category_name']): ?><span><i class="fas fa-tag"></i> <?= htmlspecialchars($job['category_name']) ?></span><?php endif; ?>
        </div>
        <?php if ($job['salary_min'] || $job['salary_max']): ?>
        <div class="job-salary">$<?= number_format($job['salary_min'] ?? 0) ?> - $<?= number_format($job['salary_max'] ?? 0) ?></div>
        <?php endif; ?>
        <div class="job-actions">
            <a href="<?= BASE_URL ?>/seeker/jobs/<?= $job['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
            <form method="POST" action="<?= BASE_URL ?>/seeker/toggle-save/<?= $job['id'] ?>" style="display:inline;">
                <?= Middleware::csrfField() ?>
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-heart"></i></button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (empty($jobs)): ?>
<div class="empty-state"><div class="empty-icon">📋</div><h3>No jobs found</h3><p>Try adjusting your search filters</p></div>
<?php endif; ?>

<script>
function filterJobs() {
    const params = new URLSearchParams({
        keyword: document.getElementById('filter-keyword').value,
        category_id: document.getElementById('filter-category').value,
        location: document.getElementById('filter-location').value,
        job_type: document.getElementById('filter-type').value,
        experience_level: document.getElementById('filter-exp').value
    });
    APP.get('/api/filter-jobs?' + params.toString()).then(data => {
        const container = document.getElementById('job-results');
        if (!data.jobs || data.jobs.length === 0) {
            container.innerHTML = '<div class="empty-state" style="grid-column:1/-1;"><div class="empty-icon">🔍</div><h3>No jobs match your filters</h3><p>Try broadening your search</p></div>';
            return;
        }
        container.innerHTML = data.jobs.map(job => `
            <div class="job-card ${job.is_featured ? 'featured' : ''}">
                <div class="job-company">
                    <div class="company-logo">${job.logo_path ? '<img src="'+APP.baseUrl+'/uploads/logos/'+job.logo_path+'">' : '<i class="fas fa-building"></i>'}</div>
                    <div><div class="company-name">${job.company_name || 'Company'}</div></div>
                </div>
                <a href="${APP.baseUrl}/seeker/jobs/${job.id}" style="color:inherit;"><div class="job-title">${job.title}</div></a>
                <div class="job-meta">
                    <span><i class="fas fa-map-marker-alt"></i> ${job.location || 'Remote'}</span>
                    <span><i class="fas fa-clock"></i> ${job.job_type}</span>
                    <span><i class="fas fa-layer-group"></i> ${job.experience_level}</span>
                    ${job.category_name ? '<span><i class="fas fa-tag"></i> '+job.category_name+'</span>' : ''}
                </div>
                ${(job.salary_min || job.salary_max) ? '<div class="job-salary">$'+Number(job.salary_min||0).toLocaleString()+' - $'+Number(job.salary_max||0).toLocaleString()+'</div>' : ''}
                <div class="job-actions">
                    <a href="${APP.baseUrl}/seeker/jobs/${job.id}" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        `).join('');
    }).catch(err => { APP.toast('Error filtering jobs', 'error'); });
}
// Live filter on enter key
document.getElementById('filter-keyword').addEventListener('keypress', e => { if (e.key === 'Enter') filterJobs(); });
</script>

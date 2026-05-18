<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JobPortal — A recruitment marketplace connecting job seekers, employers, and recruiters.">
    <title><?= htmlspecialchars($pageTitle ?? 'JobPortal') ?> — JobPortal</title>
    <link rel="stylesheet" href="<?= PUBLIC_URL ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="jp">
<?php $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); ?>
<?php $currentUser = Auth::user(); $currentRole = Auth::role(); ?>

<?php if (Auth::check()): ?>
    <!-- SIDEBAR -->
    <aside class="jp-sb">
        <div class="jp-sb-brand">
            <div class="mark">JP</div>
            JobPortal
        </div>
        <div style="display: flex; flex-direction: column; gap: 4px; overflow-y: auto; overflow-x: hidden;">
            <?php if ($currentRole === 'seeker'): ?>
                <div class="jp-sb-role">Main</div>
                <a href="<?= BASE_URL ?>/seeker/dashboard" class="jp-sb-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <a href="<?= BASE_URL ?>/seeker/profile" class="jp-sb-item <?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-user"></i></span> My Profile</a>
                <div class="jp-sb-role">Jobs</div>
                <a href="<?= BASE_URL ?>/seeker/jobs" class="jp-sb-item <?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-briefcase"></i></span> Browse Jobs</a>
                <a href="<?= BASE_URL ?>/seeker/applications" class="jp-sb-item <?= ($activePage ?? '') === 'applications' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-file-alt"></i></span> Applications</a>
                <a href="<?= BASE_URL ?>/seeker/saved-jobs" class="jp-sb-item <?= ($activePage ?? '') === 'saved' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-heart"></i></span> Saved Jobs</a>
                <a href="<?= BASE_URL ?>/seeker/alerts" class="jp-sb-item <?= ($activePage ?? '') === 'alerts' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-bell"></i></span> Job Alerts</a>
                <div class="jp-sb-role">Communication</div>
                <a href="<?= BASE_URL ?>/seeker/messages" class="jp-sb-item <?= ($activePage ?? '') === 'messages' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-envelope"></i></span> Messages</a>
                <a href="<?= BASE_URL ?>/seeker/outreach" class="jp-sb-item <?= ($activePage ?? '') === 'outreach' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-bullhorn"></i></span> Recruiter Outreach</a>
                <a href="<?= BASE_URL ?>/seeker/complaints" class="jp-sb-item <?= ($activePage ?? '') === 'complaints' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-flag"></i></span> Complaints</a>

            <?php elseif ($currentRole === 'employer'): ?>
                <div class="jp-sb-role">Main</div>
                <a href="<?= BASE_URL ?>/employer/dashboard" class="jp-sb-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <a href="<?= BASE_URL ?>/employer/profile" class="jp-sb-item <?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-building"></i></span> Company Profile</a>
                <div class="jp-sb-role">Recruitment</div>
                <a href="<?= BASE_URL ?>/employer/jobs" class="jp-sb-item <?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-briefcase"></i></span> Job Postings</a>
                <a href="<?= BASE_URL ?>/employer/shortlisted" class="jp-sb-item <?= ($activePage ?? '') === 'shortlisted' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-star"></i></span> Shortlisted</a>
                <a href="<?= BASE_URL ?>/employer/analytics" class="jp-sb-item <?= ($activePage ?? '') === 'analytics' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-chart-bar"></i></span> Analytics</a>
                <div class="jp-sb-role">Communication</div>
                <a href="<?= BASE_URL ?>/employer/messages" class="jp-sb-item <?= ($activePage ?? '') === 'messages' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-envelope"></i></span> Messages</a>
                <a href="<?= BASE_URL ?>/employer/recruiters" class="jp-sb-item <?= ($activePage ?? '') === 'recruiters' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-handshake"></i></span> Recruiters</a>
                <a href="<?= BASE_URL ?>/employer/complaints" class="jp-sb-item <?= ($activePage ?? '') === 'complaints' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-flag"></i></span> Complaints</a>

            <?php elseif ($currentRole === 'recruiter'): ?>
                <div class="jp-sb-role">Main</div>
                <a href="<?= BASE_URL ?>/recruiter/dashboard" class="jp-sb-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <a href="<?= BASE_URL ?>/recruiter/profile" class="jp-sb-item <?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-id-badge"></i></span> Agency Profile</a>
                <a href="<?= BASE_URL ?>/recruiter/clients" class="jp-sb-item <?= ($activePage ?? '') === 'clients' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-building"></i></span> Clients</a>
                <div class="jp-sb-role">Recruitment</div>
                <a href="<?= BASE_URL ?>/recruiter/jobs" class="jp-sb-item <?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-briefcase"></i></span> Job Postings</a>
                <a href="<?= BASE_URL ?>/recruiter/candidate-search" class="jp-sb-item <?= ($activePage ?? '') === 'search' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-search"></i></span> Search Candidates</a>
                <a href="<?= BASE_URL ?>/recruiter/pipeline" class="jp-sb-item <?= ($activePage ?? '') === 'pipeline' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-stream"></i></span> Pipeline</a>
                <a href="<?= BASE_URL ?>/recruiter/placements" class="jp-sb-item <?= ($activePage ?? '') === 'placements' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-trophy"></i></span> Placements</a>
                <div class="jp-sb-role">Communication</div>
                <a href="<?= BASE_URL ?>/recruiter/outreach" class="jp-sb-item <?= ($activePage ?? '') === 'outreach' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-paper-plane"></i></span> Outreach</a>
                <a href="<?= BASE_URL ?>/recruiter/analytics" class="jp-sb-item <?= ($activePage ?? '') === 'analytics' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-chart-line"></i></span> Analytics</a>

            <?php elseif ($currentRole === 'admin'): ?>
                <div class="jp-sb-role">Overview</div>
                <a href="<?= BASE_URL ?>/admin/dashboard" class="jp-sb-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <div class="jp-sb-role">Users</div>
                <a href="<?= BASE_URL ?>/admin/employers" class="jp-sb-item <?= ($activePage ?? '') === 'employers' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-building"></i></span> Employers</a>
                <a href="<?= BASE_URL ?>/admin/recruiters" class="jp-sb-item <?= ($activePage ?? '') === 'recruiters' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-id-badge"></i></span> Recruiters</a>
                <a href="<?= BASE_URL ?>/admin/seekers" class="jp-sb-item <?= ($activePage ?? '') === 'seekers' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-users"></i></span> Seekers</a>
                <div class="jp-sb-role">Platform</div>
                <a href="<?= BASE_URL ?>/admin/categories" class="jp-sb-item <?= ($activePage ?? '') === 'categories' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-tags"></i></span> Categories</a>
                <a href="<?= BASE_URL ?>/admin/jobs" class="jp-sb-item <?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-briefcase"></i></span> All Jobs</a>
                <a href="<?= BASE_URL ?>/admin/complaints" class="jp-sb-item <?= ($activePage ?? '') === 'complaints' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-exclamation-triangle"></i></span> Complaints</a>
                <a href="<?= BASE_URL ?>/admin/announcements" class="jp-sb-item <?= ($activePage ?? '') === 'announcements' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-bullhorn"></i></span> Announcements</a>
                <div class="jp-sb-role">Reports</div>
                <a href="<?= BASE_URL ?>/admin/analytics" class="jp-sb-item <?= ($activePage ?? '') === 'analytics' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-chart-pie"></i></span> Analytics</a>
                <a href="<?= BASE_URL ?>/admin/user-growth" class="jp-sb-item <?= ($activePage ?? '') === 'growth' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-chart-line"></i></span> User Growth</a>
                <a href="<?= BASE_URL ?>/admin/settings" class="jp-sb-item <?= ($activePage ?? '') === 'settings' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-cog"></i></span> Settings</a>
                <a href="<?= BASE_URL ?>/admin/reports" class="jp-sb-item <?= ($activePage ?? '') === 'reports' ? 'active' : '' ?>"><span class="ico"><i class="fas fa-file-export"></i></span> Export Report</a>
            <?php endif; ?>
        </div>
        <div class="jp-sb-spacer"></div>
        <div class="jp-sb-user">
            <?php if ($currentUser['profile_pic']): ?>
                <img src="<?= PUBLIC_URL ?>/uploads/profile_pics/<?= htmlspecialchars($currentUser['profile_pic']) ?>" class="av" alt="avatar">
            <?php else: ?>
                <div class="av"><?= strtoupper(substr($currentUser['name'],0,1)) ?></div>
            <?php endif; ?>
            <div style="display:flex; flex-direction:column; margin-left: 4px;">
                <div class="name"><?= htmlspecialchars($currentUser['name']) ?></div>
                <div class="sub"><?= htmlspecialchars($currentRole) ?></div>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="jp-main">
        <header class="jp-top">
            <h1><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
            <div class="jp-top-search">
                <i class="fas fa-search muted"></i>
                <input type="text" placeholder="Search..." style="border:none; background:transparent; width:100%; outline:none; font-family:var(--sans); font-size:12.5px; color:var(--ink);" />
            </div>
            <div class="jp-top-right">
                <?php if ($currentRole === 'seeker'): ?>
                    <a href="<?= BASE_URL ?>/seeker/notifications" class="btn icon ghost"><i class="fas fa-bell"></i></a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/logout" class="btn ghost"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>
        <div class="jp-content pad-lg">
            <?php if ($flash): ?>
                <div class="pill <?= $flash['type'] === 'success' ? 'ok' : ($flash['type'] === 'error' ? 'bad' : 'info') ?>" style="margin-bottom: 20px;">
                    <i class="dot"></i> <?= htmlspecialchars($flash['message']) ?>
                </div>
            <?php endif; ?>
<?php else: ?>
<!-- No sidebar for auth pages -->
<?php endif; ?>

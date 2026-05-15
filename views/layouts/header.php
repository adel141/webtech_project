<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JobPortal — A recruitment marketplace connecting job seekers, employers, and recruiters.">
    <title><?= htmlspecialchars($pageTitle ?? 'JobPortal') ?> — JobPortal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); ?>
<?php $currentUser = Auth::user(); $currentRole = Auth::role(); ?>

<?php if (Auth::check()): ?>
<div class="app-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>JobPortal</h2>
            <span><?= htmlspecialchars(ucfirst($currentRole)) ?> Panel</span>
        </div>
        <nav class="sidebar-nav">
            <?php if ($currentRole === 'seeker'): ?>
                <div class="nav-section">Main</div>
                <a href="<?= BASE_URL ?>/seeker/dashboard" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <a href="<?= BASE_URL ?>/seeker/profile" class="<?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-user"></i></span> My Profile</a>
                <div class="nav-section">Jobs</div>
                <a href="<?= BASE_URL ?>/seeker/jobs" class="<?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-briefcase"></i></span> Browse Jobs</a>
                <a href="<?= BASE_URL ?>/seeker/applications" class="<?= ($activePage ?? '') === 'applications' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-file-alt"></i></span> Applications</a>
                <a href="<?= BASE_URL ?>/seeker/saved-jobs" class="<?= ($activePage ?? '') === 'saved' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-heart"></i></span> Saved Jobs</a>
                <a href="<?= BASE_URL ?>/seeker/alerts" class="<?= ($activePage ?? '') === 'alerts' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-bell"></i></span> Job Alerts</a>
                <div class="nav-section">Communication</div>
                <a href="<?= BASE_URL ?>/seeker/messages" class="<?= ($activePage ?? '') === 'messages' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-envelope"></i></span> Messages</a>
                <a href="<?= BASE_URL ?>/seeker/outreach" class="<?= ($activePage ?? '') === 'outreach' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-bullhorn"></i></span> Recruiter Outreach</a>
                <a href="<?= BASE_URL ?>/seeker/complaints" class="<?= ($activePage ?? '') === 'complaints' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-flag"></i></span> Complaints</a>

            <?php elseif ($currentRole === 'employer'): ?>
                <div class="nav-section">Main</div>
                <a href="<?= BASE_URL ?>/employer/dashboard" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <a href="<?= BASE_URL ?>/employer/profile" class="<?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-building"></i></span> Company Profile</a>
                <div class="nav-section">Recruitment</div>
                <a href="<?= BASE_URL ?>/employer/jobs" class="<?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-briefcase"></i></span> Job Postings</a>
                <a href="<?= BASE_URL ?>/employer/shortlisted" class="<?= ($activePage ?? '') === 'shortlisted' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-star"></i></span> Shortlisted</a>
                <a href="<?= BASE_URL ?>/employer/analytics" class="<?= ($activePage ?? '') === 'analytics' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-chart-bar"></i></span> Analytics</a>
                <div class="nav-section">Communication</div>
                <a href="<?= BASE_URL ?>/employer/messages" class="<?= ($activePage ?? '') === 'messages' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-envelope"></i></span> Messages</a>
                <a href="<?= BASE_URL ?>/employer/recruiters" class="<?= ($activePage ?? '') === 'recruiters' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-handshake"></i></span> Recruiters</a>
                <a href="<?= BASE_URL ?>/employer/complaints" class="<?= ($activePage ?? '') === 'complaints' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-flag"></i></span> Complaints</a>

            <?php elseif ($currentRole === 'recruiter'): ?>
                <div class="nav-section">Main</div>
                <a href="<?= BASE_URL ?>/recruiter/dashboard" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <a href="<?= BASE_URL ?>/recruiter/profile" class="<?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-id-badge"></i></span> Agency Profile</a>
                <a href="<?= BASE_URL ?>/recruiter/clients" class="<?= ($activePage ?? '') === 'clients' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-building"></i></span> Clients</a>
                <div class="nav-section">Recruitment</div>
                <a href="<?= BASE_URL ?>/recruiter/jobs" class="<?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-briefcase"></i></span> Job Postings</a>
                <a href="<?= BASE_URL ?>/recruiter/candidate-search" class="<?= ($activePage ?? '') === 'search' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-search"></i></span> Search Candidates</a>
                <a href="<?= BASE_URL ?>/recruiter/pipeline" class="<?= ($activePage ?? '') === 'pipeline' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-stream"></i></span> Pipeline</a>
                <a href="<?= BASE_URL ?>/recruiter/placements" class="<?= ($activePage ?? '') === 'placements' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-trophy"></i></span> Placements</a>
                <div class="nav-section">Communication</div>
                <a href="<?= BASE_URL ?>/recruiter/outreach" class="<?= ($activePage ?? '') === 'outreach' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-paper-plane"></i></span> Outreach</a>
                <a href="<?= BASE_URL ?>/recruiter/analytics" class="<?= ($activePage ?? '') === 'analytics' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-chart-line"></i></span> Analytics</a>

            <?php elseif ($currentRole === 'admin'): ?>
                <div class="nav-section">Overview</div>
                <a href="<?= BASE_URL ?>/admin/dashboard" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-th-large"></i></span> Dashboard</a>
                <div class="nav-section">Users</div>
                <a href="<?= BASE_URL ?>/admin/employers" class="<?= ($activePage ?? '') === 'employers' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-building"></i></span> Employers</a>
                <a href="<?= BASE_URL ?>/admin/recruiters" class="<?= ($activePage ?? '') === 'recruiters' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-id-badge"></i></span> Recruiters</a>
                <a href="<?= BASE_URL ?>/admin/seekers" class="<?= ($activePage ?? '') === 'seekers' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-users"></i></span> Seekers</a>
                <div class="nav-section">Platform</div>
                <a href="<?= BASE_URL ?>/admin/categories" class="<?= ($activePage ?? '') === 'categories' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-tags"></i></span> Categories</a>
                <a href="<?= BASE_URL ?>/admin/jobs" class="<?= ($activePage ?? '') === 'jobs' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-briefcase"></i></span> All Jobs</a>
                <a href="<?= BASE_URL ?>/admin/complaints" class="<?= ($activePage ?? '') === 'complaints' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-exclamation-triangle"></i></span> Complaints</a>
                <a href="<?= BASE_URL ?>/admin/announcements" class="<?= ($activePage ?? '') === 'announcements' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-bullhorn"></i></span> Announcements</a>
                <div class="nav-section">Reports</div>
                <a href="<?= BASE_URL ?>/admin/analytics" class="<?= ($activePage ?? '') === 'analytics' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-chart-pie"></i></span> Analytics</a>
                <a href="<?= BASE_URL ?>/admin/user-growth" class="<?= ($activePage ?? '') === 'growth' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-chart-line"></i></span> User Growth</a>
                <a href="<?= BASE_URL ?>/admin/settings" class="<?= ($activePage ?? '') === 'settings' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-cog"></i></span> Settings</a>
                <a href="<?= BASE_URL ?>/admin/reports" class="<?= ($activePage ?? '') === 'reports' ? 'active' : '' ?>"><span class="icon"><i class="fas fa-file-export"></i></span> Export Report</a>
            <?php endif; ?>
        </nav>
        <div class="sidebar-user">
            <?php if ($currentUser['profile_pic']): ?>
                <img src="<?= BASE_URL ?>/uploads/profile_pics/<?= htmlspecialchars($currentUser['profile_pic']) ?>" alt="avatar">
            <?php else: ?>
                <div class="avatar" style="background:var(--accent-glow);display:flex;align-items:center;justify-content:center;color:var(--accent);font-weight:700;"><?= strtoupper(substr($currentUser['name'],0,1)) ?></div>
            <?php endif; ?>
            <div class="user-info">
                <div class="name"><?= htmlspecialchars($currentUser['name']) ?></div>
                <div class="role"><?= htmlspecialchars($currentRole) ?></div>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main-content">
        <header class="top-header">
            <button id="sidebar-toggle" class="btn btn-secondary btn-sm" style="display:none;"><i class="fas fa-bars"></i></button>
            <div></div>
            <div style="display:flex;align-items:center;gap:16px;">
                <?php if ($currentRole === 'seeker'): ?>
                    <a href="<?= BASE_URL ?>/seeker/notifications" class="btn btn-secondary btn-sm"><i class="fas fa-bell"></i></a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/logout" class="btn btn-secondary btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </header>
        <div class="page-content fade-in">
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
            <?php endif; ?>
<?php else: ?>
<!-- No sidebar for auth pages -->
<?php endif; ?>

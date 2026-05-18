<?php
require_once __DIR__ . '/../../config/remember.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../../login.php");
    exit;
}

if($_SESSION['role'] != 'recruiter'){
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Seekers</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <h2>Recruiter</h2>
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="profile.php">Profile</a>
            <a class="nav-link" href="clients.php">Clients</a>
            <a class="nav-link" href="jobs.php">Jobs</a>
            <a class="nav-link active" href="seekers.php">Seekers</a>
            <a class="nav-link" href="outreach.php">Outreach</a>
            <a class="nav-link" href="applications.php">Applications</a>
            <a class="nav-link" href="pipeline.php">Pipeline</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Seekers</h1>
                    <p class="muted">Search candidates by skills, location, experience and salary</p>
                </div>
            </div>

            <div class="form-card">
                <div class="form-grid">
                    <div>
                        <label>Keyword</label>
                        <input type="text" id="keyword">
                    </div>
                    <div>
                        <label>Location</label>
                        <input type="text" id="location">
                    </div>
                    <div>
                        <label>Minimum Experience</label>
                        <input type="number" id="experience">
                    </div>
                    <div>
                        <label>Maximum Salary</label>
                        <input type="number" id="salary">
                    </div>
                </div>
                <br>
                <button class="btn btn-primary" onclick="searchSeekers()">Search</button>
            </div>

            <div id="searchResults" class="search-results"></div>
        </main>
    </div>
    <script src="../../assets/js/recruiter.js"></script>
</body>
</html>

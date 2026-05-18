<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../../login.php");
    exit;
}

if($_SESSION['role'] != 'recruiter'){
    header("Location: ../../login.php");
    exit;
}

require_once '../../controllers/RecruiterController.php';

$controller = new RecruiterController();
$profile = $controller->profile($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Profile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <h2>Recruiter</h2>
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link active" href="profile.php">Profile</a>
            <a class="nav-link" href="clients.php">Clients</a>
            <a class="nav-link" href="jobs.php">Jobs</a>
            <a class="nav-link" href="seekers.php">Seekers</a>
            <a class="nav-link" href="outreach.php">Outreach</a>
            <a class="nav-link" href="applications.php">Applications</a>
            <a class="nav-link" href="pipeline.php">Pipeline</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Profile</h1>
                    <p class="muted"><?php echo htmlspecialchars($profile['email'] ?? ''); ?></p>
                </div>
            </div>

            <form id="profileForm" class="form-card">
                <div class="form-grid">
                    <div>
                        <label>Agency Name</label>
                        <input type="text" name="agency_name" value="<?php echo htmlspecialchars($profile['agency_name'] ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>Specialization</label>
                        <input type="text" name="specialization" value="<?php echo htmlspecialchars($profile['specialization'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Website</label>
                        <input type="text" name="website" value="<?php echo htmlspecialchars($profile['website'] ?? ''); ?>">
                    </div>
                </div>
                <br>
                <div>
                    <label>Description</label>
                    <textarea name="description"><?php echo htmlspecialchars($profile['description'] ?? ''); ?></textarea>
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Save Profile</button>
            </form>
        </main>
    </div>
    <script src="../../assets/js/recruiter.js"></script>
</body>
</html>

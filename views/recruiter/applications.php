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

require_once '../../controllers/RecruiterController.php';

$controller = new RecruiterController();
$applications = $controller->getApplications($_SESSION['user_id']);
$statuses = ['submitted', 'reviewed', 'shortlisted', 'interview', 'rejected', 'withdrawn'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
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
            <a class="nav-link" href="seekers.php">Seekers</a>
            <a class="nav-link" href="outreach.php">Outreach</a>
            <a class="nav-link active" href="applications.php">Applications</a>
            <a class="nav-link" href="pipeline.php">Pipeline</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Applications</h1>
                    <p class="muted">Applications for your jobs</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Email</th>
                            <th>Job</th>
                            <th>Status</th>
                            <th>Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($applications as $application){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($application['seeker_name']); ?></td>
                                <td><?php echo htmlspecialchars($application['seeker_email']); ?></td>
                                <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                                <td>
                                    <select onchange="updateApplicationStatus(<?php echo $application['id']; ?>, this.value)">
                                        <?php foreach($statuses as $status){ ?>
                                            <option value="<?php echo $status; ?>" <?php echo $status == $application['status'] ? 'selected' : ''; ?>>
                                                <?php echo ucfirst($status); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><?php echo htmlspecialchars($application['created_at']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="../../assets/js/recruiter.js"></script>
</body>
</html>

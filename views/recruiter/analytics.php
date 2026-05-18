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
$data = $controller->analytics($_SESSION['user_id']);
$summary = $data['summary'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Analytics</title>
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
            <a class="nav-link" href="applications.php">Applications</a>
            <a class="nav-link" href="pipeline.php">Pipeline</a>
            <a class="nav-link active" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Analytics</h1>
                    <p class="muted">Recruiter activity summary</p>
                </div>
            </div>

            <section class="grid">
                <div class="card">
                    <p class="card-title">Total Jobs</p>
                    <p class="card-value"><?php echo htmlspecialchars($summary['total_jobs']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Active Jobs</p>
                    <p class="card-value"><?php echo htmlspecialchars($summary['active_jobs']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Clients</p>
                    <p class="card-value"><?php echo htmlspecialchars($summary['total_clients']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Outreach</p>
                    <p class="card-value"><?php echo htmlspecialchars($summary['total_outreach']); ?></p>
                </div>
            </section>

            <section class="card">
                <h2>Applications by Status</h2>
                <br>
                <table>
                    <tbody>
                        <?php foreach($data['applications_by_status'] as $row){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
                                <td><?php echo htmlspecialchars($row['cnt']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

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
$pipeline = $controller->pipeline($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pipeline</title>
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
            <a class="nav-link active" href="pipeline.php">Pipeline</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Pipeline</h1>
                    <p class="muted">Candidates grouped by status</p>
                </div>
            </div>

            <div class="pipeline-grid">
                <?php foreach($pipeline as $status => $items){ ?>
                    <section class="card">
                        <div class="section-header">
                            <h2><?php echo htmlspecialchars(ucfirst($status)); ?></h2>
                            <span class="badge badge-blue"><?php echo count($items); ?></span>
                        </div>
                        <table>
                            <tbody>
                                <?php if(count($items) == 0){ ?>
                                    <tr><td>No candidates.</td></tr>
                                <?php } ?>
                                <?php foreach($items as $item){ ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($item['seeker_name']); ?></strong><br>
                                            <span class="muted"><?php echo htmlspecialchars($item['job_title']); ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                <?php } ?>
            </div>
        </main>
    </div>
</body>
</html>

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
$jobs = $controller->getJobs($_SESSION['user_id']);
$seekers = $controller->searchSeekers();
$outreach = $controller->getOutreach($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outreach</title>
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
            <a class="nav-link active" href="outreach.php">Outreach</a>
            <a class="nav-link" href="applications.php">Applications</a>
            <a class="nav-link" href="pipeline.php">Pipeline</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Outreach</h1>
                    <p class="muted">Send job messages to seekers</p>
                </div>
            </div>

            <form id="outreachForm" class="form-card">
                <div class="form-grid">
                    <div>
                        <label>Seeker</label>
                        <select name="seeker_id" required>
                            <option value="">Select seeker</option>
                            <?php foreach($seekers as $seeker){ ?>
                                <option value="<?php echo $seeker['id']; ?>"><?php echo htmlspecialchars($seeker['name']); ?> - <?php echo htmlspecialchars($seeker['email']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Job</label>
                        <select name="job_id" required>
                            <option value="">Select job</option>
                            <?php foreach($jobs as $job){ ?>
                                <option value="<?php echo $job['id']; ?>"><?php echo htmlspecialchars($job['title']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <br>
                <div>
                    <label>Message</label>
                    <textarea name="message" required></textarea>
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Send Outreach</button>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Seeker</th>
                            <th>Job</th>
                            <th>Message</th>
                            <th>Sent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($outreach as $item){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['seeker_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($item['job_title'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($item['message']); ?></td>
                                <td><?php echo htmlspecialchars($item['created_at']); ?></td>
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

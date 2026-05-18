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
$jobs = $controller->getJobs($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Jobs</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <h2>Recruiter</h2>
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="profile.php">Profile</a>
            <a class="nav-link" href="clients.php">Clients</a>
            <a class="nav-link active" href="jobs.php">Jobs</a>
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
                    <h1>Jobs</h1>
                    <p class="muted">Your job posts</p>
                </div>
                <a class="btn btn-primary" href="addJob.php">Add Job</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Salary</th>
                            <th>Location</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($jobs as $job){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($job['title']); ?></td>
                                <td><?php echo htmlspecialchars($job['category_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($job['salary_min']); ?> - <?php echo htmlspecialchars($job['salary_max']); ?></td>
                                <td><?php echo htmlspecialchars($job['location']); ?></td>
                                <td><?php echo htmlspecialchars($job['deadline']); ?></td>
                                <td><span class="badge badge-blue"><?php echo htmlspecialchars($job['status']); ?></span></td>
                                <td class="actions">
                                    <a class="btn btn-light" href="editJob.php?job_id=<?php echo $job['id']; ?>">Edit</a>
                                    <?php if($job['status'] != 'closed'){ ?>
                                        <button class="btn btn-warning" onclick="closeRecruiterJob(<?php echo $job['id']; ?>)">Close</button>
                                    <?php } ?>
                                    <button class="btn btn-danger" onclick="deleteRecruiterJob(<?php echo $job['id']; ?>)">Delete</button>
                                </td>
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

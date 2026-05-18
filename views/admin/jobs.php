<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../../login.php");
    exit;
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../../login.php");
    exit;
}

require_once '../../controllers/AdminController.php';

$controller = new AdminController();
$jobs = $controller->getAllJobs();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <h2>Job Portal</h2>
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="users.php">Users</a>
            <a class="nav-link" href="employers.php">Employers</a>
            <a class="nav-link" href="recruiters.php">Recruiters</a>
            <a class="nav-link" href="seekers.php">Seekers</a>
            <a class="nav-link" href="categories.php">Categories</a>
            <a class="nav-link active" href="jobs.php">Jobs</a>
            <a class="nav-link" href="complaints.php">Complaints</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Jobs</h1>
                    <p class="muted">All jobs posted by recruiters</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Recruiter</th>
                            <th>Employer</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($jobs as $job){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($job['title']); ?></td>
                                <td><?php echo htmlspecialchars($job['category_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($job['recruiter_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($job['employer_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($job['location']); ?></td>
                                <td><span class="badge badge-blue"><?php echo htmlspecialchars($job['status']); ?></span></td>
                                <td>
                                    <span class="badge <?php echo $job['is_featured'] == 1 ? 'badge-green' : 'badge-amber'; ?>">
                                        <?php echo $job['is_featured'] == 1 ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <?php if($job['is_featured'] == 1){ ?>
                                        <button class="btn btn-warning" onclick="toggleFeatured(<?php echo $job['id']; ?>, 0)">Unfeature</button>
                                    <?php }else{ ?>
                                        <button class="btn btn-success" onclick="toggleFeatured(<?php echo $job['id']; ?>, 1)">Feature</button>
                                    <?php } ?>
                                    <button class="btn btn-danger" onclick="deleteJob(<?php echo $job['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="../../assets/js/admin.js"></script>
</body>
</html>

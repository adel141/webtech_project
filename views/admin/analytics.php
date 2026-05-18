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
$data = $controller->analytics();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
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
            <a class="nav-link" href="jobs.php">Jobs</a>
            <a class="nav-link" href="complaints.php">Complaints</a>
            <a class="nav-link active" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Analytics</h1>
                    <p class="muted">Simple job portal metrics</p>
                </div>
            </div>

            <section class="grid">
                <div class="card">
                    <p class="card-title">Active Jobs</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['active_jobs']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Applications</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['application_count']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Recruiters</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['recruiter_count']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Open Complaints</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['open_complaints']); ?></p>
                </div>
            </section>

            <section class="grid grid-three">
                <div class="card">
                    <h2>Jobs by Category</h2>
                    <br>
                    <table>
                        <tbody>
                            <?php foreach($data['jobs_by_category'] as $row){ ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cnt']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <h2>Users by Role</h2>
                    <br>
                    <table>
                        <tbody>
                            <?php foreach($data['users_by_role'] as $row){ ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cnt']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

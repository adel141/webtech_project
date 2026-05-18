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
$data = $controller->dashboard();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <h2>Job Portal</h2>
            <a class="nav-link active" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="users.php">Users</a>
            <a class="nav-link" href="employers.php">Employers</a>
            <a class="nav-link" href="recruiters.php">Recruiters</a>
            <a class="nav-link" href="seekers.php">Seekers</a>
            <a class="nav-link" href="categories.php">Categories</a>
            <a class="nav-link" href="jobs.php">Jobs</a>
            <a class="nav-link" href="complaints.php">Complaints</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Admin Dashboard</h1>
                    <p class="muted">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></p>
                </div>
            </div>

            <section class="grid">
                <div class="card">
                    <p class="card-title">Seekers</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['seeker']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Employers</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['employer']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Recruiters</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['recruiter']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Active Jobs</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['active_jobs']); ?></p>
                </div>
            </section>

            <section class="grid grid-three">
                <div class="card">
                    <p class="card-title">Recent Applications</p>
                    <p class="card-value"><?php echo htmlspecialchars($data['recent_applications']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Pending Employers</p>
                    <p class="card-value"><?php echo count($data['pending_employers']); ?></p>
                </div>
                <div class="card">
                    <p class="card-title">Pending Recruiters</p>
                    <p class="card-value"><?php echo count($data['pending_recruiters']); ?></p>
                </div>
            </section>

            <section class="section">
                <div class="section-header">
                    <h2>Pending Employers</h2>
                    <a class="btn btn-light" href="employers.php">View All</a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($data['pending_employers']) == 0){ ?>
                                <tr><td colspan="4">No pending employers.</td></tr>
                            <?php } ?>
                            <?php foreach($data['pending_employers'] as $user){ ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                    <td class="actions">
                                        <button class="btn btn-success" onclick="approveUser(<?php echo $user['id']; ?>)">Approve</button>
                                        <button class="btn btn-danger" onclick="rejectUser(<?php echo $user['id']; ?>)">Reject</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="section">
                <div class="section-header">
                    <h2>Pending Recruiters</h2>
                    <a class="btn btn-light" href="recruiters.php">View All</a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($data['pending_recruiters']) == 0){ ?>
                                <tr><td colspan="4">No pending recruiters.</td></tr>
                            <?php } ?>
                            <?php foreach($data['pending_recruiters'] as $user){ ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                    <td class="actions">
                                        <button class="btn btn-success" onclick="approveUser(<?php echo $user['id']; ?>)">Approve</button>
                                        <button class="btn btn-danger" onclick="rejectUser(<?php echo $user['id']; ?>)">Reject</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <script src="../../assets/js/admin.js"></script>
</body>
</html>

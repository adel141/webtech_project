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
$complaints = $controller->getAllComplaints();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints</title>
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
            <a class="nav-link active" href="complaints.php">Complaints</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Complaints</h1>
                    <p class="muted">User complaints and resolution notes</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Admin Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($complaints as $complaint){ ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($complaint['user_name'] ?? ''); ?><br>
                                    <span class="muted"><?php echo htmlspecialchars($complaint['user_email'] ?? ''); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($complaint['subject']); ?></td>
                                <td><?php echo htmlspecialchars($complaint['message']); ?></td>
                                <td>
                                    <span class="badge <?php echo $complaint['status'] == 'resolved' ? 'badge-green' : 'badge-amber'; ?>">
                                        <?php echo htmlspecialchars($complaint['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($complaint['admin_note'] ?? ''); ?></td>
                                <td class="actions">
                                    <?php if($complaint['status'] != 'resolved'){ ?>
                                        <button class="btn btn-success" onclick="resolveComplaint(<?php echo $complaint['id']; ?>)">Resolve</button>
                                    <?php } ?>
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

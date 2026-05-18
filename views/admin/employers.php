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
$employers = $controller->getUserByRole('employer');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employers</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <h2>Job Portal</h2>
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="users.php">Users</a>
            <a class="nav-link active" href="employers.php">Employers</a>
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
                    <h1>Employers</h1>
                    <p class="muted">Review and manage employer accounts</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Industry</th>
                            <th>Approved</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($employers as $user){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['company_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($user['industry'] ?? ''); ?></td>
                                <td><?php echo $user['is_approved'] == 1 ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <span class="badge <?php echo $user['is_active'] == 1 ? 'badge-green' : 'badge-red'; ?>">
                                        <?php echo $user['is_active'] == 1 ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <?php if($user['is_approved'] == 0){ ?>
                                        <button class="btn btn-success" onclick="approveUser(<?php echo $user['id']; ?>)">Approve</button>
                                    <?php } ?>
                                    <?php if($user['is_active'] == 1){ ?>
                                        <button class="btn btn-warning" onclick="toggleUserStatus(<?php echo $user['id']; ?>, 0)">Deactivate</button>
                                    <?php }else{ ?>
                                        <button class="btn btn-success" onclick="toggleUserStatus(<?php echo $user['id']; ?>, 1)">Activate</button>
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

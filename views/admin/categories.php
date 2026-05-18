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
$categories = $controller->getAllCategories();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
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
            <a class="nav-link active" href="categories.php">Categories</a>
            <a class="nav-link" href="jobs.php">Jobs</a>
            <a class="nav-link" href="complaints.php">Complaints</a>
            <a class="nav-link" href="analytics.php">Analytics</a>
            <a class="nav-link" href="../../ajax/auth/logout.php">Logout</a>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Categories</h1>
                    <p class="muted">Total categories: <?php echo count($categories); ?></p>
                </div>
            </div>

            <form id="categoryForm" class="form-card">
                <div class="form-grid">
                    <div>
                        <label>Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div>
                        <label>Description</label>
                        <input type="text" name="description">
                    </div>
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Add Category</button>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Jobs</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $category){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                <td><?php echo htmlspecialchars($category['description']); ?></td>
                                <td><span class="badge badge-blue"><?php echo htmlspecialchars($category['job_count']); ?></span></td>
                                <td class="actions">
                                    <button class="btn btn-light" onclick="updateCategory(<?php echo $category['id']; ?>)">Edit</button>
                                    <button class="btn btn-danger" onclick="deleteCategory(<?php echo $category['id']; ?>)">Delete</button>
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

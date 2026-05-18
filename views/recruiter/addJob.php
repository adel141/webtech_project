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
$categories = $controller->getCategories();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job</title>
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
                    <h1>Add Job</h1>
                    <p class="muted">Create a recruiter job post</p>
                </div>
            </div>

            <form id="addJobForm" class="form-card">
                <div class="form-grid">
                    <div>
                        <label>Category</label>
                        <select name="category_id" required>
                            <option value="">Select category</option>
                            <?php foreach($categories as $category){ ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Employer ID</label>
                        <input type="number" name="employer_id">
                    </div>
                    <div>
                        <label>Title</label>
                        <input type="text" name="title" required>
                    </div>
                    <div>
                        <label>Location</label>
                        <input type="text" name="location">
                    </div>
                    <div>
                        <label>Salary Min</label>
                        <input type="number" name="salary_min" value="0">
                    </div>
                    <div>
                        <label>Salary Max</label>
                        <input type="number" name="salary_max" value="0">
                    </div>
                    <div>
                        <label>Job Type</label>
                        <select name="job_type">
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="contract">Contract</option>
                            <option value="remote">Remote</option>
                        </select>
                    </div>
                    <div>
                        <label>Experience Level</label>
                        <select name="experience_level">
                            <option value="entry">Entry</option>
                            <option value="mid">Mid</option>
                            <option value="senior">Senior</option>
                        </select>
                    </div>
                    <div>
                        <label>Deadline</label>
                        <input type="date" name="deadline" required>
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="status">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="form-grid">
                    <div>
                        <label>Description</label>
                        <textarea name="description" required></textarea>
                    </div>
                    <div>
                        <label>Requirements</label>
                        <textarea name="requirements"></textarea>
                    </div>
                    <div>
                        <label>Benefits</label>
                        <textarea name="benefits"></textarea>
                    </div>
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Save Job</button>
                <a class="btn btn-light" href="jobs.php">Cancel</a>
            </form>
        </main>
    </div>
    <script src="../../assets/js/recruiter.js"></script>
</body>
</html>

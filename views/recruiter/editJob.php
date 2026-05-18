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

if(!isset($_GET['job_id'])){
    header("Location: jobs.php");
    exit;
}

require_once '../../controllers/RecruiterController.php';

$controller = new RecruiterController();
$job = $controller->getJobById($_GET['job_id']);
$categories = $controller->getCategories();

if(!$job || $job['recruiter_id'] != $_SESSION['user_id']){
    header("Location: jobs.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
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
                    <h1>Edit Job</h1>
                    <p class="muted"><?php echo htmlspecialchars($job['title']); ?></p>
                </div>
            </div>

            <form id="editJobForm" class="form-card">
                <input type="hidden" id="job_id" value="<?php echo $job['id']; ?>">
                <div class="form-grid">
                    <div>
                        <label>Category</label>
                        <select name="category_id" required>
                            <?php foreach($categories as $category){ ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $job['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label>Employer ID</label>
                        <input type="number" name="employer_id" value="<?php echo htmlspecialchars($job['employer_id'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
                    </div>
                    <div>
                        <label>Location</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($job['location']); ?>">
                    </div>
                    <div>
                        <label>Salary Min</label>
                        <input type="number" name="salary_min" value="<?php echo htmlspecialchars($job['salary_min']); ?>">
                    </div>
                    <div>
                        <label>Salary Max</label>
                        <input type="number" name="salary_max" value="<?php echo htmlspecialchars($job['salary_max']); ?>">
                    </div>
                    <div>
                        <label>Job Type</label>
                        <select name="job_type">
                            <option value="full-time" <?php echo $job['job_type'] == 'full-time' ? 'selected' : ''; ?>>Full Time</option>
                            <option value="part-time" <?php echo $job['job_type'] == 'part-time' ? 'selected' : ''; ?>>Part Time</option>
                            <option value="contract" <?php echo $job['job_type'] == 'contract' ? 'selected' : ''; ?>>Contract</option>
                            <option value="remote" <?php echo $job['job_type'] == 'remote' ? 'selected' : ''; ?>>Remote</option>
                        </select>
                    </div>
                    <div>
                        <label>Experience Level</label>
                        <select name="experience_level">
                            <option value="entry" <?php echo $job['experience_level'] == 'entry' ? 'selected' : ''; ?>>Entry</option>
                            <option value="mid" <?php echo $job['experience_level'] == 'mid' ? 'selected' : ''; ?>>Mid</option>
                            <option value="senior" <?php echo $job['experience_level'] == 'senior' ? 'selected' : ''; ?>>Senior</option>
                        </select>
                    </div>
                    <div>
                        <label>Deadline</label>
                        <input type="date" name="deadline" value="<?php echo htmlspecialchars($job['deadline']); ?>" required>
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="status">
                            <option value="active" <?php echo $job['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="draft" <?php echo $job['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="closed" <?php echo $job['status'] == 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="form-grid">
                    <div>
                        <label>Description</label>
                        <textarea name="description" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                    </div>
                    <div>
                        <label>Requirements</label>
                        <textarea name="requirements"><?php echo htmlspecialchars($job['requirements']); ?></textarea>
                    </div>
                    <div>
                        <label>Benefits</label>
                        <textarea name="benefits"><?php echo htmlspecialchars($job['benefits']); ?></textarea>
                    </div>
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Update Job</button>
                <a class="btn btn-light" href="jobs.php">Cancel</a>
            </form>
        </main>
    </div>
    <script src="../../assets/js/recruiter.js"></script>
</body>
</html>

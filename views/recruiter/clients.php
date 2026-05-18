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
$clients = $controller->getClients($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <h2>Recruiter</h2>
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="profile.php">Profile</a>
            <a class="nav-link active" href="clients.php">Clients</a>
            <a class="nav-link" href="jobs.php">Jobs</a>
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
                    <h1>Clients</h1>
                    <p class="muted">Registered employers or standalone companies</p>
                </div>
            </div>

            <form id="clientForm" class="form-card">
                <input type="hidden" name="employer_id" id="employer_id">

                <div class="section-header">
                    <div>
                        <h2>Add Client</h2>
                        <p class="muted">Search a registered employer or add a standalone company.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div>
                        <label>Search Registered Employer</label>
                        <input type="text" id="employerKeyword" placeholder="Type employer name, company or email">
                    </div>
                    <div>
                        <label>Selected Employer</label>
                        <input type="text" id="selectedEmployer" placeholder="No registered employer selected" readonly>
                    </div>
                    <div>
                        <label>Standalone Company Name</label>
                        <input type="text" name="company_name_override">
                    </div>
                </div>
                <br>
                <div class="actions">
                    <button class="btn btn-light" type="button" onclick="searchEmployers()">Search Employer</button>
                    <button class="btn btn-warning" type="button" onclick="clearSelectedEmployer()">Clear Employer</button>
                </div>
                <br>
                <div id="employerSearchResults" class="search-results"></div>
                <br>
                <button class="btn btn-primary" type="submit">Add Client</button>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Employer</th>
                            <th>Email</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clients as $client){ ?>
                            <tr>
                                <td><?php echo htmlspecialchars($client['company_name_override'] ?: ($client['company_name'] ?? '')); ?></td>
                                <td><?php echo htmlspecialchars($client['employer_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($client['employer_email'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($client['created_at']); ?></td>
                                <td>
                                    <button class="btn btn-danger" onclick="deleteClient(<?php echo $client['id']; ?>)">Delete</button>
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

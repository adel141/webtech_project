<!DOCTYPE html>
<html lang="en">
<head>
    <title>Recruiter | Jobs</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src="../../public/js/recruiter.js"></script>
</head>
<body>
    <div class="card" style="margin-bottom:14px">
        <div style="display:grid;grid-template-columns:160px 160px 160px;gap:10px">
            <select class="select" id="job-status" onchange="loadRecruiterJobs()">
                <option value="">All statuses</option><option value="active">Active</option><option value="closed">Closed</option><option value="draft">Draft</option>
            </select>
            <input class="input" id="job-category" oninput="loadRecruiterJobs()" placeholder="Category id">
            <input class="input" id="job-client" oninput="loadRecruiterJobs()" placeholder="Client employer id">
        </div>
    </div>
    <div class="card flush">
        <table class="tbl"><thead><tr><th>Title</th><th>Client</th><th>Category</th><th>Location</th><th>Status</th><th>Apps</th><th></th></tr></thead><tbody id="jobs-table-body"></tbody></table>
    </div>
</body>
<script>loadRecruiterJobs();</script>
</html>

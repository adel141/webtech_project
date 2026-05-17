<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Dashboard</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src ="../../public/js/recruiter.js"></script>
</head>
<body>



<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:16px">
    <div class="stat">
        <div class="k">Active jobs</div>
        <div class="v" id="activeJobs"></div>
    </div>
    <div class="stat">
        <div class="k">Clients</div>
        <div class="v" id="Clients"></div>
    </div>
    <div class="stat">
        <div class="k">Outreach sent</div>
        <div class="v" id="recruiter-count"></div>
    </div>
    <div class="stat">
        <div class="k">Responded</div>
        <div class="v" id="job-count"></div>
    </div>
</div>






</body>

<script> 
    loadDashboard();
</script>

</html>
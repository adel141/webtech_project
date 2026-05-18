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

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:16px">
  <div class="card flush">
    <div class="card-h">
      <h3>Recent outreach</h3>
      <a href="" class="btn ghost sm" style="margin-left:auto">View all</a>
    </div>
    <table class="tbl">
      <thead>
       <tr>
        <th>Candidate</th>
        <th>Job</th>
        <th>Sent</th>
        <th>Status</th>
       </tr>
      </thead>
      <tbody id="recent-outreach-body">
 
      </tbody>
    </table>
  </div>




</body>

<script> 
    loadDashboard();
    loadRecentOutreach();
</script>

</html>
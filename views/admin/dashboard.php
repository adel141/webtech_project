<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>

<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:16px">
    <div class="stat">
        <div class="k">Seekers</div>
        <div class="v" id="seeker-count"><?= $data[0]['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Employers</div>
        <div class="v" id="employer-count"><?= $data[1]['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Recruiters</div>
        <div class="v" id="recruiter-count"><?= $data[2]['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Active jobs</div>
        <div class="v" id="job-count"><?= $data['active_jobs']['cnt'] ?? 0 ?></div>
    </div>
    <div class="stat">
        <div class="k">Apps (30d)</div>
        <div class="v" id="app-count"><?= $data['recent_applications']['cnt'] ?? 0 ?></div>
        <div class="d up">recent</div>
    </div>
    <div class="stat">
        <div class="k">Open complaints</div>
        <div class="v" id="complaint-count"><?= $data['open_complaints']['cnt'] ?? 0 ?></div>
    </div>
</div>







</body>
<script>
    

    renderStats();
        // function renderStats(){
        //     let xtthp = new XMLHttpRequest();
        //     xtthp.onload = function(){
        //         if(this.readyState == 4 && this.status == 200){
        //             let data = JSON.parse(this.responseText);
        //             document.getElementById('seeker-count').innerText = data[0]?.cnt || 0;
        //             document.getElementById('employer-count').innerText = data[1]?.cnt || 0;
        //             document.getElementById('recruiter-count').innerText = data[2]?.cnt || 0;
        //             document.getElementById('job-count').innerText = data['active_jobs']?.cnt || 0;
        //             document.getElementById('app-count').innerText = data['recent_applications']?.cnt || 0;
        //             document.getElementById('complaint-count').innerText = data['open_complaints']?.cnt || 0;
        //         }
        //     }
        //     xtthp.open('GET', '../../ajax/admin/statusDashboard.php', true);
        //     xtthp.send();
        // }


</script>
</html>
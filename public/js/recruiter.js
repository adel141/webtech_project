function loadDashboard() {
    xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
            const data = JSON.parse(this.responseText);
            document.getElementById('activeJobs').innerText = data['active_jobs'];
            document.getElementById('Clients').innerText = data['clients'];
            document.getElementById('recruiter-count').innerText = data['outreach'];
            document.getElementById('responded').innerText = data['responded'];
        }
    };
    xhttp.open("GET", "../../ajax/recruiter/dashboardData.php", true);
    xhttp.send();
}

function loadRecentOutreach() {
    let xhttp = new XMLHttpRequest();       
    xhttp.onload = function() {
        if (this.status == 200) {
            const outreach = JSON.parse(this.responseText);
            const tbody = document.getElementById('recent-outreach-body');
            tbody.innerHTML = '';        
            if(outreach['COUNT(*)'] < 1){
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:32px;color:var(--muted)">No outreach made yet.</td></tr>';
                return;
            }
            let data='';
            outreach.forEach(function(item) {
                data += '<tr>';
                data += '<td>' + item.candidate_name + '</td>';
                data += '<td>' + item.job_title + '</td>';
                data += '<td>' + item.outreach_date + '</td>';
                data += '<td>' + item.status + '</td>';
                data += '</tr>';
            });
            tbody.innerHTML = data;
        }
        
    };
    xhttp.open("GET", "../../ajax/recruiter/recentOutreach.php", true);
    xhttp.send();
}   

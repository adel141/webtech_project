function loadDashboard() {
    xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
            const data = JSON.parse(this.responseText);
            document.getElementById('activeJobs').innerText = data['active_jobs'];
            document.getElementById('Clients').innerText = data['clients'];
        }
    };
    xhttp.open("GET", "../../ajax/recruiter/dashboardData.php", true);
    xhttp.send();
}
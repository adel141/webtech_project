function recruiterResponse(xhttp, successMessage, redirectUrl){
    if(xhttp.readyState == 4){
        if(xhttp.status != 200){
            alert("Request failed. Please try again.");
            return;
        }

        if(xhttp.responseText.trim() == ""){
            alert("No response from server");
            return;
        }

        let response;

        try{
            response = JSON.parse(xhttp.responseText);
        }catch(e){
            alert("Server returned an invalid response");
            return;
        }

        if(response.status == "success"){
            alert(successMessage);

            if(redirectUrl){
                window.location.href = redirectUrl;
            }else{
                location.reload();
            }
        }else{
            alert(response.message || "Something went wrong");
        }
    }
}

function escapeHtml(value){
    return String(value || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function deleteClient(client_id){
    if(!confirm("Delete this client?")){
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/recruiter/deleteClient.php?client_id=" + client_id, true);
    xhttp.onreadystatechange = function(){
        recruiterResponse(this, "Client deleted");
    }
    xhttp.send();
}

function deleteRecruiterJob(job_id){
    if(!confirm("Delete this job?")){
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/recruiter/deleteJob.php?job_id=" + job_id, true);
    xhttp.onreadystatechange = function(){
        recruiterResponse(this, "Job deleted");
    }
    xhttp.send();
}

function closeRecruiterJob(job_id){
    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/recruiter/closeJob.php?job_id=" + job_id, true);
    xhttp.onreadystatechange = function(){
        recruiterResponse(this, "Job closed");
    }
    xhttp.send();
}

function updateApplicationStatus(application_id, status){
    let formData = new FormData();
    formData.append("application_id", application_id);
    formData.append("status", status);

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../ajax/recruiter/updateApplicationStatus.php", true);
    xhttp.onreadystatechange = function(){
        recruiterResponse(this, "Application updated");
    }
    xhttp.send(formData);
}

function searchSeekers(){
    let keyword = document.getElementById("keyword").value;
    let location = document.getElementById("location").value;
    let experience = document.getElementById("experience").value;
    let salary = document.getElementById("salary").value;
    let url = "../../ajax/recruiter/searchSeekers.php?keyword=" + encodeURIComponent(keyword)
        + "&location=" + encodeURIComponent(location)
        + "&experience=" + encodeURIComponent(experience)
        + "&salary=" + encodeURIComponent(salary);

    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", url, true);

    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            let seekers;

            try{
                seekers = JSON.parse(this.responseText);
            }catch(e){
                document.getElementById("searchResults").innerHTML = '<div class="card">Server returned an invalid response.</div>';
                return;
            }

            let html = "";

            for(let i = 0; i < seekers.length; i++){
                html += '<div class="candidate-card">';
                html += '<h3>' + escapeHtml(seekers[i].name) + '</h3>';
                html += '<p class="muted">' + escapeHtml(seekers[i].email) + '</p>';
                html += '<p>' + escapeHtml(seekers[i].headline) + '</p>';
                html += '<p><strong>Skills:</strong> ' + escapeHtml(seekers[i].skills) + '</p>';
                html += '<p><strong>Experience:</strong> ' + escapeHtml(seekers[i].experience) + ' years</p>';
                html += '<p><strong>Expected Salary:</strong> ' + escapeHtml(seekers[i].expected_salary) + '</p>';
                html += '<p><strong>Location:</strong> ' + escapeHtml(seekers[i].preferred_location) + '</p>';
                html += '</div>';
            }

            if(html == ""){
                html = '<div class="card">No seekers found.</div>';
            }

            document.getElementById("searchResults").innerHTML = html;
        }
    }

    xhttp.send();
}

function searchEmployers(){
    let keyword = document.getElementById("employerKeyword").value;

    if(keyword.trim() == ""){
        alert("Enter employer name, company or email");
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/recruiter/searchEmployers.php?keyword=" + encodeURIComponent(keyword), true);

    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            let employers;

            try{
                employers = JSON.parse(this.responseText);
            }catch(e){
                document.getElementById("employerSearchResults").innerHTML = '<div class="card">Server returned an invalid response.</div>';
                return;
            }

            let html = "";

            for(let i = 0; i < employers.length; i++){
                let company = employers[i].company_name || "";
                let label = employers[i].name;

                if(company != ""){
                    label += " - " + company;
                }

                html += '<div class="candidate-card">';
                html += '<h3>' + escapeHtml(employers[i].name) + '</h3>';
                html += '<p class="muted">' + escapeHtml(employers[i].email) + '</p>';
                html += '<p><strong>Company:</strong> ' + escapeHtml(company) + '</p>';
                html += '<p><strong>Industry:</strong> ' + escapeHtml(employers[i].industry) + '</p>';
                html += '<button class="btn btn-primary" type="button" onclick="selectEmployer(' + employers[i].id + ', \'' + escapeHtml(label) + '\')">Use Employer</button>';
                html += '</div>';
            }

            if(html == ""){
                html = '<div class="card">No employer found. You can enter a standalone company name instead.</div>';
            }

            document.getElementById("employerSearchResults").innerHTML = html;
        }
    }

    xhttp.send();
}

function selectEmployer(employer_id, label){
    document.getElementById("employer_id").value = employer_id;
    document.getElementById("selectedEmployer").value = label;
    document.getElementById("employerSearchResults").innerHTML = "";
}

function clearSelectedEmployer(){
    document.getElementById("employer_id").value = "";
    document.getElementById("selectedEmployer").value = "";
}

document.addEventListener("DOMContentLoaded", function(){
    let profileForm = document.getElementById("profileForm");

    if(profileForm){
        profileForm.addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", "../../ajax/recruiter/updateProfile.php", true);
            xhttp.onreadystatechange = function(){
                recruiterResponse(this, "Profile updated");
            }
            xhttp.send(formData);
        });
    }

    let clientForm = document.getElementById("clientForm");

    if(clientForm){
        clientForm.addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", "../../ajax/recruiter/addClient.php", true);
            xhttp.onreadystatechange = function(){
                recruiterResponse(this, "Client added");
            }
            xhttp.send(formData);
        });
    }

    let addJobForm = document.getElementById("addJobForm");

    if(addJobForm){
        addJobForm.addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", "../../ajax/recruiter/addJob.php", true);
            xhttp.onreadystatechange = function(){
                recruiterResponse(this, "Job added", "jobs.php");
            }
            xhttp.send(formData);
        });
    }

    let editJobForm = document.getElementById("editJobForm");

    if(editJobForm){
        editJobForm.addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let job_id = document.getElementById("job_id").value;
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", "../../ajax/recruiter/updateJob.php?job_id=" + job_id, true);
            xhttp.onreadystatechange = function(){
                recruiterResponse(this, "Job updated", "jobs.php");
            }
            xhttp.send(formData);
        });
    }

    let outreachForm = document.getElementById("outreachForm");

    if(outreachForm){
        outreachForm.addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", "../../ajax/recruiter/sendOutreach.php", true);
            xhttp.onreadystatechange = function(){
                recruiterResponse(this, "Outreach sent");
            }
            xhttp.send(formData);
        });
    }
});

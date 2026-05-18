function adminResponse(xhttp, successMessage){
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
            location.reload();
        }else{
            alert(response.message || "Something went wrong");
        }
    }
}

function approveUser(user_id){
    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/admin/approveUser.php?user_id=" + user_id, true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "User approved");
    }
    xhttp.send();
}

function rejectUser(user_id){
    if(!confirm("Reject this user?")){
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/admin/rejectUser.php?user_id=" + user_id, true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "User rejected");
    }
    xhttp.send();
}

function toggleUserStatus(user_id, status){
    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/admin/toggleUserStatus.php?user_id=" + user_id + "&status=" + status, true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "User status updated");
    }
    xhttp.send();
}

function deleteCategory(category_id){
    if(!confirm("Delete this category?")){
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/admin/deleteCategory.php?category_id=" + category_id, true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "Category deleted");
    }
    xhttp.send();
}

function updateCategory(category_id){
    let name = prompt("Category name");

    if(name == null || name.trim() == ""){
        return;
    }

    let description = prompt("Description");
    let formData = new FormData();
    formData.append("id", category_id);
    formData.append("name", name);
    formData.append("description", description || "");

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../ajax/admin/updateCategory.php", true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "Category updated");
    }
    xhttp.send(formData);
}

function deleteJob(job_id){
    if(!confirm("Delete this job?")){
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/admin/deleteJob.php?job_id=" + job_id, true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "Job deleted");
    }
    xhttp.send();
}

function toggleFeatured(job_id, featured){
    let xhttp = new XMLHttpRequest();
    xhttp.open("GET", "../../ajax/admin/toggleFeatured.php?job_id=" + job_id + "&featured=" + featured, true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "Featured status updated");
    }
    xhttp.send();
}

function resolveComplaint(complaint_id){
    let note = prompt("Admin note");

    if(note == null || note.trim() == ""){
        return;
    }

    let formData = new FormData();
    formData.append("admin_note", note);

    let xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../../ajax/admin/resolveComplaint.php?complaint_id=" + complaint_id, true);
    xhttp.onreadystatechange = function(){
        adminResponse(this, "Complaint resolved");
    }
    xhttp.send(formData);
}

document.addEventListener("DOMContentLoaded", function(){
    let categoryForm = document.getElementById("categoryForm");

    if(categoryForm){
        categoryForm.addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", "../../ajax/admin/addCategory.php", true);
            xhttp.onreadystatechange = function(){
                adminResponse(this, "Category added");
            }
            xhttp.send(formData);
        });
    }
});

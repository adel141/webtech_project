
    function approveUser(userId) {
        event.preventDefault();
        if(!confirm("Are you sure?")) return;

        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                let response = JSON.parse(this.responseText);
                if(response.status === 'success') {
                    alert('Employer approved successfully.');
                } else {
                    alert('Failed to approve employer.');
                }
                location.reload();
                
            }
        }
        xhr.open("GET", "../../ajax/admin/approvePendingUser.php?user_id=" + userId, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send();


        
    }

        
    function toggleUserStatus(userId, status) {
        event.preventDefault();
        if(!confirm("Are you sure?")) return;
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                let response = JSON.parse(this.responseText);
                if(response.status === 'success') {
                    alert('User status updated successfully.');
                } else {
                    alert('Failed to update user status.');
                }
                location.reload();
            }
        }
        xhr.open("POST", "../../ajax/admin/toggleUserStatus.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("user_id=" + userId + "&status=" + status);
    }


    function loadUserByRole($role){
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const users = JSON.parse(this.responseText);
                const tbody = document.getElementById('user-table-body');
                tbody.innerHTML = '';
                if(users.length < 1){
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--muted)">No Employers registered.</td></tr>';
                    return;
                }
                let data='';
                users.forEach( function(user){
                    data += `<tr>
                        <td style="font-weight:500">${user.name}</td>
                        <td class="muted" style="font-size:12px">${user.email}</td>
                        <td class="muted" style="font-size:12px">${user.phone}</td>
                        <td class="num muted">${new Date(user.created_at).toLocaleDateString()}</td>
                        <td id="UserStatus">
                            ${user.is_active == 1 ?( (user.is_verified == 1? `<span class="pill ok">Active</span>` : `<span class="pill warn">Pending</span>`)) : `<span class="pill bad">Inactive</span>` }
                        </td>
                        <td>
                            <form method="GET" style="display:inline">
                                <input type="hidden" name="user_id" value="${user.id}">
                                ${user.is_verified == 0 ? `<button class="btn sm accent " onclick = "approveUser(${user.id})">Approve</button>` : (user.is_active == 1 ? `<button class="btn sm " onclick = "toggleUserStatus(${user.id}, 0)">Deactivate</button>` : `<button class="btn sm accent" onclick = "toggleUserStatus(${user.id}, 1)">Activate</button>`)}
                            </form>
                        </td>
                    </tr>`;
                });
                tbody.innerHTML = data;
            }
        };
        xhttp.open("POST", "../../ajax/admin/fetchUserByRole.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("role=" + $role);
    }

    function loadCategories(){
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.responseText);
                
                let output='';
                
                const tbody = document.getElementById('category-table-data');
                if(data.length < 1){
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--muted)">No Employers registered.</td></tr>';
                    return;
                }
                document.getElementById('categories-count').innerHTML= data['total'];
                tbody.innerHTML = '';

                
            
                data['name'].forEach( function(category){
                output += `<tr>
                <td style="font-weight:500">${category.name}</td>
                <td>${category.category_name}</td>
                <td>${category.job_count}</td>
                </tr>
                    `
                });
                tbody.innerHTML = output;
                    
                
            }
        };
        xhttp.open("GET", "../../ajax/admin/loadCatagories.php", true);
        xhttp.send();
    }

        
    // renderStats();
    //     function renderStats(){
    //         let xtthp = new XMLHttpRequest();
    //         xtthp.onload = function(){
    //             if(this.readyState == 4 && this.status == 200){
    //                 let data = JSON.parse(this.responseText);
    //                 document.getElementById('seeker-count').innerText = data[0]?.cnt || 0;
    //                 document.getElementById('employer-count').innerText = data[1]?.cnt || 0;
    //                 document.getElementById('recruiter-count').innerText = data[2]?.cnt || 0;
    //                 document.getElementById('job-count').innerText = data['active_jobs']?.cnt || 0;
    //                 document.getElementById('app-count').innerText = data['recent_applications']?.cnt || 0;
    //                 document.getElementById('complaint-count').innerText = data['open_complaints']?.cnt || 0;
    //             }
    //         }
    //         xtthp.open('GET', '../../ajax/admin/statusDashboard.php', true);
    //         xtthp.send();
    //     }


<?php
session_start();

$basePath = "";

if(strpos($_SERVER['SCRIPT_NAME'], "/views/auth/") !== false){
    $basePath = "../../";
}

function roleDashboardPath($role){
    $paths = [
        'admin' => 'views/admin/dashboard.php',
        'recruiter' => 'views/recruiter/dashboard.php',
        'employer' => 'public/index.php/employer/dashboard',
        'seeker' => 'public/index.php/seeker/dashboard'
    ];

    return $paths[$role] ?? 'login.php';
}

if(isset($_SESSION['user_id'])){
    $role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';
    if($role != ''){
        header("Location: " . $basePath . roleDashboardPath($role));
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal Register</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-card auth-card-wide">
        <div class="brand-mark">JP</div>
        <h1>Create Account</h1>
        <p class="muted">Register as a job seeker, employer or recruiter</p>

        <form id="registerForm" class="form-stack">
            <div>
                <label>Account Type</label>
                <select name="role" id="role">
                    <option value="seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                    <option value="recruiter">Recruiter</option>
                </select>
            </div>

            <div>
                <label>Name</label>
                <input type="text" name="name" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div>
                <label>Phone</label>
                <input type="text" name="phone">
            </div>

            <div class="role-fields active" data-role-fields="seeker">
                <div>
                    <label>Headline</label>
                    <input type="text" name="headline" placeholder="Frontend Developer">
                </div>

                <div>
                    <label>Skills</label>
                    <input type="text" name="skills" placeholder="PHP, MySQL, JavaScript">
                </div>
            </div>

            <div class="role-fields" data-role-fields="employer">
                <div>
                    <label>Company Name</label>
                    <input type="text" name="company_name" data-required-for="employer">
                </div>

                <div>
                    <label>Industry</label>
                    <input type="text" name="industry" placeholder="Software, Finance, Retail">
                </div>

                <div>
                    <label>Company Size</label>
                    <select name="company_size">
                        <option value="">Select size</option>
                        <option value="1-10">1-10</option>
                        <option value="11-50">11-50</option>
                        <option value="51-200">51-200</option>
                        <option value="201-500">201-500</option>
                        <option value="500+">500+</option>
                    </select>
                </div>
            </div>

            <div class="role-fields" data-role-fields="recruiter">
                <div>
                    <label>Agency Name</label>
                    <input type="text" name="agency_name" data-required-for="recruiter">
                </div>

                <div>
                    <label>Specialization</label>
                    <input type="text" name="specialization" placeholder="Technology hiring">
                </div>
            </div>

            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <div>
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>

            <div id="registerMessage" class="message"></div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p class="auth-link">Already have an account? <a href="<?php echo $basePath; ?>login.php">Login</a></p>
    </div>

    <script>
        let basePath = "<?php echo $basePath; ?>";
        let roleSelect = document.getElementById("role");

        function syncRoleFields(){
            let role = roleSelect.value;
            let groups = document.querySelectorAll("[data-role-fields]");
            let requiredInputs = document.querySelectorAll("[data-required-for]");

            groups.forEach(function(group){
                group.classList.toggle("active", group.getAttribute("data-role-fields") == role);
            });

            requiredInputs.forEach(function(input){
                input.required = input.getAttribute("data-required-for") == role;
            });
        }

        roleSelect.addEventListener("change", syncRoleFields);
        syncRoleFields();

        document.getElementById("registerForm").addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", basePath + "ajax/auth/register.php", true);

            xhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    let message = document.getElementById("registerMessage");
                    let response;

                    try{
                        response = JSON.parse(this.responseText);
                    }catch(error){
                        message.innerHTML = "Server returned an invalid response";
                        message.className = "message message-error";
                        return;
                    }

                    if(response.status == "success"){
                        message.innerHTML = response.message;
                        message.className = "message message-success";
                        document.getElementById("registerForm").reset();

                        setTimeout(function(){
                            window.location.href = basePath + "login.php";
                        }, 1500);
                    }else{
                        message.innerHTML = response.message;
                        message.className = "message message-error";
                    }
                }
            }

            xhttp.send(formData);
        });
    </script>
</body>
</html>

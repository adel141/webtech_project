<?php
session_start();

$basePath = "";

if(strpos($_SERVER['SCRIPT_NAME'], "/views/auth/") !== false){
    $basePath = "../../";
}

if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: " . $basePath . "views/admin/dashboard.php");
        exit;
    }

    if($_SESSION['role'] == 'recruiter'){
        header("Location: " . $basePath . "views/recruiter/dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Register</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="brand-mark">JP</div>
        <h1>Create Account</h1>
        <p class="muted">Register as a recruiter</p>

        <form id="registerForm" class="form-stack">
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

            <div>
                <label>Agency Name</label>
                <input type="text" name="agency_name" required>
            </div>

            <div>
                <label>Specialization</label>
                <input type="text" name="specialization">
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

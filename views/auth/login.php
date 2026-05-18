<?php
session_start();

if(isset($_COOKIE['jp_remember_id'])){
    setcookie("jp_remember_id", "", time() - 3600, "/");
}

if(isset($_COOKIE['jp_remember_token'])){
    setcookie("jp_remember_token", "", time() - 3600, "/");
}

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

$registeredEmail = "";
$registeredName = "";
$registerNotice = "";

if(isset($_COOKIE['jp_registered_email'])){
    $registeredEmail = $_COOKIE['jp_registered_email'];
}

if(isset($_COOKIE['jp_registered_name'])){
    $registeredName = $_COOKIE['jp_registered_name'];
}

if(isset($_COOKIE['jp_register_success'])){
    $registeredRole = $_COOKIE['jp_registered_role'] ?? '';
    $registerNotice = "Registration successful. You can login now.";

    if(in_array($registeredRole, ['employer', 'recruiter'])){
        $registerNotice = "Registration successful. Please wait for admin approval.";
    }

    setcookie("jp_register_success", "", time() - 3600, "/");
    setcookie("jp_registered_role", "", time() - 3600, "/");
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
    <title>Job Portal Login</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="brand-mark">JP</div>
        <h1>Job Portal</h1>
        <p class="muted">
            <?php if($registeredName != ""){ ?>
                Welcome, <?php echo htmlspecialchars($registeredName); ?>
            <?php }else{ ?>
                Access for seekers, employers, recruiters and admins
            <?php } ?>
        </p>

        <form id="loginForm" class="form-stack">
            <div>
                <label>Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($registeredEmail); ?>" required>
            </div>

            <div>
                <label>Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div id="loginMessage" class="message <?php echo $registerNotice != "" ? 'message-success' : ''; ?>">
                <?php echo htmlspecialchars($registerNotice); ?>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p class="auth-link">Need an account? <a href="<?php echo $basePath; ?>register.php">Register</a></p>
    </div>

    <script>
        let basePath = "<?php echo $basePath; ?>";

        document.getElementById("loginForm").addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);
            let xhttp = new XMLHttpRequest();

            xhttp.open("POST", basePath + "ajax/auth/login.php", true);

            xhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    let message = document.getElementById("loginMessage");
                    let response;

                    try{
                        response = JSON.parse(this.responseText);
                    }catch(error){
                        message.innerHTML = "Server returned an invalid response";
                        message.className = "message message-error";
                        return;
                    }

                    if(response.status == "success"){
                        window.location.href = basePath + (response.redirect || "index.php");
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

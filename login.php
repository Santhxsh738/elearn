<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 1. Instructor Check
    $inst_q = mysqli_query($conn, "SELECT * FROM instructor_applications WHERE email='$email' AND status='Approved'");
    $inst_r = mysqli_fetch_assoc($inst_q);

    if ($inst_r) {
        $_SESSION['username'] = $inst_r['first_name'];
        $_SESSION['user_email'] = $inst_r['email'];
        $_SESSION['role'] = 'instructor';
        header("Location: instructor_dashboard.php");
        exit();
    } 

    // 2. Admin/Student Check
    $user_q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user_r = mysqli_fetch_assoc($user_q);

    if ($user_r) {
        if (password_verify($password, $user_r['password']) || $password == $user_r['password']) {
            $_SESSION['username'] = $user_r['username'];
            $_SESSION['user_email'] = $user_r['email'];
            $_SESSION['role'] = $user_r['role'];

            if ($user_r['role'] == 'admin') {
                header("Location: admin_home.php");
            } else {
                header("Location: home.php");
            }
            exit();
        }
    }
    $msg = "Invalid Login Details!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="img/icon.png" rel="icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700;800;900&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 50px 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        /* Top Decoration Bar */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, #fb873f, #e73c7e);
        }

        .brand-title {
            font-family: 'Orbitron', sans-serif; /* FONT CHANGED HERE */
            font-weight: 800; /* Bold weight */
            color: #333;
            letter-spacing: 1px; /* Konjam gap vittu irukkum */
            font-size: 30px; 
            margin-bottom: 5px;
            text-transform: uppercase; /* Ellam capital letters */
        }

        .form-control {
            background: #f0f2f5;
            border: none;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 15px;
            color: #333;
        }

        .form-control:focus {
            background: #fff;
            box-shadow: 0 0 0 3px rgba(251, 135, 63, 0.15);
            color: #333;
            outline: none;
        }

        .btn-login {
            background: #fb873f;
            background: linear-gradient(90deg, #fb873f, #ff6b6b);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(251, 135, 63, 0.4);
            color: white;
        }

        .register-link {
            color: #333;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }
        
        .register-link:hover {
            color: #fb873f;
        }
    </style>
</head>

<body>
    
    <div class="login-card">
        <div class="text-center mb-5">
            <h2 class="brand-title">Secret<span style="color: #fb873f;">Coder</span></h2>
            <p class="text-muted" style="font-size: 14px;">Welcome Back!</p>
        </div>

        <?php if(isset($msg)) { ?>
            <div class="alert alert-danger py-2 text-center small rounded-3 border-0 bg-danger text-white mb-4">
                <i class="fa fa-circle-exclamation me-2"></i> <?php echo $msg; ?>
            </div>
        <?php } ?>

        <form method="post">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Remember me</label>
                </div>
                <a href="#" class="small text-decoration-none" style="color: #fb873f;">Forgot Password?</a>
            </div>

            <button name="login" class="btn btn-login">SIGN IN</button>

            <div class="text-center mt-4">
                <p class="small text-muted mb-0">Don't have an account? <a href="register.php" class="register-link">Register Here</a></p>
            </div>
        </form>
    </div>

</body>
</html>
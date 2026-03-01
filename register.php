<?php
include 'db.php';
$msg = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    
    if(mysqli_num_rows($check_email) > 0){
        $msg = "Email Address already exists!";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration Successful! Please Login.'); window.location.href='login.php';</script>";
        } else {
            $msg = "Something went wrong. Try again!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register | Secret Coder</title>
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
            padding: 40px 40px;
            width: 100%;
            max-width: 450px;
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
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
            color: #333;
            letter-spacing: 1px;
            font-size: 30px;
            margin-bottom: 5px;
            text-transform: uppercase;
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

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(251, 135, 63, 0.4);
            color: white;
        }

        .login-link {
            color: #333;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }
        
        .login-link:hover {
            color: #fb873f;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <h2 class="brand-title">Secret<span style="color: #fb873f;">Coder</span></h2>
            <p class="text-muted" style="font-size: 14px;">Create your account</p>
        </div>

        <?php if($msg != "") { ?>
            <div class="alert alert-danger py-2 text-center small rounded-3 border-0 bg-danger text-white mb-4">
                <i class="fa fa-circle-exclamation me-2"></i> <?php echo $msg; ?>
            </div>
        <?php } ?>

        <form method="post">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>

            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button name="register" class="btn btn-register">REGISTER</button>

            <div class="text-center mt-4">
                <p class="small text-muted mb-0">Already have an account? <a href="login.php" class="login-link">Login Here</a></p>
            </div>
        </form>
    </div>

</body>
</html>
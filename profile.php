<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$email = $_SESSION['user_email'];

// Profile Photo Upload Logic
if (isset($_POST['upload'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir);
    $file_name = time() . "_" . basename($_FILES["profile_img"]["name"]);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
        mysqli_query($conn, "UPDATE users SET profile_pic = '$file_name' WHERE email = '$email'");
        echo "<script>window.location.href='profile.php';</script>";
    }
}

// User Data Fetch
$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT profile_pic FROM users WHERE email = '$email'"));
$profile_pic = !empty($user_data['profile_pic']) ? "uploads/" . $user_data['profile_pic'] : "https://ui-avatars.com/api/?name=$username&background=random&size=128";

// FIXED TABLE NAME: course_enrollments
$enroll_result = mysqli_query($conn, "SELECT course_name, enroll_date FROM course_enrollments WHERE user_email = '$email' ORDER BY enroll_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $username; ?> | Profile</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/icon.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        
        body { 
            background-color: #f4f7f6; 
            font-family: 'Poppins', sans-serif; 
            color: #333;
            margin: 0;
        }

        /* --- GRADIENT SIDEBAR --- */
        .sidebar { 
            width: 280px; 
            height: 100vh; 
            background: #fff; 
            border-right: 1px solid #eee; 
            position: fixed; 
            padding: 30px 20px; 
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 15px rgba(0,0,0,0.02);
        }
        
        .nav-link {
            padding: 12px 20px;
            border-radius: 12px;
            color: #555;
            font-weight: 600;
            margin-bottom: 8px;
            transition: 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background: linear-gradient(90deg, rgba(251, 135, 63, 0.1), rgba(231, 60, 126, 0.1));
            color: #e73c7e !important;
        }

        .main-content { margin-left: 280px; padding: 40px; }
        
        /* Profile Hero Card with Gradient Animation */
        .hero-banner { 
            background: #fff;
            border-radius: 25px; 
            padding: 40px; 
            display: flex; 
            align-items: center; 
            gap: 30px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 5px;
            background: linear-gradient(90deg, #fb873f, #e73c7e);
        }
        
        .profile-container { position: relative; }
        .user-img { width: 120px; height: 120px; border-radius: 30px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .edit-icon { position: absolute; bottom: -5px; right: -5px; background: #e73c7e; color: #fff; width: 35px; height: 35px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff; transition: 0.3s; }
        .edit-icon:hover { transform: scale(1.1); background: #fb873f; }
        
        .name-display { font-weight: 800; font-size: 32px; color: #333; letter-spacing: -1px; }
        .email-display { color: #888; font-weight: 500; font-size: 16px; }
        
        /* Course Rows */
        .section-header { font-weight: 700; font-size: 22px; margin-bottom: 25px; color: #333; }
        
        .course-row { 
            background: #fff; 
            border-radius: 20px; 
            padding: 20px; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            transition: 0.3s;
            border: 1px solid transparent;
        }
        
        .course-row:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-color: rgba(231, 60, 126, 0.2);
        }
        
        .icon-box { width: 50px; height: 50px; background: rgba(251, 135, 63, 0.1); color: #fb873f; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        
        .btn-action { 
            background: linear-gradient(90deg, #fb873f, #e73c7e);
            color: #fff; 
            border-radius: 50px; 
            padding: 10px 25px; 
            font-weight: 600; 
            text-decoration: none; 
            font-size: 13px;
            transition: 0.3s;
            border: none;
        }
        .btn-action:hover { color: #fff; transform: scale(1.05); box-shadow: 0 5px 15px rgba(231, 60, 126, 0.3); }
        
        .logout-link { margin-top: auto; color: #ff4d4d; font-weight: 700; text-decoration: none; padding: 12px; border-radius: 12px; background: #fff1f1; text-align: center; transition: 0.3s; }
        .logout-link:hover { background: #ffe4e4; color: #ff0000; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="mb-5 px-3">
        <h4 class="fw-bold" style="color:#333;">Secret<span style="color:#e73c7e;">Coder</span></h4>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link" href="home.php"><i class="fa fa-home me-3"></i> Home</a>
        <a class="nav-link active" href="profile.php"><i class="fa fa-user me-3"></i> Profile</a>
        <a class="nav-link" href="courses.php"><i class="fa fa-graduation-cap me-3"></i> My Courses</a>
        <a class="nav-link" href="student_doubts.php"><i class="fa fa-comment-dots me-3"></i> Doubts</a>
        <a class="nav-link" href="contact.php"><i class="fa fa-headset me-3"></i> Help</a>
    </nav>
    <a href="logout.php" class="logout-link"><i class="fa fa-power-off me-2"></i> Log Out</a>
</div>

<div class="main-content">
    <div class="hero-banner">
        <div class="profile-container">
            <img src="<?php echo $profile_pic; ?>" class="user-img">
            <form action="" method="POST" enctype="multipart/form-data" id="photoForm">
                <label for="img_input" class="edit-icon"><i class="fa fa-camera"></i></label>
                <input type="file" name="profile_img" id="img_input" hidden onchange="document.getElementById('photoForm').submit();">
                <input type="hidden" name="upload" value="1">
            </form>
        </div>
        <div>
            <div class="name-display"><?php echo $username; ?></div>
            <div class="email-display"><i class="fa-regular fa-envelope me-2"></i><?php echo $email; ?></div>
            <span class="badge bg-success mt-2 rounded-pill px-3 py-2" style="font-size: 11px;">Active Student</span>
        </div>
    </div>

    <div class="section-header">My Learning Journey</div>
    
    <?php if(mysqli_num_rows($enroll_result) > 0) { 
        while($row = mysqli_fetch_assoc($enroll_result)) { 
            $c_name = $row['course_name'];
            // Logic for link
            $link = ($c_name == "Full Stack Web Development") ? "fullstack_details.php" : "courses.php";
        ?>
        <div class="course-row shadow-sm">
            <div class="d-flex align-items-center gap-4">
                <div class="icon-box"><i class="fa-solid fa-play-circle"></i></div>
                <div>
                    <div class="fw-bold" style="font-size:17px; color:#333;"><?php echo $c_name; ?></div>
                    <small class="text-muted">Enrolled on <?php echo date('d M, Y', strtotime($row['enroll_date'])); ?></small>
                </div>
            </div>
            <a href="<?php echo $link; ?>" class="btn-action">Continue <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
    <?php } } else { ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-box-4085812-3385481.png" width="150" class="opacity-50">
            <h5 class="mt-3 text-muted">You haven't enrolled in any courses yet.</h5>
            <a href="courses.php" class="btn-action d-inline-block mt-3">Browse Courses</a>
        </div>
    <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
session_start();
include 'db.php';

// Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// 1. Fetching Students Only (Assuming role is 'student' or 'user')
$student_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role = 'student' OR role = 'user'"));

// 2. Fetching Instructors Only
$active_instructors = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM instructor_applications WHERE status='Approved'"));
$pending_instructors = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM instructor_applications WHERE status='Pending'"));

// 3. Other Counts
$msg_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM contact_messages"));
$enroll_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM course_enrollments"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard | SecretCoder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --primary-gradient: linear-gradient(135deg, #fb873f 0%, #e73c7e 100%);
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #fff;
            position: fixed;
            padding: 30px 20px;
            box-shadow: 5px 0 15px rgba(0,0,0,0.02);
            z-index: 1000;
        }

        .brand-logo {
            font-size: 24px;
            font-weight: 800;
            color: #333;
            margin-bottom: 40px;
            padding: 0 10px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #555;
            text-decoration: none;
            font-weight: 600;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .nav-item i { width: 25px; font-size: 18px; }

        .nav-item:hover, .nav-item.active {
            background: linear-gradient(90deg, rgba(251, 135, 63, 0.1), rgba(231, 60, 126, 0.1));
            color: #e73c7e;
        }

        .main-content { margin-left: var(--sidebar-width); padding: 40px; }

        /* Welcome Banner */
        .welcome-banner {
            background: var(--primary-gradient);
            border-radius: 25px;
            padding: 40px;
            color: white;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(231, 60, 126, 0.2);
        }

        /* Stat Cards */
        .stat-card {
            background: #fff;
            border-radius: 20px;
            padding: 25px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            transition: 0.3s;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.08); }

        .stat-card h3 { font-weight: 800; font-size: 32px; margin: 10px 0 5px; color: #333; }
        .stat-card p { color: #888; font-size: 13px; font-weight: 600; text-transform: uppercase; margin: 0; }
        
        .card-icon {
            width: 50px; height: 50px;
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            margin-bottom: 15px;
        }

        /* Action Buttons */
        .action-card {
            background: #fff;
            padding: 20px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: #333;
            border: 1px solid transparent;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            height: 100%;
        }

        .action-card:hover {
            border-color: #e73c7e;
            transform: translateX(5px);
            color: #e73c7e;
            box-shadow: 0 10px 20px rgba(231, 60, 126, 0.1);
        }

        .action-icon {
            width: 45px; height: 45px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: #f8f9fa;
            font-size: 18px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="brand-logo">
            Secret<span style="color:#e73c7e;">Coder</span>
        </div>
        <nav>
            <a href="admin_home.php" class="nav-item active"><i class="fa-solid fa-grid-2"></i> Overview</a>
            
            <a href="manage_users.php" class="nav-item"><i class="fa-solid fa-user-graduate"></i> Students</a>
            <a href="manage_instructors.php" class="nav-item"><i class="fa-solid fa-chalkboard-teacher"></i> Instructors</a>
            
            <a href="admin_enrollments.php" class="nav-item"><i class="fa-solid fa-graduation-cap"></i> Enrollments</a>
            <a href="view_messages.php" class="nav-item"><i class="fa-solid fa-envelope"></i> Messages</a>
            <hr>
            <a href="logout.php" class="nav-item text-danger"><i class="fa-solid fa-power-off"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        
        <div class="welcome-banner">
            <h2 class="fw-bold m-0">Admin Panel Control Center 🛡️</h2>
            <p class="m-0 opacity-75 mt-2">Welcome back, <b><?php echo $_SESSION['username']; ?></b>. Manage your students and instructors with ease.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="card-icon" style="background: rgba(35, 166, 213, 0.1); color: #23a6d5;"><i class="fa fa-user-graduate"></i></div>
                    <p>Total Students</p>
                    <h3><?php echo $student_count; ?></h3>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="card-icon" style="background: rgba(35, 213, 171, 0.1); color: #23d5ab;"><i class="fa fa-chalkboard-teacher"></i></div>
                    <p>Active Instructors</p>
                    <h3><?php echo $active_instructors; ?></h3>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="card-icon" style="background: rgba(251, 135, 63, 0.1); color: #fb873f;"><i class="fa fa-user-clock"></i></div>
                    <p>Pending Requests</p>
                    <h3><?php echo $pending_instructors; ?></h3>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="card-icon" style="background: rgba(231, 60, 126, 0.1); color: #e73c7e;"><i class="fa fa-book-reader"></i></div>
                    <p>Course Enrollments</p>
                    <h3><?php echo $enroll_count; ?></h3>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-4">Quick Management</h5>
        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <a href="manage_users.php" class="action-card">
                    <div class="action-icon" style="color: #23a6d5;"><i class="fa fa-users"></i></div>
                    <div class="fw-bold">View Students</div>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <a href="manage_instructors.php" class="action-card">
                    <div class="action-icon" style="color: #23d5ab;"><i class="fa fa-chalkboard-teacher"></i></div>
                    <div class="fw-bold">View Instructors</div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="admin_instructor_applications.php" class="action-card">
                    <div class="action-icon" style="color: #fb873f;"><i class="fa fa-file-signature"></i></div>
                    <div class="fw-bold">Instructor Requests <span class="badge bg-warning text-dark ms-2"><?php echo $pending_instructors; ?></span></div>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <a href="admin_enrollments.php" class="action-card">
                    <div class="action-icon" style="color: #e73c7e;"><i class="fa fa-database"></i></div>
                    <div class="fw-bold">Track Enrollments</div>
                </a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
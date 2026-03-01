<?php
session_start();
include 'db.php'; 

// 1. Session Check
if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}

// 2. Role Redirects
if($_SESSION['role'] == 'instructor'){
    header("Location: instructor_dashboard.php");
    exit();
}
if($_SESSION['role'] == 'admin'){
    header("Location: admin_home.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Secret Coder | Master Tech Skills</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }

        /* --- GRADIENT ANIMATION --- */
        .gradient-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: white;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Navbar */
        .navbar { background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 15px 0; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: #e73c7e !important; }
        
        .btn-gradient {
            background: linear-gradient(90deg, #fb873f, #e73c7e);
            color: white; border: none; padding: 10px 25px; border-radius: 50px; font-weight: 600; transition: 0.3s;
        }
        .btn-gradient:hover { transform: translateY(-3px); color: white; box-shadow: 0 10px 20px rgba(231, 60, 126, 0.3); }

        /* Hero Section */
        .hero-header { padding: 100px 0; position: relative; overflow: hidden; }
        .hero-header::after {
            content: ''; position: absolute; bottom: -50px; left: 0; width: 100%; height: 100px;
            background: #f4f7f6; transform: skewY(-3deg);
        }

        /* Cards */
        .course-card {
            border: none; border-radius: 20px; overflow: hidden; background: #fff;
            transition: 0.4s; position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .course-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .course-thumb { height: 200px; object-fit: cover; width: 100%; transition: 0.5s; }
        .course-card:hover .course-thumb { transform: scale(1.1); }
        
        .badge-gradient {
            position: absolute; top: 15px; left: 15px;
            background: linear-gradient(90deg, #23a6d5, #23d5ab);
            color: white; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; z-index: 2;
        }

        /* Doubt Section */
        .doubt-section {
            background: #fff; border-radius: 20px; padding: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05); border-left: 5px solid #e73c7e;
        }

        /* Floating Button */
        .float-chat {
            position: fixed; bottom: 30px; right: 30px;
            width: 65px; height: 65px;
            background: linear-gradient(135deg, #fb873f, #e73c7e);
            color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; box-shadow: 0 10px 25px rgba(231, 60, 126, 0.4);
            z-index: 999; text-decoration: none; transition: 0.3s;
        }
        .float-chat:hover { transform: scale(1.1) rotate(10deg); color: white; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="home.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2">
                <h3 class="m-0 fw-bold" style="color:#333;">Secret<span style="color:#e73c7e;">Coder</span></h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a href="home.php" class="nav-link active">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link">Courses</a></li>
                    <li class="nav-item"><a href="student_doubts.php" class="nav-link">Doubts</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    
                    <li class="nav-item ms-3">
                        <a href="instructor.html" class="btn-gradient text-decoration-none shadow-sm">Teach</a>
                    </li>

                    <li class="nav-item ms-4">
                        <a href="profile.php" class="d-flex align-items-center text-decoration-none">
                            <img src="https://ui-avatars.com/api/?name=<?php echo $username; ?>&background=random&rounded=true" width="40">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid hero-header gradient-bg">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h5 class="text-white text-uppercase letter-spacing-2 mb-3">Welcome to the Future</h5>
                    <h1 class="display-3 fw-bold text-white mb-4">Master Coding with <br> Secret Coder</h1>
                    <p class="fs-5 text-white-50 mb-5">Join the most vibrant community of learners. Access premium courses and get instant doubt clearance.</p>
                    <a href="courses.php" class="btn btn-light text-dark py-3 px-5 rounded-pill fw-bold me-2 shadow">Explore Courses</a>
                    <a href="student_doubts.php" class="btn btn-outline-light py-3 px-5 rounded-pill fw-bold">Ask Doubt</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5 mt-4">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold" style="color:#e73c7e;">Top Picks</h6>
            <h1 class="fw-bold">Trending Courses</h1>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-3 col-md-6">
                <div class="course-card">
                    <div class="position-relative">
                        <img src="image/img1.jpg" class="course-thumb" alt="Web Dev">
                        <span class="badge-gradient">FREE</span>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="fw-bold text-muted">Web Dev</small>
                            <small class="text-warning"><i class="fa fa-star"></i> 4.9</small>
                        </div>
                        <h5 class="fw-bold mb-3">Full Stack Development</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0 fw-bold" style="color:#23a6d5;">₹0</h5>
                            <a href="#" class="btn btn-sm btn-dark rounded-pill px-3">Enroll</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="course-card">
                    <div class="position-relative">
                        <img src="image/img2.jpg" class="course-thumb" alt="Python">
                        <span class="badge-gradient" style="background:linear-gradient(90deg, #11998e, #38ef7d);">PAID</span>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="fw-bold text-muted">Python</small>
                            <small class="text-warning"><i class="fa fa-star"></i> 4.8</small>
                        </div>
                        <h5 class="fw-bold mb-3">Python Masterclass</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0 fw-bold" style="color:#23a6d5;">₹499</h5>
                            <a href="#" class="btn btn-sm btn-dark rounded-pill px-3">Enroll</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="course-card">
                    <div class="position-relative">
                        <img src="image/img3.jpg" class="course-thumb" alt="Java">
                        <span class="badge-gradient">FREE</span>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="fw-bold text-muted">Java</small>
                            <small class="text-warning"><i class="fa fa-star"></i> 4.7</small>
                        </div>
                        <h5 class="fw-bold mb-3">Java for Beginners</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0 fw-bold" style="color:#23a6d5;">₹0</h5>
                            <a href="#" class="btn btn-sm btn-dark rounded-pill px-3">Enroll</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="course-card">
                    <div class="position-relative">
                        <img src="image/img4.jpg" class="course-thumb" alt="Cloud">
                        <span class="badge-gradient" style="background:linear-gradient(90deg, #11998e, #38ef7d);">PAID</span>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="fw-bold text-muted">Cloud</small>
                            <small class="text-warning"><i class="fa fa-star"></i> 4.9</small>
                        </div>
                        <h5 class="fw-bold mb-3">AWS Cloud Architect</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0 fw-bold" style="color:#23a6d5;">₹999</h5>
                            <a href="#" class="btn btn-sm btn-dark rounded-pill px-3">Enroll</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="doubt-section text-center">
            <h2 class="fw-bold mb-3">Stuck in Code? <span style="color:#e73c7e;">Ask an Expert!</span></h2>
            <p class="text-muted mb-4">Don't let errors stop your learning. Chat directly with our expert instructors and clarify your doubts instantly.</p>
            <a href="student_doubts.php" class="btn btn-gradient px-4 py-3 shadow">
                <i class="fa fa-comments me-2"></i> Chat with Instructor
            </a>
        </div>
    </div>

    <div class="container-fluid bg-dark text-light pt-5 mt-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h4 class="text-white mb-4">Secret Coder</h4>
                    <p class="text-white-50">Empowering learners with high-quality coding education. Join our community today.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-4">Quick Links</h5>
                    <a class="d-block text-white-50 mb-2 text-decoration-none" href="about.html">About Us</a>
                    <a class="d-block text-white-50 mb-2 text-decoration-none" href="courses.html">Courses</a>
                    <a class="d-block text-white-50 mb-2 text-decoration-none" href="instructor_register.php">Become Instructor</a>
                    <a class="d-block text-white-50 mb-2 text-decoration-none" href="contact.html">Contact Us</a>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-4">Contact</h5>
                    <p class="text-white-50"><i class="fa fa-envelope me-2"></i> support@secretcoder.com</p>
                    <p class="text-white-50"><i class="fa fa-phone me-2"></i> +91 9944967885</p>
                </div>
            </div>
        </div>
    </div>

    <a href="student_doubts.php" class="float-chat" title="Chat Now">
        <i class="fa fa-comment-dots"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
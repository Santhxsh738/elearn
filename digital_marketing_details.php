<?php
session_start();
include 'db.php'; 

// Check if user is logged in
if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''); 

// --- COURSE CONFIG & ENROLLMENT CHECK ---
$course_name = "Digital Marketing Mastery";
$is_enrolled = false;
$progress = 0;

$check_query = "SELECT * FROM course_enrollments WHERE course_name='$course_name'";
$result = mysqli_query($conn, $check_query);
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $db_email = isset($row['student_email']) ? $row['student_email'] : (isset($row['email']) ? $row['email'] : '');
        if($db_email == $user_email && !empty($user_email)){
            $is_enrolled = true;
            $progress = isset($row['progress']) ? $row['progress'] : 100; 
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Digital Marketing | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #ffffff; color: #333; }

        /* --- GRADIENT THEME (Vibrant Coral & Sunset Orange) --- */
        .gradient-bg {
            background: linear-gradient(135deg, #FF416C, #FF4B2B, #E63946);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: white;
            padding: 70px 0;
            border-bottom: 4px solid #d90429;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Navbar */
        .navbar { background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover { color: #FF416C !important; }

        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(90deg, #FF416C, #FF4B2B);
            color: white; border: none; padding: 14px 30px; border-radius: 50px; font-weight: 700; transition: 0.3s;
            text-decoration: none; display: inline-block; cursor: pointer; text-align: center;
        }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255, 65, 108, 0.3); color: white; }

        /* Course Styling */
        .course-info-card { background: white; border-radius: 20px; padding: 30px; border: 1px solid #f0f0f0; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .sidebar-card { background: white; border-radius: 20px; padding: 25px; border: 1px solid #f0f0f0; box-shadow: 0 15px 35px rgba(0,0,0,0.05); position: sticky; top: 100px; }
        
        .badge-skill { background: rgba(255, 65, 108, 0.08); color: #d90429; border: 1px solid rgba(255, 65, 108, 0.2); padding: 8px 20px; border-radius: 50px; font-size: 14px; margin: 5px; display: inline-block; font-weight: 600; }
        
        .accordion-button:not(.collapsed) { background-color: rgba(255, 65, 108, 0.05); color: #d90429; }
        .accordion-button:focus { border-color: #FF416C; box-shadow: none; }
        
        .instructor-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #FF416C; }

        .dm-text { color: #FF416C; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="home.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2">
                <h3 class="m-0 fw-bold">Secret<span class="dm-text">Coder</span></h3>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link active">Courses</a></li>
                    <li class="nav-item"><a href="student_doubts.php" class="nav-link">Doubts</a></li>
                    <li class="nav-item ms-4">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=FF416C&color=fff" width="40" class="rounded-circle">
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid gradient-bg">
        <div class="container text-center">
            <span class="badge mb-3 px-3 py-2" style="background: #fff; color: #FF416C; font-weight: 800; border-radius: 50px; letter-spacing: 1px;">GROWTH & STRATEGY</span>
            <h1 class="display-4 fw-bold mb-3">Digital Marketing Mastery</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent m-0 p-0">
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none opacity-75" href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none opacity-75" href="courses.php">Courses</a></li>
                    <li class="breadcrumb-item text-white active fw-bold">Digital Marketing</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <div class="course-info-card mb-4">
                    <h2 class="fw-bold mb-3">Grow Brands and Drive Sales</h2>
                    <p class="text-muted">Master the art of online growth. In this course, you will learn how to run profitable ad campaigns, rank on Google using SEO, and build a massive audience on social media.</p>
                    
                    <div class="d-flex flex-wrap gap-4 mt-4">
                        <small><i class="fa fa-star text-warning me-1"></i> 4.7 Rating</small>
                        <small><i class="fa fa-user-graduate text-primary me-1"></i> 34k+ Students</small>
                        <small><i class="fa fa-layer-group text-danger me-1"></i> Beginner to Pro</small>
                        <small><i class="fa fa-tag text-success me-1"></i> Free Access</small>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 class="fw-bold mb-4">Core Skills Covered</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="badge-skill">SEO (Search Engine Optimization)</div>
                            <div class="badge-skill">Social Media Marketing</div>
                            <div class="badge-skill">Google Ads & PPC</div>
                            <div class="badge-skill">Content Strategy</div>
                            <div class="badge-skill">Email Marketing</div>
                            <div class="badge-skill">Google Analytics 4 (GA4)</div>
                        </div>
                    </div>

                    <h3 class="fw-bold mt-5 mb-4">Course Curriculum</h3>
                    <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden border" id="syllabus">
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec1">
                                    Module 1: SEO & Content Marketing
                                </button>
                            </h2>
                            <div id="sec1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <ul class="list-unstyled m-0">
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 dm-text"></i> Keyword Research Mastery</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 dm-text"></i> On-Page & Off-Page SEO</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 dm-text"></i> Building a Content Calendar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-top">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec2">
                                    Module 2: Paid Ads & Analytics
                                </button>
                            </h2>
                            <div id="sec2" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <ul class="list-unstyled m-0">
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 dm-text"></i> Facebook & Instagram Ads Setup</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 dm-text"></i> Google Ads Bidding Strategies</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 dm-text"></i> Tracking Conversions with GA4</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card mt-5">
                    <h3 class="fw-bold mb-4">Meet your Instructor</h3>
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name=Emma+Growth&background=FF416C&color=fff" class="instructor-img me-4 shadow-sm">
                        <div>
                            <h5 class="fw-bold m-0">Emma Growth</h5>
                            <p class="text-primary small mb-2 fw-bold">Head of Marketing @ HubSpot</p>
                            <div class="small text-muted">
                                <span class="me-3"><i class="fa fa-star text-warning"></i> 4.9 Rating</span>
                                <span><i class="fa fa-video text-danger"></i> 5 Courses</span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-muted small">Emma is a growth hacker who specializes in turning startups into 7-figure businesses. She teaches real-world strategies that bring ROI, not just theory.</p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-card">
                    <img src="image/img9.jpg" onerror="this.src='https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?w=600&q=80'" class="img-fluid rounded-4 mb-4 shadow-sm">
                    
                    <?php if(!$is_enrolled): ?>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold m-0 text-success">FREE</h3>
                            <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">100% OFF</span>
                        </div>

                        <a href="enroll_process.php?course=Digital+Marketing+Mastery" class="btn-gradient w-100 py-3 mb-3 shadow">
                            <i class="fa fa-bullhorn me-2"></i> Enroll for Free
                        </a>
                        <p class="text-center small text-muted mb-4"><i class="fa fa-rocket text-warning"></i> Boost Your Career Today</p>
                    <?php else: ?>
                        <div class="alert alert-success text-center py-2 fw-bold mb-4"><i class="fa fa-check-circle"></i> Successfully Enrolled</div>
                        
                        <?php if($progress >= 100): ?>
                            <a href="generate_certificate.php?course=Digital+Marketing+Mastery" class="btn btn-success w-100 py-3 mb-3 fw-bold shadow"><i class="fa fa-award me-2"></i> Claim Marketing Certificate</a>
                        <?php else: ?>
                            <a href="#" class="btn btn-dark w-100 py-3 mb-3 fw-bold shadow">Continue Learning</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <ul class="list-unstyled small border-top pt-3 m-0">
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-clock me-2 dm-text"></i> Duration</span>
                            <span class="fw-bold text-dark">32.5 Hours</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-chart-line me-2 dm-text"></i> Live Case Studies</span>
                            <span class="fw-bold text-dark">Included</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-certificate me-2 dm-text"></i> Certificate</span>
                            <span class="fw-bold text-dark">Yes</span>
                        </li>
                    </ul>

                    <hr>
                    <div class="text-center">
                        <a href="#" class="text-decoration-none text-muted small fw-bold">Share this Course</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container-fluid bg-dark text-light pt-5 mt-5">
        <div class="container py-4 text-center">
            <p class="mb-0 opacity-50 small">&copy; Secret Coder Academy. All Rights Reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
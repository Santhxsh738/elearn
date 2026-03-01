<?php
session_start();
include 'db.php'; 

if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Full Stack Web Development | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }

        /* --- GRADIENT THEME --- */
        .gradient-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: white;
            padding: 60px 0;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .navbar { background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 15px 0; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; }
        .nav-link:hover { color: #e73c7e !important; }

        .btn-gradient {
            background: linear-gradient(90deg, #fb873f, #e73c7e);
            color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600; transition: 0.3s;
            text-decoration: none; display: inline-block;
        }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(231, 60, 126, 0.3); color: white; }

        /* Course Styling */
        .course-info-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .sidebar-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: sticky; top: 100px; }
        
        .badge-skill { background: rgba(35, 166, 213, 0.1); color: #23a6d5; border: 1px solid rgba(35, 166, 213, 0.2); padding: 8px 20px; border-radius: 50px; font-size: 14px; margin: 5px; display: inline-block; }
        
        .accordion-button:not(.collapsed) { background-color: rgba(231, 60, 126, 0.05); color: #e73c7e; }
        .accordion-button:focus { border-color: #e73c7e; box-shadow: none; }
        
        .instructor-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #e73c7e; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="home.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2">
                <h3 class="m-0 fw-bold">Secret<span style="color:#e73c7e;">Coder</span></h3>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link">Courses</a></li>
                    <li class="nav-item"><a href="student_doubts.php" class="nav-link">Doubts</a></li>
                    <li class="nav-item ms-4">
                        <img src="https://ui-avatars.com/api/?name=<?php echo $username; ?>&background=random" width="40" class="rounded-circle">
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid gradient-bg">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Full Stack Web Development</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a class="text-white" href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="courses.php">Courses</a></li>
                    <li class="breadcrumb-item text-white active opacity-75">Full Stack Dev</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <div class="course-info-card mb-4">
                    <h2 class="fw-bold mb-3">Master the Modern Web</h2>
                    <p class="text-muted">Become a complete developer by learning both Frontend and Backend technologies. This course covers everything from HTML/CSS to database management and server-side logic.</p>
                    
                    <div class="d-flex flex-wrap gap-4 mt-4">
                        <small><i class="fa fa-star text-warning me-1"></i> 4.9 Rating</small>
                        <small><i class="fa fa-user-graduate text-primary me-1"></i> 8.5L+ Students</small>
                        <small><i class="fa fa-layer-group text-danger me-1"></i> Expert Level</small>
                        <small><i class="fa fa-clock text-success me-1"></i> 45+ Hours</small>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 class="fw-bold mb-4">What you will learn</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="badge-skill">Frontend (HTML5, CSS3, JS)</div>
                            <div class="badge-skill">React JS & Redux</div>
                            <div class="badge-skill">Node.js & Express</div>
                            <div class="badge-skill">MongoDB & SQL</div>
                            <div class="badge-skill">RESTful APIs</div>
                            <div class="badge-skill">Deployment (AWS/Heroku)</div>
                        </div>
                    </div>

                    <h3 class="fw-bold mt-5 mb-4">Course Content (Syllabus)</h3>
                    <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="syllabus">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec1">
                                    Introduction to Full Stack & UI Design
                                </button>
                            </h2>
                            <div id="sec1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> What is Full Stack?</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Designing with Figma</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec2">
                                    Backend Development with Node.js
                                </button>
                            </h2>
                            <div id="sec2" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Server setup with Express</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Building API Endpoints</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card mt-5">
                    <h3 class="fw-bold mb-4">Meet your Instructor</h3>
                    <div class="d-flex align-items-center">
                        <img src="image/testimonial-2.jpg" class="instructor-img me-4 shadow-sm">
                        <div>
                            <h5 class="fw-bold m-0">Zoe Bachman</h5>
                            <p class="text-primary small mb-2">Senior Architect @ Google</p>
                            <div class="small text-muted">
                                <span class="me-3"><i class="fa fa-star text-warning"></i> 4.9 Rating</span>
                                <span><i class="fa fa-video text-danger"></i> 24 Courses</span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-muted small">Zoe is a veteran developer with over 12 years of experience in building scalable web applications. She has mentored thousands of students globally.</p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-card">
                    <img src="https://images.unsplash.com/photo-1621839673705-6617adf9e890?w=600&q=80" class="img-fluid rounded-3 mb-4 shadow-sm">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold m-0 text-primary">₹0 <small class="text-muted fs-6 text-decoration-line-through">₹4999</small></h3>
                        <span class="badge bg-danger rounded-pill">Limited Offer</span>
                    </div>
<div class="mt-auto">
    <a href="enroll_process.php?course=Full Stack Web Development" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                    
                    <ul class="list-unstyled small mt-4">
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-clock me-2 text-primary"></i> Duration</span>
                            <span class="fw-bold">45.5 Hours</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-book me-2 text-primary"></i> Total Lessons</span>
                            <span class="fw-bold">128</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-certificate me-2 text-primary"></i> Certificate</span>
                            <span class="fw-bold">Yes</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-infinity me-2 text-primary"></i> Access</span>
                            <span class="fw-bold">Lifetime</span>
                        </li>
                    </ul>

                    <hr>
                    <div class="text-center">
                        <a href="#" class="text-decoration-none text-muted small fw-bold"><i class="fa fa-share-alt me-2"></i> Share this Course</a>
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
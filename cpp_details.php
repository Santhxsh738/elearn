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
$course_name = "C++ Programming Masterclass";
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
    <title>C++ Masterclass | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }

        /* --- GRADIENT THEME (C++ Royal Blue & Silver Animated) --- */
        .gradient-bg {
            background: linear-gradient(-45deg, #004282, #0059b3, #8f94fb, #4e54c8);
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

        /* Navbar */
        .navbar { background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 15px 0; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover { color: #0059b3 !important; }

        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(90deg, #0059b3, #4e54c8);
            color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; transition: 0.3s;
            text-decoration: none; display: inline-block; cursor: pointer; text-align: center;
        }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 89, 179, 0.3); color: white; }

        /* Course Styling */
        .course-info-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .sidebar-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: sticky; top: 100px; }
        
        .badge-skill { background: rgba(0, 89, 179, 0.1); color: #0059b3; border: 1px solid rgba(0, 89, 179, 0.2); padding: 8px 20px; border-radius: 50px; font-size: 14px; margin: 5px; display: inline-block; font-weight: 500; }
        
        .accordion-button:not(.collapsed) { background-color: rgba(0, 89, 179, 0.1); color: #0059b3; }
        .accordion-button:focus { border-color: #0059b3; box-shadow: none; }
        
        .instructor-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #0059b3; }

        .cpp-text { color: #0059b3; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="home.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2">
                <h3 class="m-0 fw-bold">Secret<span class="cpp-text">Coder</span></h3>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link active">Courses</a></li>
                    <li class="nav-item"><a href="student_doubts.php" class="nav-link">Doubts</a></li>
                    <li class="nav-item ms-4">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=random" width="40" class="rounded-circle">
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid gradient-bg">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">C++ Programming Masterclass</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="courses.php">Courses</a></li>
                    <li class="breadcrumb-item text-white active opacity-75">C++ Development</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <div class="course-info-card mb-4">
                    <h2 class="fw-bold mb-3">Master High-Performance Coding</h2>
                    <p class="text-muted">C++ is the engine behind modern games, OS, and high-frequency trading. Learn Memory Management, Pointers, Object-Oriented Design, and the Standard Template Library (STL).</p>
                    
                    <div class="d-flex flex-wrap gap-4 mt-4">
                        <small><i class="fa fa-star text-warning me-1"></i> 4.8 Rating</small>
                        <small><i class="fa fa-user-graduate text-primary me-1"></i> 9k+ Students</small>
                        <small><i class="fa fa-layer-group text-danger me-1"></i> All Levels</small>
                        <small><i class="fa fa-tag text-success me-1"></i> Free Edition</small>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 class="fw-bold mb-4">What you will learn</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="badge-skill">C++ Syntax & Functions</div>
                            <div class="badge-skill">Pointers & Memory Allocation</div>
                            <div class="badge-skill">Object-Oriented Programming</div>
                            <div class="badge-skill">Data Structures & Algorithms</div>
                            <div class="badge-skill">Standard Template Library (STL)</div>
                            <div class="badge-skill">File Handling</div>
                        </div>
                    </div>

                    <h3 class="fw-bold mt-5 mb-4">Course Content (Syllabus)</h3>
                    <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="syllabus">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec1">
                                    Getting Started with C++
                                </button>
                            </h2>
                            <div id="sec1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Compilers & Environment Setup</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Data Types & Variables</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Control Flow & Loops</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec2">
                                    Core Concepts & Memory
                                </button>
                            </h2>
                            <div id="sec2" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Master Pointers & References</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Dynamic Memory (new & delete)</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-danger"></i> Understanding Classes & Objects</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card mt-5">
                    <h3 class="fw-bold mb-4">Meet your Instructor</h3>
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name=Bjarne+Expert&background=0059b3&color=fff" class="instructor-img me-4 shadow-sm">
                        <div>
                            <h5 class="fw-bold m-0">Bjarne Expert</h5>
                            <p class="text-primary small mb-2">Lead Systems Engineer @ Intel</p>
                            <div class="small text-muted">
                                <span class="me-3"><i class="fa fa-star text-warning"></i> 4.9 Rating</span>
                                <span><i class="fa fa-video text-danger"></i> 6 Courses</span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-muted small">A passionate low-level programmer who builds high-performance applications. He loves teaching students how the computer really works under the hood using C++.</p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-card">
                    <img src="image/img6.jpg" onerror="this.src='https://images.unsplash.com/photo-1542831371-29b0f74f9713?w=600&q=80'" class="img-fluid rounded-3 mb-4 shadow-sm">
                    
                    <?php if(!$is_enrolled): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold m-0 text-primary">₹0 <small class="text-muted fs-6 text-decoration-line-through">₹2999</small></h3>
                            <span class="badge bg-danger rounded-pill">100% FREE</span>
                        </div>

                        <a href="enroll_process.php?course=C++ Programming Masterclass" class="btn btn-gradient w-100 shadow-sm py-3 mb-3 fw-bold">Enroll For Free</a>
                    <?php else: ?>
                        <div class="alert alert-success text-center py-2 fw-bold mb-3"><i class="fa fa-check-circle"></i> Successfully Enrolled</div>
                        
                        <?php if($progress >= 100): ?>
                            <a href="generate_certificate.php?course=C%2B%2B+Programming+Masterclass" class="btn btn-success w-100 py-3 mb-3 fw-bold shadow"><i class="fa fa-award me-2"></i> Claim C++ Certificate</a>
                        <?php else: ?>
                            <a href="#" class="btn btn-dark w-100 py-3 mb-3 fw-bold shadow">Continue Learning</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <ul class="list-unstyled small mt-4">
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-clock me-2 cpp-text"></i> Duration</span>
                            <span class="fw-bold">35.5 Hours</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-book me-2 cpp-text"></i> Total Lessons</span>
                            <span class="fw-bold">98</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-certificate me-2 cpp-text"></i> Certificate</span>
                            <span class="fw-bold">Yes</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-infinity me-2 cpp-text"></i> Access</span>
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
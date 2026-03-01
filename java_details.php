<?php
session_start();
include 'db.php'; 

if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
// Universal email fetch from session
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : ''); 

// --- COURSE CONFIG ---
$course_name = "Java Programming Masterclass";
$is_enrolled = false;
$progress = 0;

// Universal Check to avoid SQL Errors
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
    <title>Java Masterclass | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }
        
        /* --- JAVA BLUE GRADIENT --- */
        .gradient-bg {
            background: linear-gradient(-45deg, #004e92, #000428, #0575E6, #021B79);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: white; padding: 60px 0;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .navbar { background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 15px 0; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; }
        .nav-link:hover { color: #0575E6 !important; }

        .btn-gradient {
            background: linear-gradient(90deg, #0575E6, #021B79);
            color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600; transition: 0.3s;
            text-decoration: none; display: inline-block;
        }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(5, 117, 230, 0.3); color: white; }

        .course-info-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .sidebar-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: sticky; top: 100px; }
        
        .badge-skill { background: rgba(5, 117, 230, 0.1); color: #0575E6; border: 1px solid rgba(5, 117, 230, 0.2); padding: 8px 20px; border-radius: 50px; font-size: 14px; margin: 5px; display: inline-block; }
        
        .accordion-button:not(.collapsed) { background-color: rgba(5, 117, 230, 0.05); color: #0575E6; }
        .instructor-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #0575E6; }
        
        .cert-btn {
            background: linear-gradient(90deg, #23d5ab, #23a6d5);
            color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; width: 100%; display: block; text-align: center; text-decoration: none;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="home.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2">
                <h3 class="m-0 fw-bold">Secret<span style="color:#0575E6;">Coder</span></h3>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link">Courses</a></li>
                    <li class="nav-item"><a href="student_doubts.php" class="nav-link">Doubts</a></li>
                    <li class="nav-item ms-4">
                        <img src="https://ui-avatars.com/api/?name=<?php echo $username; ?>&background=0575E6&color=fff" width="40" class="rounded-circle">
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid gradient-bg text-center">
        <h1 class="display-4 fw-bold mb-3">Java Programming Masterclass</h1>
        <p class="text-white opacity-75">Level up from Core Java to Enterprise Spring Boot Applications</p>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="course-info-card mb-4">
                    <h2 class="fw-bold mb-3">Master the World's Most Powerful Language</h2>
                    <p class="text-muted">Java is the foundation for enterprise software. Learn everything from Object-Oriented Principles to Spring Framework, Hibernate, and Microservices architecture.</p>
                    
                    <div class="d-flex flex-wrap gap-4 mt-4 text-center">
                        <div class="flex-fill p-2 border rounded">
                            <h5 class="fw-bold mb-0">4.8</h5>
                            <small class="text-muted">Rating</small>
                        </div>
                        <div class="flex-fill p-2 border rounded">
                            <h5 class="fw-bold mb-0">60h+</h5>
                            <small class="text-muted">Duration</small>
                        </div>
                        <div class="flex-fill p-2 border rounded">
                            <h5 class="fw-bold mb-0">140+</h5>
                            <small class="text-muted">Lessons</small>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 class="fw-bold mb-4">What you will learn</h3>
                    <div class="badge-skill">Advanced OOP Concepts</div>
                    <div class="badge-skill">Java Collections & Streams</div>
                    <div class="badge-skill">Spring Boot 3.0</div>
                    <div class="badge-skill">Hibernate & JPA</div>
                    <div class="badge-skill">Microservices with Spring Cloud</div>
                    <div class="badge-skill">JUnit Testing</div>

                    <h3 class="fw-bold mt-5 mb-4">Syllabus</h3>
                    <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="syllabus">
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec1">Section 1: Java Basics & OOP</button></h2>
                            <div id="sec1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-primary"></i> Introduction to JDK 21</li>
                                        <li class="mb-2"><i class="fa fa-play-circle me-2 text-primary"></i> Understanding Classes & Interfaces</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card mt-5">
                    <h3 class="fw-bold mb-4">Meet your Instructor</h3>
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name=James+Gosling&background=0575E6&color=fff" class="instructor-img me-4 shadow-sm">
                        <div>
                            <h5 class="fw-bold m-0">James Gosling (Lead)</h5>
                            <p class="text-primary small mb-2">Senior Java Architect @ Oracle</p>
                            <div class="small text-muted"><span class="me-3"><i class="fa fa-star text-warning"></i> 4.9 Rating</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-card">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&q=80" class="img-fluid rounded-3 mb-4 shadow-sm">
                    
                    <?php if(!$is_enrolled): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold m-0 text-primary">FREE</h3>
                            <span class="badge bg-danger rounded-pill">Limited Offer</span>
                        </div>
                        <a href="enroll_process.php?course=Java Programming Masterclass" class="btn-gradient w-100 shadow-sm">Enroll Now</a>
                    <?php else: ?>
                        <div class="alert alert-success text-center fw-bold"><i class="fa fa-check-circle me-1"></i> Enrolled</div>
                        
                        <?php if($progress >= 100): ?>
                            <a href="generate_certificate.php?course=Java+Programming+Masterclass" class="cert-btn shadow mt-2">
                                <i class="fa fa-award me-2"></i> Claim Certificate
                            </a>
                        <?php else: ?>
                            <a href="#" class="btn btn-dark w-100 rounded-pill mb-2">Continue Learning</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <ul class="list-unstyled small mt-4">
                        <li class="mb-3 d-flex justify-content-between"><span><i class="fa fa-clock me-2 text-primary"></i> Hours</span><b>60.5</b></li>
                        <li class="mb-3 d-flex justify-content-between"><span><i class="fa fa-certificate me-2 text-primary"></i> Certificate</span><b>Included</b></li>
                        <li class="mb-3 d-flex justify-content-between"><span><i class="fa fa-infinity me-2 text-primary"></i> Access</span><b>Lifetime</b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
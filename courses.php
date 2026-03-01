<?php
session_start();
include 'db.php'; 

// 1. Security Check
if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// 2. Search Logic
$search_sql = "";
$search_query = "";
if(isset($_GET['search'])){
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $search_sql = " WHERE course_name LIKE '%$search_query%'";
}

// 3. FETCH INSTRUCTOR COURSES
$sql = "SELECT * FROM instructor_courses $search_sql ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>All Courses | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }

        /* Gradients */
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

        /* Hero */
        .hero-header { padding: 80px 0; text-align: center; }
        
        /* Search */
        .search-box { position: relative; max-width: 600px; margin: 0 auto; }
        .search-input {
            width: 100%; padding: 15px 25px; border-radius: 50px; border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2); font-size: 16px; color: #333;
        }
        .search-btn {
            position: absolute; right: 5px; top: 5px;
            background: #fb873f; color: white; border: none;
            width: 45px; height: 45px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: 0.3s;
        }
        .search-btn:hover { background: #e73c7e; }

        /* Section Title */
        .section-title {
            position: relative; display: inline-block; margin-bottom: 40px;
            font-weight: 700; color: #333; font-size: 28px;
        }
        .section-title::after {
            content: ""; position: absolute; width: 60px; height: 4px; border-radius: 2px;
            background: #e73c7e; bottom: -10px; left: 50%; transform: translateX(-50%);
        }

        /* Course Cards */
        .course-card {
            background: #fff; border-radius: 15px; overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: 0.3s;
            height: 100%; border: none; display: flex; flex-direction: column;
        }
        .course-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        
        .thumb-box {
            height: 180px; width: 100%; position: relative; overflow: hidden; background: #000;
        }
        .course-thumb {
            width: 100%; height: 100%; object-fit: cover; opacity: 0.9; transition: 0.3s;
        }
        .course-card:hover .course-thumb { opacity: 1; transform: scale(1.05); }
        
        .play-overlay {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            font-size: 50px; color: white; opacity: 0; transition: 0.3s;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .course-card:hover .play-overlay { opacity: 1; }

        .instructor-badge {
            background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 20px;
            font-size: 12px; font-weight: 600; color: #333;
            position: absolute; bottom: 10px; left: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Floating Chat */
        .float-chat {
            position: fixed; bottom: 30px; right: 30px;
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #fb873f, #e73c7e);
            color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; box-shadow: 0 10px 25px rgba(231, 60, 126, 0.4);
            z-index: 999; text-decoration: none; transition: 0.3s;
        }
        .float-chat:hover { transform: scale(1.1); color: white; }
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
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link active">Courses</a></li>
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
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">All Courses</h1>
            <p class="fs-5 text-white-50 mb-4">Browse videos and notes uploaded by our expert instructors.</p>
            
            <form action="" method="GET" class="search-box">
                <input type="text" name="search" class="search-input" placeholder="Search for Python, Java, Web..." value="<?php echo $search_query; ?>">
                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>

    <div class="container py-5">
        
        <?php if($search_query == ""): ?>
        <div class="text-center">
            <h2 class="section-title">Popular Series</h2>
        </div>
        <div class="row g-4 mb-5">
            
            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img5.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-success rounded-pill">Free</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.9</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">Full Stack Web Dev</h5>
                        <div class="mt-auto">
                            <a href="fullstack_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img2.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary rounded-pill">Premium</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.8</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">Python Masterclass</h5>
                        <div class="mt-auto">
                            <a href="python_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img3.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-success rounded-pill">Free</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.7</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">Java for Beginners</h5>
                        <div class="mt-auto">
                            <a href="java_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img4.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary rounded-pill">Premium</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.9</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">AWS Cloud Architect</h5>
                        <div class="mt-auto">
                            <a href="aws_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img6.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-success rounded-pill">Free</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.6</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">C++ Programming</h5>
                        <div class="mt-auto">
                            <a href="cpp_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img7.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary rounded-pill">Premium</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.8</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">React JS Bootcamp</h5>
                        <div class="mt-auto">
                            <a href="react_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img8.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-success rounded-pill">Free</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.9</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">DSA with Java</h5>
                        <div class="mt-auto">
                            <a href="dsa_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="image/img9.jpg" class="course-thumb">
                        <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <div class="instructor-badge"><i class="fa fa-check-circle me-1 text-success"></i> Secret Coder</div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-success rounded-pill">Free</span>
                            <small class="text-muted"><i class="fa fa-star text-warning"></i> 4.5</small>
                        </div>
                        <h5 class="fw-bold mb-3 text-dark">Digital Marketing</h5>
                        <div class="mt-auto">
                            <a href="digital_marketing_details.php" class="btn btn-gradient w-100 shadow-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php endif; ?>


        <div class="text-center">
            <h2 class="section-title">Fresh from Instructors</h2>
        </div>
        <div class="row g-4">
            
            <?php
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    
                    // --- SMART IMAGE LOGIC ---
                    $type = isset($row['course_type']) ? $row['course_type'] : '';
                    $video_link = $row['video_link'];
                    $title = $row['course_name'];
                    
                    // Default
                    $thumb_url = "image/img10.jpg";

                    // Check keywords
                    if(stripos($title, 'python') !== false) { $thumb_url = "image/img2.jpg"; } 
                    elseif(stripos($title, 'java') !== false) { $thumb_url = "image/img3.jpg"; } 
                    elseif(stripos($title, 'web') !== false) { $thumb_url = "image/img1.jpg"; }
                    elseif(stripos($title, 'cloud') !== false) { $thumb_url = "image/img4.jpg"; }

                    // YouTube Logic
                    if(strpos($video_link, 'youtube.com') !== false || strpos($video_link, 'youtu.be') !== false){
                        $type = "youtube";
                        parse_str( parse_url( $video_link, PHP_URL_QUERY ), $my_array_of_vars );
                        if(isset($my_array_of_vars['v'])){
                            $vid_id = $my_array_of_vars['v'];
                            $thumb_url = "https://img.youtube.com/vi/$vid_id/hqdefault.jpg";
                        }
                    } elseif($type == 'note') {
                        $thumb_url = "image/img12.jpg";
                    }
            ?>

            <div class="col-lg-3 col-md-6 wow fadeInUp">
                <div class="course-card h-100">
                    <div class="thumb-box">
                        <img src="<?php echo $thumb_url; ?>" class="course-thumb" alt="Thumbnail">
                        <?php if($type == 'note'): ?>
                            <div class="play-overlay"><i class="fa fa-book-open"></i></div>
                        <?php else: ?>
                            <div class="play-overlay"><i class="fa fa-play-circle"></i></div>
                        <?php endif; ?>
                        <div class="instructor-badge">
                            <i class="fa fa-user-circle me-1 text-primary"></i> <?php echo $row['instructor_name']; ?>
                        </div>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between mb-2">
                            <?php if($type == 'note'): ?>
                                <span class="badge bg-warning text-dark rounded-pill">Notes</span>
                            <?php else: ?>
                                <span class="badge bg-primary rounded-pill">Video</span>
                            <?php endif; ?>
                            <small class="text-muted"><i class="fa fa-clock me-1"></i> <?php echo date('M d', strtotime($row['upload_date'])); ?></small>
                        </div>
                        <h6 class="fw-bold mb-3 text-dark"><?php echo $title; ?></h6>
                        <div class="mt-auto">
                            <?php if($type == 'note'): ?>
                                <a href="view_note.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-dark btn-sm w-100 rounded-pill fw-bold">Read</a>
                            <?php else: ?>
                                <a href="<?php echo $video_link; ?>" target="_blank" class="btn btn-gradient btn-sm w-100 shadow-sm">Watch</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
                } 
            } else {
                echo "<div class='col-12 text-center py-5'><h5 class='text-muted'>No instructor uploads yet!</h5></div>";
            } 
            ?>
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
                    <a class="d-block text-white-50 mb-2 text-decoration-none" href="home.php">Home</a>
                    <a class="d-block text-white-50 mb-2 text-decoration-none" href="courses.html">Courses</a>
                    <a class="d-block text-white-50 mb-2 text-decoration-none" href="student_doubts.php">Doubts</a>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-4">Contact</h5>
                    <p class="text-white-50"><i class="fa fa-envelope me-2"></i> support@secretcoder.com</p>
                </div>
            </div>
        </div>
    </div>

    <a href="student_doubts.php" class="float-chat" title="Chat Now"><i class="fa fa-comment-dots"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
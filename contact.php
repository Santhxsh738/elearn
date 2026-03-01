<?php
session_start();
include 'db.php'; 

// 1. Session Check
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
    <title>Contact | Secret Coder</title>
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

        /* Header Section */
        .page-header { padding: 80px 0; text-align: center; }

        /* Search Box */
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

        /* Contact Card */
        .contact-card {
            background: #fff; border-radius: 20px; padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .form-control {
            background: #f8f9fa; border: 1px solid #eee; padding: 15px; border-radius: 10px;
        }
        .form-control:focus {
            background: #fff; border-color: #e73c7e; box-shadow: 0 0 0 3px rgba(231, 60, 126, 0.1);
        }

        /* Side Previews */
        .preview-box {
            background: #fff; border-radius: 15px; padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px;
        }
        .video-thumb {
            width: 100%; height: 120px; object-fit: cover; border-radius: 10px;
            margin-bottom: 15px; border: 2px solid #f0f0f0;
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
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link">Courses</a></li>
                    <li class="nav-item"><a href="student_doubts.php" class="nav-link">Doubts</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>
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

    <div class="container-fluid page-header gradient-bg">
        <div class="container">
            <h1 class="display-4 fw-bold text-white mb-3">Get in Touch</h1>
            <p class="fs-5 text-white-50 mb-4">Have questions about courses? Search here or send us a message.</p>
            
            <form action="courses.php" method="GET" class="search-box">
                <input type="text" name="search" class="search-input" placeholder="Search for videos or topics...">
                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="contact-card">
                    <h3 class="fw-bold mb-4">Send Message</h3>
                    <form action="contact_process.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" name="send_message" class="btn-gradient w-100 py-3 mt-2">SEND MESSAGE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="preview-box">
                    <h5 class="fw-bold mb-4">Quick Contact</h5>
                    <p><i class="fa fa-map-marker-alt text-primary me-2"></i> Avinashi, Tamil Nadu</p>
                    <p><i class="fa fa-phone-alt text-primary me-2"></i> +91 9944967885</p>
                    <p><i class="fa fa-envelope text-primary me-2"></i> support@secretcoder.com</p>
                </div>

                <h5 class="fw-bold mb-3 mt-4">Instructor Previews</h5>
                
                <div class="preview-box d-flex align-items-center">
                    <div style="width: 100px; height: 70px; flex-shrink: 0;" class="me-3">
                        <img src="image/img1.jpg" class="video-thumb m-0" alt="Web">
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Modern Web Dev</h6>
                        <small class="text-muted">By Instructor Santhosh</small>
                    </div>
                </div>

                <div class="preview-box d-flex align-items-center">
                    <div style="width: 100px; height: 70px; flex-shrink: 0;" class="me-3">
                        <img src="image/img2.jpg" class="video-thumb m-0" alt="Python">
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Python Advanced</h6>
                        <small class="text-muted">By Instructor Raju</small>
                    </div>
                </div>

                <a href="courses.php" class="btn btn-outline-dark w-100 rounded-pill fw-bold py-2 mt-2">Browse All Videos</a>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-dark text-light pt-5 mt-5">
        <div class="container py-4 text-center">
            <p class="mb-0 opacity-50">&copy; Secret Coder. All Rights Reserved.</p>
        </div>
    </div>

    <a href="student_doubts.php" class="float-chat" title="Chat Now"><i class="fa fa-comment-dots"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
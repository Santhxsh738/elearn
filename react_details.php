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
$course_name = "React JS Bootcamp";
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
    <title>React JS Bootcamp | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }

        /* --- GRADIENT THEME (React Dark Blue & Cyan Animated) --- */
        .gradient-bg {
            background: linear-gradient(-45deg, #20232a, #282c34, #61dafb, #00d2ff);
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
        .nav-link:hover { color: #00d2ff !important; }

        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(90deg, #00d2ff, #3a7bd5);
            color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; transition: 0.3s;
            text-decoration: none; display: inline-block; cursor: pointer;
        }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 210, 255, 0.3); color: white; }

        /* Course Styling */
        .course-info-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .sidebar-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: sticky; top: 100px; }
        
        .badge-skill { background: rgba(0, 210, 255, 0.1); color: #008cc9; border: 1px solid rgba(0, 210, 255, 0.2); padding: 8px 20px; border-radius: 50px; font-size: 14px; margin: 5px; display: inline-block; font-weight: 500; }
        
        .accordion-button:not(.collapsed) { background-color: rgba(0, 210, 255, 0.1); color: #008cc9; }
        .accordion-button:focus { border-color: #00d2ff; box-shadow: none; }
        
        .instructor-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #00d2ff; }

        /* Payment Modal Styling */
        .modal-content { border-radius: 20px; border: none; }
        .modal-header { border-bottom: 1px solid #f0f0f0; background: #f9f9f9; border-radius: 20px 20px 0 0; }
        
        .pay-option { 
            border: 2px solid #eee; border-radius: 10px; padding: 15px; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
        }
        .pay-option:hover, .pay-option.active { border-color: #00d2ff; background: rgba(0, 210, 255, 0.05); }
        
        .form-control { border-radius: 10px; padding: 12px; }

        .react-text { color: #008cc9; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="home.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2">
                <h3 class="m-0 fw-bold">Secret<span style="color:#00d2ff;">Coder</span></h3>
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
            <h1 class="display-4 fw-bold mb-3">React JS Masterclass 2026</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white text-decoration-none" href="courses.php">Courses</a></li>
                    <li class="breadcrumb-item text-white active opacity-75">React JS</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <div class="course-info-card mb-4">
                    <h2 class="fw-bold mb-3">Build Powerful Web Applications</h2>
                    <p class="text-muted">Master the most popular frontend library. This bootcamp takes you from foundational React concepts to advanced patterns like Hooks, Context API, Redux Toolkit, and performance optimization.</p>
                    
                    <div class="d-flex flex-wrap gap-4 mt-4">
                        <small><i class="fa fa-star text-warning me-1"></i> 4.9 Rating</small>
                        <small><i class="fa fa-user-graduate text-primary me-1"></i> 18k+ Students</small>
                        <small><i class="fa fa-layer-group text-danger me-1"></i> All Levels</small>
                        <small><i class="fa fa-certificate text-success me-1"></i> Premium</small>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 class="fw-bold mb-4">What you will learn</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="badge-skill">React Fundamentals & JSX</div>
                            <div class="badge-skill">React Hooks (useState, useEffect)</div>
                            <div class="badge-skill">State Management (Redux Toolkit)</div>
                            <div class="badge-skill">React Router DOM</div>
                            <div class="badge-skill">API Integration & Fetch</div>
                            <div class="badge-skill">Next.js Basics</div>
                        </div>
                    </div>

                    <h3 class="fw-bold mt-5 mb-4">Course Content (Syllabus)</h3>
                    <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="syllabus">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec1">
                                    Getting Started with React
                                </button>
                            </h2>
                            <div id="sec1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Setting up Vite & React</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Understanding JSX & Components</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Props vs State</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec2">
                                    Advanced State & Hooks
                                </button>
                            </h2>
                            <div id="sec2" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Master useEffect & Custom Hooks</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Context API for Global State</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Integrating Redux Toolkit</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card mt-5">
                    <h3 class="fw-bold mb-4">Meet your Instructor</h3>
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name=Alex+Techie&background=00d2ff&color=fff" class="instructor-img me-4 shadow-sm">
                        <div>
                            <h5 class="fw-bold m-0">Alex Techie</h5>
                            <p class="text-primary small mb-2">Senior Frontend Engineer @ Meta</p>
                            <div class="small text-muted">
                                <span class="me-3"><i class="fa fa-star text-warning"></i> 4.9 Rating</span>
                                <span><i class="fa fa-video text-danger"></i> 10 Courses</span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-muted small">Alex is a UI/UX expert and a core contributor to multiple open-source frontend libraries. He specializes in breaking down complex React concepts into simple, visual lessons.</p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-card">
                    <img src="image/img7.jpg" onerror="this.src='https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=600&q=80'" class="img-fluid rounded-3 mb-4 shadow-sm">
                    
                    <?php if(!$is_enrolled): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="fw-bold m-0 text-success">₹499 <small class="text-muted fs-6 text-decoration-line-through">₹2999</small></h3>
                            <span class="badge bg-warning text-dark rounded-pill">83% OFF</span>
                        </div>

                        <button type="button" class="btn-gradient w-100 py-3 mb-3 shadow" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fa fa-lock me-2"></i> Buy Now for ₹499
                        </button>
                    <?php else: ?>
                        <div class="alert alert-success text-center py-2 fw-bold mb-3"><i class="fa fa-check-circle"></i> Already Enrolled</div>
                        <?php if($progress >= 100): ?>
                            <a href="generate_certificate.php?course=React+JS+Bootcamp" class="btn btn-success w-100 py-3 mb-3 fw-bold shadow"><i class="fa fa-award me-2"></i> Claim Certificate</a>
                        <?php else: ?>
                            <a href="#" class="btn btn-dark w-100 py-3 mb-3 fw-bold shadow">Continue Learning</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <p class="text-center small text-muted"><i class="fa fa-shield-alt"></i> 30-Day Money-Back Guarantee</p>

                    <ul class="list-unstyled small mt-4">
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-clock me-2 react-text"></i> Duration</span>
                            <span class="fw-bold">42 Hours</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-file-code me-2 react-text"></i> Projects</span>
                            <span class="fw-bold">8 Real World</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-certificate me-2 react-text"></i> Certificate</span>
                            <span class="fw-bold">Yes</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-infinity me-2 react-text"></i> Access</span>
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

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa fa-credit-card me-2 text-success"></i> Secure Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark">₹499.00</h4>
                        <small class="text-muted">React JS Bootcamp 2026</small>
                    </div>

                    <form action="payment_gateway.php" method="GET">
                        <input type="hidden" name="course" value="React JS Bootcamp">
                        <input type="hidden" name="url" value="https://www.youtube.com/watch?v=bMknfKXIFA8">

                        <h6 class="fw-bold mb-3">Select Payment Method</h6>
                        
                        <div class="pay-option active" id="btn-card" onclick="selectPayment('card')">
                            <i class="fab fa-cc-visa text-primary fa-lg"></i> Credit / Debit Card
                        </div>
                        <div class="pay-option" id="btn-upi" onclick="selectPayment('upi')">
                            <i class="fab fa-google-pay text-danger fa-lg"></i> UPI / GPay / PhonePe
                        </div>

                        <div id="card-form">
                            <div class="mt-3">
                                <label class="small fw-bold mb-1">Card Number</label>
                                <input type="text" class="form-control mb-2" placeholder="0000 0000 0000 0000">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="small fw-bold mb-1">Expiry</label>
                                        <input type="text" class="form-control" placeholder="MM/YY">
                                    </div>
                                    <div class="col-6">
                                        <label class="small fw-bold mb-1">CVV</label>
                                        <input type="password" class="form-control" placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="upi-form" style="display: none;">
                            <div class="mt-3 text-center">
                                <label class="small fw-bold mb-2">Enter UPI ID</label>
                                <input type="text" class="form-control mb-3" placeholder="username@okaxis">
                                <p class="text-muted small">OR</p>
                                <div class="border p-3 rounded bg-light d-inline-block">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=REACT_PREMIUM_PAYMENT" width="100">
                                </div>
                                <p class="small text-success mt-2"><i class="fa fa-check-circle"></i> Scan using GPay / PhonePe</p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 mt-4 fw-bold shadow-sm">
                            Proceed to Pay ₹499
                        </button>
                    </form>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <small class="text-muted"><i class="fa fa-lock"></i> Secured by SecretCoder Pay</small>
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
    
    <script>
        function selectPayment(type) {
            // Remove active class from both
            document.getElementById('btn-card').classList.remove('active');
            document.getElementById('btn-upi').classList.remove('active');

            // Add active class based on selection
            if (type === 'card') {
                document.getElementById('btn-card').classList.add('active');
                document.getElementById('card-form').style.display = 'block';
                document.getElementById('upi-form').style.display = 'none';
            } else {
                document.getElementById('btn-upi').classList.add('active');
                document.getElementById('card-form').style.display = 'none';
                document.getElementById('upi-form').style.display = 'block';
            }
        }
    </script>
</body>
</html>
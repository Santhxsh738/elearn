<?php
session_start();
include 'db.php'; 

// Check if user is logged in
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
    <title>Python Masterclass | Secret Coder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #333; }

        /* --- GRADIENT THEME (Greenish for Python) --- */
        .gradient-bg {
            background: linear-gradient(-45deg, #11998e, #38ef7d, #0575E6, #021B79);
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
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; }
        .nav-link:hover { color: #38ef7d !important; }

        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(90deg, #11998e, #38ef7d);
            color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 600; transition: 0.3s;
            text-decoration: none; display: inline-block; cursor: pointer;
        }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(56, 239, 125, 0.3); color: white; }

        /* Course Styling */
        .course-info-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .sidebar-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); position: sticky; top: 100px; }
        
        .badge-skill { background: rgba(17, 153, 142, 0.1); color: #11998e; border: 1px solid rgba(17, 153, 142, 0.2); padding: 8px 20px; border-radius: 50px; font-size: 14px; margin: 5px; display: inline-block; }
        
        .accordion-button:not(.collapsed) { background-color: rgba(56, 239, 125, 0.1); color: #11998e; }
        .accordion-button:focus { border-color: #38ef7d; box-shadow: none; }
        
        .instructor-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #38ef7d; }

        /* Payment Modal Styling */
        .modal-content { border-radius: 20px; border: none; }
        .modal-header { border-bottom: 1px solid #f0f0f0; background: #f9f9f9; border-radius: 20px 20px 0 0; }
        
        .pay-option { 
            border: 2px solid #eee; border-radius: 10px; padding: 15px; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
        }
        .pay-option:hover, .pay-option.active { border-color: #11998e; background: rgba(17, 153, 142, 0.05); }
        
        .form-control { border-radius: 10px; padding: 12px; }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="home.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2">
                <h3 class="m-0 fw-bold">Secret<span style="color:#11998e;">Coder</span></h3>
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link active">Courses</a></li>
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
            <h1 class="display-4 fw-bold mb-3">Python Masterclass 2026</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent">
                    <li class="breadcrumb-item"><a class="text-white" href="home.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="courses.php">Courses</a></li>
                    <li class="breadcrumb-item text-white active opacity-75">Python</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <div class="course-info-card mb-4">
                    <h2 class="fw-bold mb-3">Zero to Hero in Python</h2>
                    <p class="text-muted">Learn Python from scratch to advanced level. This comprehensive course covers Python basics, Object Oriented Programming, Data Analysis with Pandas, and Web Development with Django.</p>
                    
                    <div class="d-flex flex-wrap gap-4 mt-4">
                        <small><i class="fa fa-star text-warning me-1"></i> 4.8 Rating</small>
                        <small><i class="fa fa-user-graduate text-primary me-1"></i> 12k+ Students</small>
                        <small><i class="fa fa-layer-group text-danger me-1"></i> All Levels</small>
                        <small><i class="fa fa-certificate text-success me-1"></i> Premium</small>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 class="fw-bold mb-4">What you will learn</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="badge-skill">Python Basics & Syntax</div>
                            <div class="badge-skill">OOP Concepts</div>
                            <div class="badge-skill">Data Structures</div>
                            <div class="badge-skill">Pandas & NumPy</div>
                            <div class="badge-skill">Django Framework</div>
                            <div class="badge-skill">Automation Scripts</div>
                        </div>
                    </div>

                    <h3 class="fw-bold mt-5 mb-4">Course Content (Syllabus)</h3>
                    <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="syllabus">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec1">
                                    Getting Started with Python
                                </button>
                            </h2>
                            <div id="sec1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Installing Python & VS Code</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Variables & Data Types</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Conditional Statements</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#sec2">
                                    Data Science & Machine Learning Basics
                                </button>
                            </h2>
                            <div id="sec2" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Introduction to NumPy</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Data Cleaning with Pandas</li>
                                        <li class="mb-2"><i class="fa fa-lock me-2 text-muted"></i> Building a Simple AI Model</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="course-info-card mt-5">
                    <h3 class="fw-bold mb-4">Meet your Instructor</h3>
                    <div class="d-flex align-items-center">
                        <img src="image/testimonial-3.jpg" class="instructor-img me-4 shadow-sm">
                        <div>
                            <h5 class="fw-bold m-0">Dr. Alan Turing</h5>
                            <p class="text-primary small mb-2">Lead Data Scientist @ Microsoft</p>
                            <div class="small text-muted">
                                <span class="me-3"><i class="fa fa-star text-warning"></i> 4.8 Rating</span>
                                <span><i class="fa fa-video text-danger"></i> 12 Courses</span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-muted small">Alan is a passionate Pythonista with extensive experience in Artificial Intelligence and Web Development. He simplifies complex topics into easy-to-understand lessons.</p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar-card">
                    <img src="image/img2.jpg" class="img-fluid rounded-3 mb-4 shadow-sm">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold m-0 text-success">₹499 <small class="text-muted fs-6 text-decoration-line-through">₹2499</small></h3>
                        <span class="badge bg-warning text-dark rounded-pill">80% OFF</span>
                    </div>

                    <button type="button" class="btn btn-gradient w-100 py-3 mb-3 shadow" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fa fa-lock me-2"></i> Buy Now for ₹499
                    </button>
                    
                    <p class="text-center small text-muted"><i class="fa fa-shield-alt"></i> 30-Day Money-Back Guarantee</p>

                    <ul class="list-unstyled small mt-4">
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-clock me-2 text-success"></i> Duration</span>
                            <span class="fw-bold">52 Hours</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-file-code me-2 text-success"></i> Projects</span>
                            <span class="fw-bold">10+ Real World</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-certificate me-2 text-success"></i> Certificate</span>
                            <span class="fw-bold">Yes</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between">
                            <span><i class="fa fa-infinity me-2 text-success"></i> Access</span>
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
                        <small class="text-muted">Python Masterclass 2026</small>
                    </div>

                    <form action="payment_gateway.php" method="GET">
                        <input type="hidden" name="course" value="Python Masterclass">
                        <input type="hidden" name="url" value="https://www.youtube.com/watch?v=_uQrJ0TkZlc">

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
                                    <img src="image/scanner.svg" width="100">
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Secure Payment Gateway</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { 
            background-color: #f0f2f5; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0;
        }

        .gateway-card {
            background: white; 
            width: 420px; 
            padding: 40px; 
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            text-center;
            position: relative;
            overflow: hidden;
        }

        /* Top Bar Colors */
        .gateway-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 6px;
            background: linear-gradient(90deg, #6c5ce7, #0984e3);
        }

        /* Loader Animation */
        .loader {
            border: 4px solid #f3f3f3; 
            border-top: 4px solid #0984e3; 
            border-radius: 50%;
            width: 60px; height: 60px; 
            animation: spin 1s linear infinite; 
            margin: 30px auto;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Success Animation */
        .success-icon {
            font-size: 70px;
            color: #00b894;
            margin-bottom: 20px;
            animation: pop 0.5s ease;
        }
        @keyframes pop { 0% { transform: scale(0); } 80% { transform: scale(1.1); } 100% { transform: scale(1); } }

        .bank-logo {
            font-size: 24px;
            font-weight: 700;
            color: #2d3436;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="gateway-card">
        
        <div class="bank-logo">
            <i class="fa-solid fa-shield-halved text-primary"></i> SecurePay Gate
        </div>

        <div id="processing-view">
            <h5 class="fw-bold text-secondary">Processing Transaction...</h5>
            <p class="text-muted small">Please do not close this window or press back.</p>
            
            <div class="loader"></div>
            
            <div class="mt-4">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Merchant:</span>
                    <span class="fw-bold text-dark">Secret Coder</span>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span>Amount:</span>
                    <span class="fw-bold text-dark">₹499.00</span>
                </div>
            </div>
        </div>

        <div id="success-view" style="display: none;">
            <i class="fa-solid fa-circle-check success-icon"></i>
            <h3 class="fw-bold text-success">Payment Successful!</h3>
            <p class="text-muted mb-4">Redirecting you to the course...</p>
            <div class="spinner-border text-success spinner-border-sm" role="status"></div>
        </div>

    </div>

    <?php
        // Get details passed from the Python page
        // If data is missing, use default values to prevent errors
        $course_name = isset($_GET['course']) ? $_GET['course'] : 'Unknown Course';
        $redirect_url = isset($_GET['url']) ? $_GET['url'] : 'courses.php';
    ?>

    <script>
        // 1. Simulate "Connecting to Bank" (2 Seconds)
        setTimeout(function() {
            document.querySelector('#processing-view h5').innerText = "Verifying Credentials...";
        }, 2000);

        // 2. Simulate "Success" (After 4.5 Seconds)
        setTimeout(function() {
            document.getElementById('processing-view').style.display = 'none';
            document.getElementById('success-view').style.display = 'block';
        }, 4500);

        // 3. Redirect to Enrollment Process (After 6.5 Seconds)
        // Note: This sends data to enroll_process.php to ACTUALLY save in database
        setTimeout(function() {
            window.location.href = "enroll_process.php?course=<?php echo urlencode($course_name); ?>&url=<?php echo urlencode($redirect_url); ?>";
        }, 6500);
    </script>

</body>
</html>
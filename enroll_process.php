<?php
session_start();
include 'db.php';

// 1. User Login Check
if(!isset($_SESSION['username'])){
    echo "<script>
            alert('Please login first!');
            window.location.href='login.php';
          </script>";
    exit();
}

// 2. Get Data form URL (Passed from Payment Gateway)
if(isset($_GET['course'])){
    $username = $_SESSION['username'];
    
    // Email Validation
    $email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : $username . "@gmail.com";
    
    $course_name = mysqli_real_escape_string($conn, $_GET['course']);
    
    // Video Link (Idhu Payment Gateway la irundhu varum)
    $redirect_url = isset($_GET['url']) ? $_GET['url'] : 'courses.php';

    // 3. Check Duplicate Enrollment
    $check_sql = "SELECT * FROM course_enrollments WHERE username='$username' AND course_name='$course_name'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if(mysqli_num_rows($check_result) == 0){
        // 4. New Enrollment - Insert into Database
        $insert_sql = "INSERT INTO course_enrollments (username, user_email, course_name) VALUES ('$username', '$email', '$course_name')";
        
        if(mysqli_query($conn, $insert_sql)){
            // SUCCESS: Enrollment Saved -> Go to Video
            echo "<script>
                    alert('Payment Verified! Course Enrolled Successfully. ✅');
                    window.location.href = '$redirect_url'; 
                  </script>";
        } else {
            // Database Error
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // ALREADY ENROLLED: Just go to Video
        echo "<script>
                alert('You have already enrolled in this course!');
                window.location.href = '$redirect_url';
              </script>";
    }
} else {
    // If accessed directly without course data
    header("Location: courses.php");
}
?>
<?php
include 'db.php';

if (isset($_POST['apply'])) {

    $first  = $_POST['first_name'];
    $last   = $_POST['last_name'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];
    $degree = $_POST['degree'];
    $subject = $_POST['subject'];
    $address = $_POST['address'];

    $sql = "INSERT INTO instructor_applications
            (first_name, last_name, email, phone, highest_degree, subject, address)
            VALUES
            ('$first', '$last', '$email', '$phone', '$degree', '$subject', '$address')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Application Submitted'); window.location.href='instructor.html';</script>";
    } else {
        echo "Error";
    }
}
?>

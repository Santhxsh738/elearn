<?php
session_start();
include 'db.php';

if (isset($_POST['upload_course'])) {
    $instructor_name = $_SESSION['username'];
    $instructor_email = $_SESSION['user_email'];
    $course_title = mysqli_real_escape_string($conn, $_POST['course_title']);
    $type = $_POST['upload_type'];
    
    $video_link = "";
    $content = "";
    $course_type = "";

    if ($type == "youtube") {
        $video_link = mysqli_real_escape_string($conn, $_POST['video_link']);
        $course_type = "youtube";
    } 
    elseif ($type == "file") {
        // Video File Logic
        $target_dir = "uploads/videos/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $file_name = time() . "_" . basename($_FILES["video_file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["video_file"]["tmp_name"], $target_file)) {
            $video_link = $target_file;
            $course_type = "file";
        }
    } 
    elseif ($type == "note") {
        // Text Note Logic
        $content = mysqli_real_escape_string($conn, $_POST['course_content']);
        $course_type = "note";
    }

    $sql = "INSERT INTO instructor_courses (instructor_name, instructor_email, course_name, video_link, content, course_type) 
            VALUES ('$instructor_name', '$instructor_email', '$course_title', '$video_link', '$content', '$course_type')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Published Successfully!'); window.location.href='instructor_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
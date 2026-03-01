<?php
include 'db.php';
$id = $_GET['id'];
mysqli_query($conn,"UPDATE instructor_applications SET status='Rejected' WHERE id=$id");
header("Location: admin_instructor_applications.php");
?>

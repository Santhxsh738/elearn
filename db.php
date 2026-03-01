<?php
$conn = mysqli_connect("localhost", "root","", "userdb");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

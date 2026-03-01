<?php
include 'db.php'; // Ensure your database connection is correct here

if (isset($_POST['send_message'])) {
    // Collect and sanitize data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // SQL to insert data
    $sql = "INSERT INTO contact_messages (name, email, subject, message) 
            VALUES ('$name', '$email', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Message saved successfully! We will contact you soon.');
                window.location.href='contact.html'; 
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: contact.html");
    exit();
}
?>
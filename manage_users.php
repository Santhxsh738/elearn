<?php
session_start();
include 'db.php';

// Security: Verify Administrative Access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Logic: Delete Student Account
if (isset($_GET['delete'])) {
    $del_id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id='$del_id'");
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Students | SecretCoder Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-accent: #23a6d5;
            --danger-accent: #e73c7e;
            --bg-light: #f8f9fa;
        }
        
        body { 
            background-color: var(--bg-light); 
            font-family: 'Poppins', sans-serif; 
            color: #333;
        }

        /* Page Header */
        .page-header {
            background: #fff;
            padding: 25px 35px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            margin-bottom: 35px;
            border-left: 5px solid var(--primary-accent);
        }

        /* Modern Spaced Table */
        .custom-table {
            border-collapse: separate;
            border-spacing: 0 12px;
            width: 100%;
        }
        
        .custom-table thead th {
            border: none;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12.5px;
            letter-spacing: 0.8px;
            padding: 0 25px 10px 25px;
        }

        .custom-table tbody tr {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            transition: all 0.2s ease-in-out;
        }

        .custom-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .custom-table td {
            border: none;
            padding: 20px 25px;
            vertical-align: middle;
        }

        .custom-table td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .custom-table td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

        /* Student Info Layout */
        .student-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .student-name { margin: 0; font-weight: 600; color: #2b2b2b; font-size: 15px; }
        .email-text { color: #555; font-size: 14px; font-weight: 500; }

        /* Badges & Buttons */
        .badge-role {
            background-color: #e3f2fd;
            color: #0d6efd;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12.5px;
            letter-spacing: 0.3px;
        }

        .btn-delete {
            background-color: #fff;
            color: var(--danger-accent);
            border: 1px solid #ffdde8;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-delete:hover {
            background-color: var(--danger-accent);
            color: #fff;
            border-color: var(--danger-accent);
        }
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold m-0 text-dark">Student Directory</h4>
            <small class="text-muted">Manage all enrolled student accounts</small>
        </div>
        <a href="admin_home.php" class="btn btn-dark px-4 fw-bold" style="border-radius: 8px;">
            <i class="fa-solid fa-arrow-left me-2"></i> Return to Dashboard
        </a>
    </div>

    <div class="table-responsive px-1">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email Address</th>
                    <th>System Role</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Logic: Exclude 'admin' accounts and 'Approved' instructors
                $sql = "SELECT * FROM users 
                        WHERE role != 'admin' 
                        AND email NOT IN (SELECT email FROM instructor_applications WHERE status='Approved') 
                        ORDER BY id DESC";
                        
                $query = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $username = htmlspecialchars($row['username']);
                        $email = htmlspecialchars($row['email']);
                        // Generate a high-quality professional avatar
                        $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=23a6d5&color=fff&rounded=true&bold=true";
                ?>
                <tr>
                    <td>
                        <div class="student-info">
                            <img src="<?php echo $avatar_url; ?>" alt="User Avatar" class="avatar">
                            <p class="student-name"><?php echo $username; ?></p>
                        </div>
                    </td>
                    
                    <td>
                        <span class="email-text"><i class="fa-solid fa-envelope me-2" style="color:#adb5bd;"></i><?php echo $email; ?></span>
                    </td>
                    
                    <td>
                        <span class="badge-role"><i class="fa-solid fa-user-graduate me-2"></i>Student</span>
                    </td>
                    
                    <td class="text-center">
                        <a href="manage_users.php?delete=<?php echo $row['id']; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('WARNING: Are you sure you want to permanently delete the account for <?php echo $username; ?>?');"
                           title="Delete User Account">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr>
                            <td colspan='4' class='text-center py-5'>
                                <div style='background: #fff; border-radius: 12px; padding: 40px; border: 1px dashed #ced4da;'>
                                    <i class='fa-solid fa-users-slash fs-2 text-muted opacity-50 mb-3'></i>
                                    <h6 class='fw-bold text-dark'>No Student Records Found</h6>
                                    <p class='text-muted small m-0'>The directory is currently empty.</p>
                                </div>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
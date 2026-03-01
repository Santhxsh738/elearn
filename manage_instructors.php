<?php
session_start();
include 'db.php';

// Security: Admin Check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Instructors | SecretCoder Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-accent: #23d5ab; /* Green/Teal for Instructors */
            --danger-accent: #e73c7e;
            --blue-accent: #23a6d5;
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

        /* Instructor Info Layout */
        .instructor-info {
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

        .instructor-name { margin: 0; font-weight: 600; color: #2b2b2b; font-size: 15px; }
        .instructor-id { color: #888; font-size: 12px; font-weight: 500; letter-spacing: 0.5px; }

        /* Contact Details */
        .contact-detail { color: #555; font-size: 13.5px; font-weight: 500; display: block; margin-bottom: 3px; }

        /* Badges & Buttons */
        .badge-status {
            background-color: #e6f8f3;
            color: #0c9c7b;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12.5px;
            letter-spacing: 0.3px;
        }

        .btn-view {
            background-color: #f0f7ff;
            color: var(--blue-accent);
            border: 1px solid #dcebfa;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-view:hover {
            background-color: var(--blue-accent);
            color: #fff;
            border-color: var(--blue-accent);
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
            <h4 class="fw-bold m-0 text-dark"><i class="fa-solid fa-chalkboard-user text-success me-2"></i> Active Instructors</h4>
            <small class="text-muted">Manage approved teaching staff across the platform</small>
        </div>
        <a href="admin_home.php" class="btn btn-dark px-4 fw-bold" style="border-radius: 8px;">
            <i class="fa-solid fa-arrow-left me-2"></i> Return to Dashboard
        </a>
    </div>

    <div class="table-responsive px-1">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Instructor Profile</th>
                    <th>Contact Details</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch Approved Instructors
                $sql = "SELECT * FROM instructor_applications WHERE status='Approved' ORDER BY id DESC";
                $query = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $full_name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                        // Generating a dynamic avatar using the Instructor's Name
                        $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($full_name) . "&background=23d5ab&color=fff&rounded=true&bold=true";
                ?>
                <tr>
                    <td>
                        <div class="instructor-info">
                            <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="avatar">
                            <div>
                                <p class="instructor-name"><?php echo $full_name; ?></p>
                                <span class="instructor-id">ID: #TCHR-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span>
                            </div>
                        </div>
                    </td>
                    
                    <td>
                        <span class="contact-detail"><i class="fa-solid fa-envelope me-2" style="color:#adb5bd; width: 16px;"></i><?php echo htmlspecialchars($row['email']); ?></span>
                        <span class="contact-detail"><i class="fa-solid fa-phone me-2" style="color:#adb5bd; width: 16px;"></i><?php echo htmlspecialchars($row['phone']); ?></span>
                    </td>
                    
                    <td>
                        <span class="badge-status"><i class="fa-solid fa-circle-check me-1"></i> Active</span>
                    </td>
                    
                    <td class="text-center">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <a href="admin_view_courses.php?email=<?php echo urlencode($row['email']); ?>&name=<?php echo urlencode($row['first_name']); ?>" 
                               class="btn-view" title="View Published Courses">
                                <i class="fa-solid fa-video me-2"></i> Courses
                            </a>
                            
                            <a href="delete_instructor.php?id=<?php echo $row['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('WARNING: Are you sure you want to permanently remove <?php echo $full_name; ?> from the instructor panel?');"
                               title="Remove Instructor">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr>
                            <td colspan='4' class='text-center py-5'>
                                <div style='background: #fff; border-radius: 12px; padding: 40px; border: 1px dashed #ced4da;'>
                                    <i class='fa-solid fa-user-tie fs-2 text-muted opacity-50 mb-3'></i>
                                    <h6 class='fw-bold text-dark'>No Active Instructors</h6>
                                    <p class='text-muted small m-0'>There are currently no approved instructors in the system.</p>
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
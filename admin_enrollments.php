<?php
session_start();
require_once 'db.php'; 

// Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM course_enrollments ORDER BY id DESC";
$res = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Enrollments Management | SecretCoder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0f172a;
            --accent: #3b82f6;
            --success: #10b981;
            --bg: #f8fafc;
        }

        body {
            background-color: var(--bg);
            font-family: 'Inter', sans-serif;
            color: #334155;
            padding: 40px 0;
        }

        .container { max-width: 1000px; }

        /* Header Style */
        .page-title-box {
            background: #fff;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Modern List Card */
        .enrollment-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
            display: grid;
            grid-template-columns: 60px 1fr 1fr 180px;
            align-items: center;
            gap: 20px;
            transition: all 0.2s;
        }

        .enrollment-card:hover {
            border-color: var(--accent);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 50px; height: 50px;
            background: #eff6ff;
            color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
            font-size: 20px;
            font-weight: 700;
        }

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .info-value {
            font-weight: 600;
            color: var(--primary);
            font-size: 15px;
        }

        .course-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: var(--success);
        }

        .status-dot {
            width: 8px; height: 8px;
            background: var(--success);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-main { background: var(--primary); color: #fff; }
        .btn-main:hover { background: #1e293b; color: #fff; }

        /* Responsive Mobile View */
        @media (max-width: 768px) {
            .enrollment-card {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 15px;
            }
            .user-avatar { margin: 0 auto; }
            .ticket-right { text-align: center; align-items: center; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="page-title-box">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Course Access Logs</h3>
            <p class="text-muted small mb-0">Total <span class="text-primary fw-bold"><?php echo mysqli_num_rows($res); ?></span> records in database</p>
        </div>
        <a href="admin_home.php" class="btn-action btn-main">
            <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
        </a>
    </div>

    <?php
    if(mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $name = $row['username'] ?? 'Student';
            $email = $row['user_email'] ?? 'No Email';
            $course = $row['course_name'] ?? 'General Course';
            $date = isset($row['enroll_date']) ? date('d M, Y', strtotime($row['enroll_date'])) : '--';
            $initial = substr($name, 0, 1);
    ?>
    
    <div class="enrollment-card">
        <div class="user-avatar"><?php echo strtoupper($initial); ?></div>

        <div>
            <div class="info-label">Student Details</div>
            <div class="info-value"><?php echo htmlspecialchars($name); ?></div>
            <div class="text-muted small"><?php echo htmlspecialchars($email); ?></div>
        </div>

        <div>
            <div class="info-label">Enrolled Course</div>
            <div class="course-badge"><?php echo htmlspecialchars($course); ?></div>
        </div>

        <div class="text-end">
            <div class="status-pill mb-2 justify-content-end">
                <span class="status-dot"></span> Active Enrollment
            </div>
            <div class="info-label">Join Date</div>
            <div class="info-value" style="font-size: 13px;"><?php echo $date; ?></div>
        </div>
    </div>

    <?php
        }
    } else {
    ?>
        <div class="text-center py-5 bg-white rounded-4 border border-dashed">
            <div class="mb-3 text-muted" style="font-size: 40px;"><i class="fa-solid fa-database"></i></div>
            <h5 class="fw-bold">No Records Found</h5>
            <p class="text-muted">Wait for students to enroll in courses.</p>
        </div>
    <?php
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
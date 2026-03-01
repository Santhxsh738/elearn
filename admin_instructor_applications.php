<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Instructor Requests | SecretCoder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #fb873f;
            --primary-grad: linear-gradient(135deg, #fb873f 0%, #ff6b6b 100%);
            --blue-grad: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            --bg-color: #f8f9fc;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Outfit', sans-serif;
            color: #2d3436;
            min-height: 100vh;
        }

        .main-container {
            max-width: 950px;
            margin: 50px auto;
            padding: 20px;
        }

        /* Header Section */
        .page-header {
            background: #fff;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border: 1px solid #f0f0f0;
        }

        .title-section h2 { font-weight: 800; font-size: 28px; letter-spacing: -0.5px; margin: 0; }
        .title-section p { color: #888; margin: 5px 0 0; font-size: 14px; font-weight: 500; }

        .count-badge {
            background: var(--blue-grad);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        /* Application Card */
        .app-card {
            background: #fff;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #f5f5f5;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .app-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.06);
            border-color: #fff;
        }

        .card-content { display: flex; align-items: center; justify-content: space-between; }
        
        .user-left { display: flex; align-items: center; gap: 20px; }

        .avatar-box {
            width: 60px; height: 60px;
            background: var(--primary-grad);
            color: white;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 15px rgba(251, 135, 63, 0.2);
            flex-shrink: 0;
        }

        .user-info h5 { margin: 0; font-weight: 700; font-size: 19px; }
        .user-info .email { margin: 2px 0 8px; color: #999; font-size: 14px; font-weight: 500; display: block; }
        
        .meta-tags { display: flex; gap: 10px; }
        .meta-pill {
            background: #f8f9fa; border: 1px solid #eee;
            color: #555; font-size: 12px; font-weight: 600;
            padding: 4px 10px; border-radius: 8px;
            display: flex; align-items: center; gap: 5px;
        }
        .meta-pill i { color: var(--primary); }

        /* Status Badges */
        .status-badge {
            padding: 6px 14px; border-radius: 50px;
            font-size: 12px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .st-pending { background: #fff7ed; color: #c2410c; }
        .st-approved { background: #ecfdf5; color: #047857; }
        .st-rejected { background: #fef2f2; color: #b91c1c; }

        /* Actions */
        .action-group { display: flex; gap: 10px; align-items: center; }
        
        .btn-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: 0.3s;
            border: none;
            font-size: 16px;
        }

        .btn-approve { background: #ecfdf5; color: #059669; }
        .btn-approve:hover { background: #059669; color: white; }

        .btn-reject { background: #fef2f2; color: #ef4444; }
        .btn-reject:hover { background: #ef4444; color: white; }

        .back-btn {
            display: inline-block; margin-top: 40px; color: #888;
            text-decoration: none; font-weight: 600; transition: 0.3s;
        }
        .back-btn:hover { color: var(--primary); }
    </style>
</head>
<body>

<div class="main-container">

    <div class="page-header">
        <div class="title-section">
            <h2>Instructor Applications</h2>
            <p>Review and manage teaching requests.</p>
        </div>
        <div class="count-badge">
            <i class="fa-solid fa-chalkboard-user me-2"></i>
            <?php 
                $res = mysqli_query($conn, "SELECT * FROM instructor_applications ORDER BY id DESC");
                echo mysqli_num_rows($res); 
            ?>
        </div>
    </div>

    <div class="app-list">
        <?php
        if(mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $status = $row['status'] ?? 'Pending';
                
                // Determine Badge Style
                $badge_class = 'st-pending';
                if ($status == 'Approved') $badge_class = 'st-approved';
                if ($status == 'Rejected') $badge_class = 'st-rejected';
        ?>
        <div class="app-card">
            <div class="card-content">
                <div class="user-left">
                    <div class="avatar-box">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div class="user-info">
                        <h5><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></h5>
                        <span class="email"><i class="fa-regular fa-envelope me-2"></i><?php echo htmlspecialchars($row['email']); ?></span>
                        
                        <div class="meta-tags">
                            <div class="meta-pill">
                                <i class="fa-solid fa-graduation-cap"></i> <?php echo htmlspecialchars($row['highest_degree']); ?>
                            </div>
                            <div class="meta-pill">
                                <i class="fa-solid fa-book-open"></i> <?php echo htmlspecialchars($row['subject']); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-group">
                    <?php if ($status == 'Pending') { ?>
                        <div class="status-badge st-pending me-3">Pending Review</div>
                        
                        <a href="approve.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-approve" title="Approve">
                            <i class="fa-solid fa-check"></i>
                        </a>
                        <a href="reject.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-reject" title="Reject" onclick="return confirm('Reject this application?')">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    <?php } else { ?>
                        <div class="status-badge <?php echo $badge_class; ?>">
                            <?php if($status == 'Approved') echo '<i class="fa-solid fa-circle-check me-1"></i>'; ?>
                            <?php if($status == 'Rejected') echo '<i class="fa-solid fa-circle-xmark me-1"></i>'; ?>
                            <?php echo $status; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<div class='text-center py-5 text-muted'><i class='fa-solid fa-box-open fa-3x mb-3'></i><br>No applications received yet.</div>";
        }
        ?>
    </div>

    <div class="text-center">
        <a href="admin_home.php" class="back-btn">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

</div>

</body>
</html>
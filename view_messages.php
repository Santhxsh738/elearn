<?php
session_start();
require_once 'db.php';

// Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// DELETE LOGIC
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $del_query = "DELETE FROM contact_messages WHERE id = '$delete_id'";
    if (mysqli_query($conn, $del_query)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=archived");
        exit();
    }
}

$query = "SELECT * FROM contact_messages ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $query);
$count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Resolution Center | SecretCoder Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #f1f5f9;
            --glass-white: rgba(255, 255, 255, 0.9);
            --accent-blue: #3b82f6;
            --accent-orange: #fb873f;
            --text-dark: #0f172a;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Outfit', sans-serif;
            color: #475569;
            padding: 40px 0;
        }

        .container { max-width: 950px; }

        /* Modern Dashboard Header */
        .dash-header {
            background: #fff;
            padding: 30px;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .dash-header h2 { font-weight: 800; color: var(--text-dark); margin: 0; font-size: 28px; }

        /* Minimalist Thread Style */
        .message-thread {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            margin-bottom: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .message-thread:hover {
            border-color: var(--accent-blue);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.08);
        }

        details[open] {
            border-color: var(--accent-blue);
            margin-bottom: 24px;
        }

        summary {
            padding: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            list-style: none;
        }
        summary::-webkit-details-marker { display: none; }

        /* Left Content: Avatar & Info */
        .thread-left { display: flex; align-items: center; gap: 18px; flex: 1; }
        
        .user-icon {
            width: 52px; height: 52px;
            background: #f8fafc;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 700; color: var(--accent-blue);
            border: 2px solid #f1f5f9;
        }

        .sender-meta h6 { margin: 0; font-weight: 700; color: var(--text-dark); font-size: 17px; }
        .sender-meta small { color: #94a3b8; font-weight: 500; }

        /* Center Content: Subject Tag */
        .thread-center { flex: 1; padding: 0 20px; }
        .subject-tag {
            background: #eff6ff;
            color: var(--accent-blue);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        /* Right Content: Date & Actions */
        .thread-right { display: flex; align-items: center; gap: 20px; }
        .timestamp { font-size: 13px; font-weight: 600; color: #cbd5e1; }

        /* Expanded Body */
        .expanded-content {
            padding: 0 30px 30px 94px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .msg-text-box {
            background: #fcfdfe;
            border: 1px solid #f1f5f9;
            padding: 24px;
            border-radius: 16px;
            color: #334155;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 24px;
            border-left: 4px solid var(--accent-blue);
        }

        /* Action Buttons */
        .btn-action-group { display: flex; gap: 12px; }
        
        .btn-custom {
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: 0.3s;
        }
        
        .btn-reply { background: var(--text-dark); color: #fff; }
        .btn-reply:hover { background: var(--accent-blue); color: #fff; }

        .btn-archive { background: #fff; border: 1px solid #e2e8f0; color: #ef4444; }
        .btn-archive:hover { background: #fef2f2; border-color: #fca5a5; }

        .back-btn {
            background: #fff; border: 1px solid #e2e8f0; padding: 10px 20px;
            border-radius: 12px; color: var(--text-dark); font-weight: 700; text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="dash-header">
        <div>
            <h2>Support Desk</h2>
            <p class="text-muted small mb-0">Manage and respond to student inquiries.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary rounded-pill px-3 py-2"><?php echo $count; ?> Enquiries</span>
            <a href="admin_home.php" class="back-btn"><i class="fa-solid fa-house me-2"></i> Dashboard</a>
        </div>
    </div>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'archived'): ?>
        <div class="alert alert-info border-0 rounded-4 shadow-sm mb-4 py-3">
            <i class="fa-solid fa-box-archive me-2"></i> Inquiry archived successfully.
        </div>
    <?php endif; ?>

    <div class="list-wrapper">
        <?php
        if($count > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $initial = strtoupper(substr($row['name'], 0, 1));
                $date = date('M d, Y', strtotime($row['submitted_at']));
        ?>
        <div class="message-thread">
            <details>
                <summary>
                    <div class="thread-left">
                        <div class="user-icon"><?php echo $initial; ?></div>
                        <div class="sender-meta">
                            <h6><?php echo htmlspecialchars($row['name']); ?></h6>
                            <small><?php echo htmlspecialchars($row['email']); ?></small>
                        </div>
                    </div>
                    <div class="thread-center">
                        <span class="subject-tag"><?php echo htmlspecialchars($row['subject']); ?></span>
                    </div>
                    <div class="thread-right">
                        <span class="timestamp"><?php echo $date; ?></span>
                        <i class="fa-solid fa-chevron-down text-muted"></i>
                    </div>
                </summary>
                
                <div class="expanded-content">
                    <div class="msg-text-box">
                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                    </div>
                    <div class="btn-action-group">
                        <a href="mailto:<?php echo $row['email']; ?>?subject=Re: <?php echo urlencode($row['subject']); ?>" class="btn-custom btn-reply">
                            <i class="fa-solid fa-paper-plane me-2"></i> Reply via Email
                        </a>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="btn-custom btn-archive" onclick="return confirm('Archive this message?');">
                            <i class="fa-solid fa-box-archive me-2"></i> Close Ticket
                        </a>
                    </div>
                </div>
            </details>
        </div>
        <?php
            }
        } else {
        ?>
            <div class="text-center py-5 bg-white rounded-5 border-dashed shadow-sm">
                <i class="fa-solid fa-circle-check fs-1 text-success opacity-25 mb-3"></i>
                <h5 class="fw-bold">No Pending Tickets</h5>
                <p class="text-muted">You've reached Inbox Zero! All student messages are resolved.</p>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
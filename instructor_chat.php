<?php
session_start();
require_once 'db.php';

// Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor'){
    header("Location: login.php");
    exit;
}

$instructor_email = $_SESSION['user_email'];
$current_student = isset($_GET['student']) ? mysqli_real_escape_string($conn, $_GET['student']) : null;

// Handle Sending Message
if(isset($_POST['send_msg']) && $current_student){
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    // Student name fetch panna logic (Optional)
    $sql = "INSERT INTO chat_messages (instructor_email, student_email, student_name, message, sender) 
            VALUES ('$instructor_email', '$current_student', 'Student', '$msg', 'instructor')";
    mysqli_query($conn, $sql);
    header("Location: instructor_chat.php?student=" . $current_student);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Instructor Connect | SecretCoder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #fb873f;
            --sidebar-bg: #ffffff;
            --chat-bg: #efe7dd;
            --sent-bg: #d9fdd3;
            --received-bg: #ffffff;
            --text-dark: #1c1e21;
        }

        body { 
            background-color: #f0f2f5; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            height: 100vh; 
            overflow: hidden; 
        }

        /* Modern Sidebar */
        .sidebar {
            width: 75px; height: 100vh; position: fixed;
            background: var(--sidebar-bg); text-align: center; padding-top: 25px;
            border-right: 1px solid #e5e7eb; z-index: 1000;
            display: flex; flex-direction: column; align-items: center;
        }
        .sidebar a { 
            width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;
            color: #9ca3af; font-size: 20px; margin-bottom: 20px; border-radius: 12px; transition: 0.3s; 
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active { background: #fff7ed; color: var(--primary); }

        /* Chat Container Layout */
        .chat-container { margin-left: 75px; height: 100vh; display: flex; }

        /* Student List Panel */
        .chat-list {
            width: 360px; background: #fff; border-right: 1px solid #e5e7eb;
            display: flex; flex-direction: column;
        }
        .list-header { padding: 20px; border-bottom: 1px solid #f3f4f6; }
        .list-header h5 { font-weight: 800; color: var(--text-dark); margin: 0; }

        .search-box { padding: 10px 20px; }
        .search-input {
            background: #f0f2f5; border: none; border-radius: 10px; padding: 10px 15px; font-size: 14px; width: 100%;
        }

        .student-item {
            padding: 15px 20px; border-bottom: 1px solid #f9fafb; cursor: pointer;
            display: flex; align-items: center; transition: 0.2s; text-decoration: none; color: #333;
        }
        .student-item:hover, .student-item.active { background: #f8fafc; }
        .student-item.active { border-left: 4px solid var(--primary); }

        .avatar {
            width: 50px; height: 50px; background: linear-gradient(135deg, #fb873f, #e73c7e); 
            border-radius: 15px; display: flex; align-items: center; justify-content: center; 
            font-weight: 800; color: #fff; margin-right: 15px; box-shadow: 0 4px 10px rgba(251, 135, 63, 0.2);
        }
        
        /* Chat Main Area */
        .chat-area { flex: 1; display: flex; flex-direction: column; background: var(--chat-bg); position: relative; }
        
        .chat-header {
            padding: 12px 25px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between;
        }

        .status-dot {
            width: 10px; height: 10px; background: #22c55e; border-radius: 50%;
            display: inline-block; margin-right: 5px; box-shadow: 0 0 8px #22c55e;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

        .messages-box {
            flex: 1; padding: 25px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px;
            background-image: url('https://w0.peakpx.com/wallpaper/580/650/wallpaper-for-whatsapp-background-doodle.jpg');
            background-size: 400px;
        }

        .message {
            max-width: 65%; padding: 10px 15px; border-radius: 12px; font-size: 15px; position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); line-height: 1.5;
        }
        .msg-received { background: var(--received-bg); align-self: flex-start; border-bottom-left-radius: 2px; }
        .msg-sent { background: var(--sent-bg); align-self: flex-end; border-bottom-right-radius: 2px; color: #054a3a; }
        
        .msg-time { font-size: 10px; opacity: 0.6; display: block; text-align: right; margin-top: 4px; font-weight: 600; }

        /* Input Area */
        .chat-input-area {
            padding: 20px 30px; background: #fff; border-top: 1px solid #e5e7eb;
            display: flex; align-items: center; gap: 15px;
        }
        .chat-input {
            flex: 1; padding: 12px 20px; border-radius: 12px; border: 1px solid #e5e7eb; 
            outline: none; transition: 0.3s; background: #f8fafc;
        }
        .chat-input:focus { border-color: var(--primary); background: #fff; }

        .btn-send {
            background: var(--primary); color: white; border: none; width: 48px; height: 48px;
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            transition: 0.3s; box-shadow: 0 4px 12px rgba(251, 135, 63, 0.3);
        }
        .btn-send:hover { transform: scale(1.05); background: #e0702b; }

        .empty-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center; 
            height: 100%; color: #94a3b8; text-align: center; padding: 40px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a href="instructor_dashboard.php"><img src="image/icon.png" width="35"></a>
        <a href="instructor_dashboard.php" title="Dashboard"><i class="fa-solid fa-house"></i></a>
        <a href="#" class="active" title="Chat"><i class="fa-solid fa-comment-dots"></i></a>
        <a href="logout.php" class="text-danger" style="margin-top: auto; margin-bottom: 25px;"><i class="fa-solid fa-power-off"></i></a>
    </div>

    <div class="chat-container">
        
        <div class="chat-list">
            <div class="list-header">
                <h5>Messages</h5>
            </div>
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search students...">
            </div>

            <div style="overflow-y: auto; flex: 1; margin-top: 10px;">
                <?php
                $q = mysqli_query($conn, "SELECT DISTINCT student_email, student_name FROM chat_messages WHERE instructor_email='$instructor_email'");
                
                if(mysqli_num_rows($q) > 0){
                    while($row = mysqli_fetch_assoc($q)){
                        $active = ($current_student == $row['student_email']) ? 'active' : '';
                        $initial = strtoupper(substr($row['student_name'], 0, 1));
                ?>
                <a href="?student=<?php echo $row['student_email']; ?>" class="student-item <?php echo $active; ?>">
                    <div class="avatar"><?php echo $initial; ?></div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark"><?php echo $row['student_name']; ?></div>
                        <small class="text-muted">Tap to view doubts</small>
                    </div>
                    <?php if($active): ?> <i class="fa-solid fa-chevron-right text-muted small"></i> <?php endif; ?>
                </a>
                <?php 
                    }
                } else {
                    echo "<div class='empty-state'><p class='small'>No doubts received.</p></div>";
                }
                ?>
            </div>
        </div>

        <div class="chat-area">
            <?php if($current_student): ?>
                
                <div class="chat-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar" style="width: 42px; height: 42px; margin-right: 12px; border-radius: 12px;">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <div>
                            <div class="fw-bold"><?php echo htmlspecialchars($current_student); ?></div>
                            <div style="font-size: 12px;"><span class="status-dot"></span> <span class="text-success fw-bold">Active Now</span></div>
                        </div>
                    </div>
                    <div class="text-muted">
                        <i class="fa-solid fa-phone me-3" style="cursor:pointer"></i>
                        <i class="fa-solid fa-video me-3" style="cursor:pointer"></i>
                        <i class="fa-solid fa-ellipsis-vertical" style="cursor:pointer"></i>
                    </div>
                </div>

                <div class="messages-box" id="msgBox">
                    <?php
                    $msgs = mysqli_query($conn, "SELECT * FROM chat_messages WHERE instructor_email='$instructor_email' AND student_email='$current_student' ORDER BY created_at ASC");
                    while($m = mysqli_fetch_assoc($msgs)){
                        $css_class = ($m['sender'] == 'instructor') ? 'msg-sent' : 'msg-received';
                    ?>
                    <div class="message <?php echo $css_class; ?>">
                        <?php echo nl2br(htmlspecialchars($m['message'])); ?>
                        <span class="msg-time"><?php echo date('h:i A', strtotime($m['created_at'])); ?></span>
                    </div>
                    <?php } ?>
                </div>

                <div class="chat-input-area">
                    <button class="btn btn-light rounded-pill border-0 text-muted"><i class="fa-solid fa-plus"></i></button>
                    <form method="post" class="flex-grow-1 d-flex gap-2">
                        <input type="text" name="message" class="chat-input" placeholder="Type your reply here..." required autocomplete="off">
                        <button type="submit" name="send_msg" class="btn-send"><i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </div>

            <?php else: ?>
                
                <div class="empty-state">
                    <div style="background: #fff; padding: 40px; border-radius: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                        <img src="image/icon.png" width="80" class="mb-4" style="filter: drop-shadow(0 5px 15px rgba(251,135,63,0.3));">
                        <h4 class="fw-bold text-dark">Select a Conversation</h4>
                        <p class="text-muted mb-0">Manage student doubts and provide<br>quick resolutions instantly.</p>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <script>
        const msgBox = document.getElementById("msgBox");
        if(msgBox){ 
            msgBox.scrollTop = msgBox.scrollHeight; 
        }
    </script>

</body>
</html>
<?php
session_start();
include 'db.php'; 

// 1. Session Check
if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}

// 2. Role Redirects
if($_SESSION['role'] == 'instructor'){
    header("Location: instructor_dashboard.php");
    exit();
}
if($_SESSION['role'] == 'admin'){
    header("Location: admin_home.php");
    exit();
}

$student_email = $_SESSION['user_email'];
$student_name = $_SESSION['username'];
$username = $_SESSION['username'];
$current_instructor = isset($_GET['instructor']) ? $_GET['instructor'] : null;

// Handle Human Instructor Messages
if(isset($_POST['send_msg']) && $current_instructor && $current_instructor != 'ai'){
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    $sql = "INSERT INTO chat_messages (instructor_email, student_email, student_name, message, sender) 
            VALUES ('$current_instructor', '$student_email', '$student_name', '$msg', 'student')";
    if(mysqli_query($conn, $sql)){
        header("Location: student_doubts.php?instructor=" . $current_instructor);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Secret Coder | Master Tech Skills</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/icon.png" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --pink: #e73c7e;
            --orange: #fb873f;
            --blue: #23a6d5;
            --teal: #23d5ab;
            --bg-color: #f4f7f6;
            --gradient-user: linear-gradient(135deg, #fb873f, #e73c7e);
            --gradient-ai: linear-gradient(135deg, #23a6d5, #23d5ab);
        }

        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); color: #333; margin: 0; overflow: hidden; }

        /* Navbar */
        .navbar { background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 15px 0; z-index: 1000; }
        .nav-link { color: #555 !important; font-weight: 600; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: var(--pink) !important; }
        .btn-gradient { background: var(--gradient-user); color: white; border: none; padding: 10px 25px; border-radius: 50px; font-weight: 600; transition: 0.3s; }
        .btn-gradient:hover { transform: translateY(-3px); color: white; box-shadow: 0 10px 20px rgba(231, 60, 126, 0.3); }

        /* Chat Layout */
        .chat-wrapper { display: flex; height: calc(100vh - 76px); }
        
        /* Sidebar */
        .sidebar { width: 320px; background: #fff; box-shadow: 5px 0 20px rgba(0,0,0,0.03); display: flex; flex-direction: column; z-index: 10; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #eee; text-align: center; }
        .instructor-list { flex: 1; overflow-y: auto; }
        .instructor-item { padding: 15px 20px; border-bottom: 1px solid #f9f9f9; display: flex; align-items: center; text-decoration: none; color: #555; transition: 0.3s; }
        .instructor-item:hover { background: #f8f9fa; transform: translateX(5px); }
        .instructor-item.active { background: rgba(231, 60, 126, 0.05); border-left: 4px solid var(--pink); color: var(--pink); font-weight: 600; }
        .avatar-circle { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; margin-right: 15px; font-size: 18px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

        /* Chat Area */
        .chat-area { flex: 1; display: flex; flex-direction: column; background: var(--bg-color); position: relative; }
        .chat-area-header { padding: 15px 30px; background: #fff; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .messages-box { flex: 1; padding: 30px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        
        /* Message Bubbles */
        .message { max-width: 75%; padding: 15px 22px; border-radius: 20px; font-size: 14.5px; line-height: 1.6; animation: slideUp 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .msg-sent { background: var(--gradient-user); color: white; align-self: flex-end; border-bottom-right-radius: 5px; }
        .msg-ai { background: #fff; color: #333; align-self: flex-start; border-bottom-left-radius: 5px; border-left: 5px solid var(--blue); }
        
        /* Input Area */
        .typing-indicator { font-size: 13px; color: var(--blue); display: none; margin-bottom: 10px; font-weight: 600; padding-left: 10px; }
        .chat-input-container { padding: 20px 30px; background: #fff; border-top: 1px solid #eee; box-shadow: 0 -5px 20px rgba(0,0,0,0.02); }
        .custom-input-group { display: flex; background: var(--bg-color); padding: 8px; border-radius: 50px; align-items: center; border: 1px solid transparent; transition: 0.3s; }
        .custom-input-group:focus-within { background: #fff; border-color: var(--pink); box-shadow: 0 0 15px rgba(231, 60, 126, 0.1); }
        .chat-input { flex: 1; border: none; background: transparent; padding: 10px 20px; outline: none; font-size: 15px; }
        .btn-circle { background: var(--gradient-user); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; transition: 0.3s; }
        .btn-circle:hover { transform: scale(1.08); box-shadow: 0 5px 15px rgba(231, 60, 126, 0.4); }
        
        /* Code Blocks */
        pre { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 12px; margin: 15px 0; font-family: 'Courier New', monospace; font-size: 13.5px; overflow-x: auto; border-left: 4px solid var(--teal); }
        code { font-family: 'Courier New', monospace; background: #f0f0f0; padding: 2px 6px; border-radius: 4px; color: var(--pink); }
        pre code { background: transparent; color: inherit; padding: 0; }
        b { color: var(--blue); }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a href="student_dashboard.php" class="navbar-brand d-flex align-items-center">
                <img src="image/icon.png" height="40" class="me-2" >
                <h3 class="m-0 fw-bold" style="color:#333;">Secret<span style="color:#e73c7e;">Coder</span></h3>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a href="home.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="courses.php" class="nav-link">Courses</a></li>
                    <li class="nav-item"><a href="student_doubts.php" class="nav-link active">Doubts</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    
                    <li class="nav-item ms-3">
                        <a href="profile.php" class="d-flex align-items-center text-decoration-none">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=e73c7e&color=fff&rounded=true" width="40" class="shadow-sm">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="chat-wrapper">
        
        <div class="sidebar">
            <div class="sidebar-header">
                <h6 class="text-uppercase fw-bold text-muted mb-0 letter-spacing-2">Support Tutors</h6>
            </div>
            <div class="instructor-list">
                <a href="?instructor=ai" class="instructor-item <?php echo ($current_instructor == 'ai') ? 'active' : ''; ?>">
                    <div class="avatar-circle" style="background: var(--gradient-ai);"><i class="fa fa-robot"></i></div>
                    <div><div class="fw-bold">SecretAI Tutor</div><small style="color:var(--blue); font-weight:600;">Live Assistant</small></div>
                </a>
                
                <div class="px-4 py-3 bg-light small fw-bold text-muted text-uppercase mt-2 border-top border-bottom">Human Instructors</div>
                
                <?php
                $q = mysqli_query($conn, "SELECT * FROM instructor_applications WHERE status='Approved'");
                while($row = mysqli_fetch_assoc($q)){
                    $active = ($current_instructor == $row['email']) ? 'active' : '';
                    echo "<a href='?instructor=".$row['email']."' class='instructor-item $active'>
                            <div class='avatar-circle' style='background: var(--gradient-user);'>".strtoupper(substr($row['first_name'],0,1))."</div>
                            <div><div class='fw-bold text-dark'>".$row['first_name']."</div><small class='text-muted'>Instructor</small></div>
                          </a>";
                }
                ?>
            </div>
        </div>

        <div class="chat-area">
            <?php if($current_instructor == 'ai'): ?>
                <div class="chat-area-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3" style="background: var(--gradient-ai); width:40px; height:40px; font-size:16px;"><i class="fa fa-bolt"></i></div>
                        <div><h5 class="m-0 fw-bold" style="color:var(--blue);">SecretAI Support</h5><small class="text-muted">Powered by Gemini 2.5 Flash</small></div>
                    </div>
                    <span class="badge rounded-pill bg-success px-3 py-2 shadow-sm"><i class="fa fa-circle me-1" style="font-size:8px;"></i> ONLINE</span>
                </div>
                
                <div class="messages-box" id="aiChatBox">
                    <div class="message msg-ai">
                        Welcome to your Dashboard support, <b><?php echo $student_name; ?></b>! ✨<br>I am your AI coding mentor. Don't let errors stop your learning—ask me any coding doubt, and I will explain it with examples.
                    </div>
                    <div id="aiTyping" class="typing-indicator"><i class="fa fa-circle-notch fa-spin me-2"></i> Analyzing your request...</div>
                </div>

                <div class="chat-input-container">
                    <div class="custom-input-group shadow-sm">
                        <input type="text" id="aiInput" class="chat-input" placeholder="Type your doubt (e.g., How to connect PHP to MySQL?)" autocomplete="off">
                        <button onclick="askAI()" class="btn-circle"><i class="fa fa-paper-plane"></i></button>
                    </div>
                </div>

                <script>
                    const GEMINI_API_KEY = "AIzaSyBCh6gxaTk5J8kVmOS7x0iNr4qQalKC-ms"; 

                    async function askAI() {
                        const input = document.getElementById('aiInput');
                        const text = input.value.trim();
                        
                        if(!text) return;

                        appendMsg(text, 'msg-sent');
                        input.value = '';
                        input.focus();
                        
                        const typing = document.getElementById('aiTyping');
                        typing.style.display = 'block';
                        scrollToBottom();

                        try {
                            const url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${GEMINI_API_KEY}`;
                            
                            const response = await fetch(url, {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({
                                    contents: [{ parts: [{ text: "You are a professional coding mentor for Secret Coder platform. Answer this technically but clearly, with code blocks where helpful: " + text }] }]
                                })
                            });

                            const data = await response.json();
                            
                            if(data.error) throw new Error(data.error.message);

                            const aiResponse = data.candidates[0].content.parts[0].text;
                            typing.style.display = 'none';
                            appendMsg(formatOutput(aiResponse), 'msg-ai');

                        } catch (err) {
                            typing.style.display = 'none';
                            appendMsg("<b style='color:#e73c7e;'><i class='fa fa-exclamation-circle me-1'></i> System Error:</b> " + err.message + "<br><small>Please check your internet connection.</small>", 'msg-ai');
                        }
                        scrollToBottom();
                    }

                    function appendMsg(text, className) {
                        const box = document.getElementById('aiChatBox');
                        const div = document.createElement('div');
                        div.className = `message ${className}`;
                        div.innerHTML = text;
                        box.insertBefore(div, document.getElementById('aiTyping'));
                    }

                    function scrollToBottom() {
                        const box = document.getElementById('aiChatBox');
                        box.scrollTop = box.scrollHeight;
                    }

                    function formatOutput(t) {
                        return t.replace(/```([\s\S]*?)```/g, '<pre>$1</pre>')
                                .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')
                                .replace(/\n/g, '<br>');
                    }

                    document.getElementById('aiInput').addEventListener('keypress', (e) => {
                        if(e.key === 'Enter') askAI();
                    });
                </script>

            <?php elseif($current_instructor): ?>
                <div class="chat-area-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3" style="background:var(--gradient-user); width:40px; height:40px; font-size:16px;">
                            <?php echo strtoupper(substr($current_instructor,0,1)); ?>
                        </div>
                        <div><h5 class="m-0 fw-bold text-dark"><?php echo $current_instructor; ?></h5><small class="text-muted">Live Support</small></div>
                    </div>
                </div>
                
                <div class="messages-box" id="msgBox">
                    <?php
                    $msgs = mysqli_query($conn, "SELECT * FROM chat_messages WHERE instructor_email='$current_instructor' AND student_email='$student_email' ORDER BY created_at ASC");
                    while($m = mysqli_fetch_assoc($msgs)){
                        $class = ($m['sender'] == 'student') ? 'msg-sent' : 'msg-ai';
                        echo "<div class='message $class'>".nl2br($m['message'])."</div>";
                    }
                    ?>
                </div>
                
                <div class="chat-input-container">
                    <form method="post" class="custom-input-group shadow-sm">
                        <input type="text" name="message" class="chat-input" placeholder="Message instructor..." required>
                        <button type="submit" name="send_msg" class="btn-circle"><i class="fa fa-paper-plane"></i></button>
                    </form>
                </div>
                <script>document.getElementById('msgBox').scrollTop = document.getElementById('msgBox').scrollHeight;</script>
            <?php else: ?>
                <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                    <div style="width:120px; height:120px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 10px 30px rgba(0,0,0,0.05); margin-bottom:20px;">
                        <i class="fa fa-comments" style="font-size:60px; color:var(--pink); opacity:0.8;"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Support Hub</h3>
                    <p>Select <b style="color:var(--blue);">SecretAI</b> or a specific Instructor from the sidebar to begin.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
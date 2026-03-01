<?php
session_start();
require_once 'db.php';

// Security Check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor'){
    header("Location: login.php");
    exit;
}

$instructor_name = $_SESSION['username'];
$instructor_email = $_SESSION['user_email'];

// Count Total Uploads
$uploads_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM instructor_courses WHERE instructor_email='$instructor_email'");
$total_uploads = mysqli_fetch_assoc($uploads_q)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Instructor Console | SecretCoder</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #fb873f;
            --brand-dark: #0f172a;
            --bg-canvas: #f8fafc;
            --sidebar-width: 260px;
        }

        body {
            background-color: var(--bg-canvas);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        /* Sidebar - Light Mode Professional */
        .sidebar {
            width: var(--sidebar-width); height: 100vh; position: fixed;
            background: #ffffff; padding: 30px 20px; z-index: 1000;
            border-right: 1px solid #e2e8f0;
        }

        .brand-box {
            display: flex; align-items: center; gap: 12px;
            padding: 0 15px 40px; text-decoration: none; color: var(--brand-dark);
        }

        .nav-link {
            display: flex; align-items: center; gap: 15px;
            color: #64748b; padding: 14px 20px; border-radius: 12px;
            margin-bottom: 8px; font-weight: 600; transition: 0.3s;
            text-decoration: none;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(251, 135, 63, 0.08);
            color: var(--brand-primary);
        }

        .main-content { margin-left: var(--sidebar-width); padding: 40px; }

        /* Modern White Cards */
        .premium-card {
            background: #ffffff; border-radius: 20px; padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            transition: 0.3s;
        }

        .stat-card-main {
            background: linear-gradient(135deg, #fff 0%, #fffbf8 100%);
            border-left: 5px solid var(--brand-primary);
        }

        /* Custom Tabs */
        .nav-tabs-custom {
            background: #f1f5f9; padding: 5px; border-radius: 12px;
            display: flex; border: none; margin-bottom: 25px;
        }
        .nav-tabs-custom .nav-link {
            flex: 1; text-align: center; border: none;
            padding: 10px; border-radius: 9px; color: #64748b; font-weight: 600; font-size: 13px;
        }
        .nav-tabs-custom .nav-link.active {
            background: #fff; color: var(--brand-primary);
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .form-control {
            background: #f8fafc; border: 1px solid #e2e8f0;
            padding: 12px; border-radius: 10px; font-size: 14px;
        }
        .form-control:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(251, 135, 63, 0.1);
            background: #fff;
        }

        .btn-publish {
            background: var(--brand-primary); color: #fff;
            padding: 13px; border-radius: 10px; font-weight: 700;
            width: 100%; border: none; transition: 0.3s;
        }
        .btn-publish:hover { background: #e0702b; transform: translateY(-2px); }

        .recent-table th { background: #f8fafc; color: #94a3b8; font-size: 11px; text-transform: uppercase; padding: 15px; }
        .recent-table td { padding: 16px 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

        .stat-icon {
            width: 55px; height: 55px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; background: rgba(251, 135, 63, 0.1); color: var(--brand-primary);
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <a href="#" class="brand-box">
            <img src="image/icon.png" width="35">
            <span class="fs-4 fw-bold">Secret<span style="color:var(--brand-primary);">Coder</span></span>
        </a>
        
        <nav class="nav flex-column">
            <a href="instructor_dashboard.php" class="nav-link active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="instructor_chat.php" class="nav-link"><i class="fa-solid fa-comment-dots"></i> Doubts & Chat</a>
            
            <div style="margin-top: 60px;">
                <a href="logout.php" class="nav-link text-danger"><i class="fa-solid fa-power-off"></i> Logout</a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold m-0">Hi, <?php echo $instructor_name; ?>!</h2>
                <p class="text-muted mb-0">Monitor your uploads and student communication.</p>
            </div>
            <img src="https://ui-avatars.com/api/?name=<?php echo $instructor_name; ?>&background=fb873f&color=fff&bold=true" class="rounded-circle border" width="48">
        </header>

        <div class="row mb-5">
            <div class="col-md-5">
                <div class="premium-card stat-card-main d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Content</div>
                        <h1 class="fw-bold mb-0 mt-1"><?php echo $total_uploads; ?></h1>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-photo-film"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="premium-card">
                    <h5 class="fw-bold mb-4">Post New Content</h5>
                    
                    <nav class="nav nav-tabs-custom" id="nav-tab" role="tablist">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-yt">YouTube</button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-file">Local File</button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-note">Quick Note</button>
                    </nav>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-yt">
                            <form action="course_save.php" method="POST">
                                <input type="hidden" name="upload_type" value="youtube">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Title</label>
                                    <input type="text" name="course_title" class="form-control" placeholder="E.g. Python Intro" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">YouTube URL</label>
                                    <input type="url" name="video_link" class="form-control" placeholder="Paste link here" required>
                                </div>
                                <button type="submit" name="upload_course" class="btn-publish">Publish Video</button>
                            </form>
                        </div>
                        
                        <div class="tab-pane fade" id="tab-file">
                            <form action="course_save.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="upload_type" value="file">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Video Name</label>
                                    <input type="text" name="course_title" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">Select MP4</label>
                                    <input type="file" name="video_file" class="form-control" accept="video/mp4" required>
                                </div>
                                <button type="submit" name="upload_course" class="btn-publish">Start Upload</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="tab-note">
                            <form action="course_save.php" method="POST">
                                <input type="hidden" name="upload_type" value="note">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">Topic</label>
                                    <input type="text" name="course_title" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">Note Content</label>
                                    <textarea name="course_content" class="form-control" rows="4" placeholder="Type notes..." required></textarea>
                                </div>
                                <button type="submit" name="upload_course" class="btn-publish">Save Note</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="premium-card h-100">
                    <h5 class="fw-bold mb-4">Content Library</h5>
                    <div class="table-responsive">
                        <table class="table recent-table align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Format</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM instructor_courses WHERE instructor_email='$instructor_email' ORDER BY id DESC LIMIT 7");
                                if(mysqli_num_rows($res) > 0) {
                                    while($row = mysqli_fetch_assoc($res)){
                                        $type = $row['course_type'] ?? 'youtube';
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['course_name']); ?></div>
                                        <small class="text-muted"><?php echo date('d M, Y', strtotime($row['upload_date'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 10px; font-weight: 700;">
                                            <?php echo strtoupper($type); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo ($type == 'note') ? 'view_note.php?id='.$row['id'] : $row['video_link']; ?>" target="_blank" class="btn btn-sm btn-dark px-3 rounded-pill">
                                            Open
                                        </a>
                                    </td>
                                </tr>
                                <?php } } else { ?>
                                    <tr><td colspan="3" class="text-center py-5 text-muted small">No content found.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
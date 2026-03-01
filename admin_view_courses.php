<?php
session_start();
require_once 'db.php';

// Security: Admin Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get Instructor Details from URL safely
if (isset($_GET['email']) && isset($_GET['name'])) {
    $inst_email = $_GET['email']; 
    $inst_name = htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
    
    // Generate avatar for the header
    $avatar_url = "https://ui-avatars.com/api/?name=" . urlencode($inst_name) . "&background=23d5ab&color=fff&rounded=true&bold=true";
} else {
    header("Location: manage_instructors.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Instructor Courses | SecretCoder Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-accent: #23d5ab;
            --blue-accent: #23a6d5;
            --danger-accent: #e73c7e;
            --bg-light: #f4f7f6;
        }
        
        body { 
            background-color: var(--bg-light); 
            font-family: 'Poppins', sans-serif; 
            color: #2b2b2b;
        }

        /* Page Header */
        .page-header {
            background: #fff;
            padding: 25px 35px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            margin-bottom: 30px;
            border-left: 5px solid var(--primary-accent);
        }

        .header-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(35, 213, 171, 0.2);
        }

        /* Search Bar */
        .search-container {
            position: relative;
            margin-bottom: 30px;
        }
        
        .search-container i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        .search-input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            font-size: 15px;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-accent);
            box-shadow: 0 4px 20px rgba(35, 213, 171, 0.15);
        }

        /* Premium Course Cards */
        .course-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        /* Video Thumbnail Area */
        .video-thumb {
            background: linear-gradient(135deg, #1f2833, #0b0c10);
            height: 190px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .video-thumb::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.2);
            transition: 0.3s;
        }

        .course-card:hover .video-thumb::after {
            background: rgba(0,0,0,0.4);
        }

        .video-thumb i.play-btn {
            font-size: 3.5rem;
            color: rgba(255, 255, 255, 0.9);
            z-index: 2;
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .course-card:hover .video-thumb i.play-btn {
            transform: scale(1.2);
            color: #fff;
        }

        .upload-date {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            z-index: 2;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Badges */
        .platform-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 11.5px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 2;
        }

        .badge-yt { background: #fff; color: #ff0000; }
        .badge-local { background: #fff; color: var(--blue-accent); }

        /* Card Content */
        .card-content { padding: 25px 20px; flex: 1; }
        .course-title { font-size: 17px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; line-height: 1.4; }
        
        .btn-watch {
            background-color: #fff;
            color: #333;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            display: block;
            text-align: center;
            text-decoration: none;
            margin-top: 15px;
        }

        .btn-watch:hover {
            background-color: var(--blue-accent);
            color: #fff;
            border-color: var(--blue-accent);
            box-shadow: 0 5px 15px rgba(35, 166, 213, 0.2);
        }
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="<?php echo $avatar_url; ?>" alt="Instructor" class="header-avatar">
            <div>
                <h4 class="fw-bold m-0 text-dark">Course Repository</h4>
                <small class="text-muted">Instructor: <span style="color: var(--primary-accent); font-weight: 700;"><?php echo $inst_name; ?></span></small>
            </div>
        </div>
        <a href="manage_instructors.php" class="btn btn-dark px-4 fw-bold" style="border-radius: 8px;">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Instructors
        </a>
    </div>

    <div class="row mb-2">
        <div class="col-md-6">
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="courseSearch" class="search-input" placeholder="Search courses by name...">
            </div>
        </div>
    </div>

    <div class="row g-4" id="courseGrid">
        <?php
        // Secure Database Call
        $stmt = $conn->prepare("SELECT * FROM instructor_courses WHERE instructor_email = ? ORDER BY id DESC");
        $stmt->bind_param("s", $inst_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $video_link = htmlspecialchars($row['video_link'], ENT_QUOTES, 'UTF-8');
                $course_name = htmlspecialchars($row['course_name'], ENT_QUOTES, 'UTF-8');
                $is_yt = (strpos(strtolower($video_link), 'youtube.com') !== false || strpos(strtolower($video_link), 'youtu.be') !== false);
                $formatted_date = date('d M Y', strtotime($row['upload_date']));
        ?>
        
        <div class="col-md-6 col-lg-4 course-item">
            <div class="course-card">
                
                <div class="video-thumb">
                    <?php if ($is_yt): ?>
                        <span class="platform-badge badge-yt"><i class="fa-brands fa-youtube me-1"></i> YouTube</span>
                    <?php else: ?>
                        <span class="platform-badge badge-local"><i class="fa-solid fa-cloud-arrow-down me-1"></i> Local Media</span>
                    <?php endif; ?>
                    
                    <i class="fa-solid fa-circle-play play-btn"></i>
                    <span class="upload-date"><i class="fa-regular fa-calendar me-1"></i> <?php echo $formatted_date; ?></span>
                </div>
                
                <div class="card-content">
                    <h5 class="course-title title-text"><?php echo $course_name; ?></h5>
                    <p class="text-muted small mb-0"><i class="fa-solid fa-id-badge me-1 opacity-50"></i> <?php echo htmlspecialchars($row['instructor_email'], ENT_QUOTES, 'UTF-8'); ?></p>
                    
                    <a href="<?php echo $video_link; ?>" target="_blank" class="btn-watch w-100">
                        <i class="fa-solid fa-play me-2"></i> Watch Course Video
                    </a>
                </div>
                
            </div>
        </div>
        
        <?php 
            }
        } else {
        ?>
            <div class="col-12 text-center py-5">
                <div style="background: #fff; border-radius: 16px; padding: 60px; border: 1px dashed #ced4da; max-width: 600px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                    <i class="fa-solid fa-photo-film fs-1 text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">No Courses Published Yet</h5>
                    <p class="text-muted small m-0">Instructor <b><?php echo $inst_name; ?></b> hasn't uploaded any video content to their portfolio.</p>
                </div>
            </div>
        <?php
        }
        $stmt->close(); 
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('courseSearch').addEventListener('keyup', function() {
        let searchQuery = this.value.toLowerCase();
        let courseItems = document.querySelectorAll('.course-item');
        
        courseItems.forEach(function(item) {
            let title = item.querySelector('.title-text').innerText.toLowerCase();
            if (title.includes(searchQuery)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
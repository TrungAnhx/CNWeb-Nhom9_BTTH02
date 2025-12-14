<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo bài học mới - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/instructor.css">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
    <!-- Header -->
    <header class="instructor-header">
        <div class="header-container">
            <div class="header-left">
                <h1 class="logo">📝 Tạo bài học mới</h1>
            </div>
            <div class="header-right">
                <span class="user-info">
                    👤 <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?>
                </span>
                <a href="<?php echo BASE_URL; ?>/logout.php" class="btn-logout">Đăng xuất</a>
            </div>
        </div>
    </header>

    <div class="instructor-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>/?controller=course&action=dashboard">Dashboard</a>
            <span> / </span>
            <a href="<?php echo BASE_URL; ?>/?controller=lesson&action=manage&course_id=<?php echo $course['id']; ?>">
                <?php echo htmlspecialchars($course['title']); ?>
            </a>
            <span> / </span>
            <span>Tạo bài học mới</span>
        </div>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <strong>❌ Có lỗi xảy ra:</strong>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Form Container -->
        <div class="form-container">
            <form action="<?php echo BASE_URL; ?>/?controller=lesson&action=store" method="POST" class="lesson-form">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                
                <!-- Section: Thông tin cơ bản -->
                <div class="form-section">
                    <h2 class="section-title">📝 Thông tin bài học</h2>
                    
                    <div class="form-group">
                        <label for="title" class="required">Tiêu đề bài học</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               placeholder="VD: Giới thiệu về PHP"
                               value="<?php echo htmlspecialchars($_SESSION['old_data']['title'] ?? ''); ?>"
                               required>
                        <small class="form-hint">Tiêu đề ngắn gọn, dễ hiểu</small>
                    </div>

                    <div class="form-group">
                        <label for="content">Nội dung bài học</label>
                        <textarea id="content" 
                                  name="content" 
                                  class="form-control" 
                                  rows="10"><?php echo htmlspecialchars($_SESSION['old_data']['content'] ?? ''); ?></textarea>
                        <small class="form-hint">Mô tả chi tiết nội dung, yêu cầu, kiến thức cần có...</small>
                    </div>
                </div>

                <!-- Section: Video -->
                <div class="form-section">
                    <h2 class="section-title">🎥 Video bài giảng</h2>
                    
                    <div class="form-group">
                        <label for="video_url">URL Video (YouTube/Vimeo)</label>
                        <input type="text" 
                               id="video_url" 
                               name="video_url" 
                               class="form-control" 
                               placeholder="https://www.youtube.com/watch?v=..."
                               value="<?php echo htmlspecialchars($_SESSION['old_data']['video_url'] ?? ''); ?>">
                        <small class="form-hint">Để trống nếu chưa có video</small>
                    </div>
                </div>

                <!-- Section: Thứ tự -->
                <div class="form-section">
                    <h2 class="section-title">🔢 Thứ tự bài học</h2>
                    
                    <div class="form-group">
                        <label for="order_num">Thứ tự</label>
                        <input type="number" 
                               id="order_num" 
                               name="order_num" 
                               class="form-control" 
                               placeholder="1"
                               min="1"
                               value="<?php echo htmlspecialchars($_SESSION['old_data']['order_num'] ?? ''); ?>">
                        <small class="form-hint">Để trống để tự động đánh số theo thứ tự</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        ✅ Tạo bài học
                    </button>
                    <a href="<?php echo BASE_URL; ?>/?controller=lesson&action=manage&course_id=<?php echo $course['id']; ?>" class="btn btn-secondary">
                        ❌ Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 400,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; font-size: 14px; }'
        });
    </script>

    <style>
        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .form-section:last-of-type {
            border-bottom: none;
        }

        .section-title {
            color: #2d2f31;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #1c1d1f;
            margin-bottom: 8px;
            font-size: 14px;
        }

        label.required::after {
            content: " *";
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d7dc;
            border-radius: 4px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #5624d0;
            box-shadow: 0 0 0 3px rgba(86, 36, 208, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            font-family: inherit;
        }

        .form-hint {
            display: block;
            color: #6a6f73;
            font-size: 13px;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #5624d0;
            color: white;
        }

        .btn-primary:hover {
            background: #401b9c;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 8px;
            font-size: 15px;
        }

        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert ul {
            margin: 10px 0 0 20px;
        }

        .breadcrumb {
            padding: 15px 0;
            color: #6a6f73;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #5624d0;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }
    </style>
</body>
</html>
<?php unset($_SESSION['old_data']); ?>
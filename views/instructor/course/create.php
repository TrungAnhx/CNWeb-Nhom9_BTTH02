<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo khóa học mới - Instructor</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/instructor.css">
</head>
<body>
    <!-- Header -->
    <header class="instructor-header">
        <div class="header-container">
            <div class="header-left">
                <h1 class="logo">📚 Tạo khóa học mới</h1>
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
            <span>Tạo khóa học mới</span>
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
            <form action="<?php echo BASE_URL; ?>/?controller=course&action=store" method="POST" enctype="multipart/form-data" class="course-form">
                
                <!-- Section: Thông tin cơ bản -->
                <div class="form-section">
                    <h2 class="section-title">📝 Thông tin cơ bản</h2>
                    
                    <div class="form-group">
                        <label for="title" class="required">Tiêu đề khóa học</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-control" 
                               placeholder="VD: Lập trình Web với PHP và MySQL từ A-Z"
                               value="<?php echo htmlspecialchars($_SESSION['old_data']['title'] ?? ''); ?>"
                               required>
                        <small class="form-hint">Tiêu đề hấp dẫn sẽ thu hút nhiều học viên hơn</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả khóa học</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-control" 
                                  rows="6"
                                  placeholder="Mô tả chi tiết về khóa học: nội dung, đối tượng học viên, kiến thức đạt được..."><?php echo htmlspecialchars($_SESSION['old_data']['description'] ?? ''); ?></textarea>
                        <small class="form-hint">Mô tả càng chi tiết, học viên càng dễ quyết định đăng ký</small>
                    </div>
                </div>

                <!-- Section: Phân loại & Cấp độ -->
                <div class="form-section">
                    <h2 class="section-title">📂 Phân loại & Cấp độ</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id" class="required">Danh mục</label>
                            <select id="category_id" name="category_id" class="form-control" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"
                                            <?php echo (isset($_SESSION['old_data']['category_id']) && $_SESSION['old_data']['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="level" class="required">Cấp độ</label>
                            <select id="level" name="level" class="form-control" required>
                                <option value="">-- Chọn cấp độ --</option>
                                <option value="Beginner" <?php echo (isset($_SESSION['old_data']['level']) && $_SESSION['old_data']['level'] == 'Beginner') ? 'selected' : ''; ?>>
                                    🌱 Beginner (Người mới bắt đầu)
                                </option>
                                <option value="Intermediate" <?php echo (isset($_SESSION['old_data']['level']) && $_SESSION['old_data']['level'] == 'Intermediate') ? 'selected' : ''; ?>>
                                    📈 Intermediate (Trung cấp)
                                </option>
                                <option value="Advanced" <?php echo (isset($_SESSION['old_data']['level']) && $_SESSION['old_data']['level'] == 'Advanced') ? 'selected' : ''; ?>>
                                    🚀 Advanced (Nâng cao)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Giá & Thời lượng -->
                <div class="form-section">
                    <h2 class="section-title">💰 Giá & Thời lượng</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Giá (VNĐ)</label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   class="form-control" 
                                   placeholder="0"
                                   min="0"
                                   step="1000"
                                   value="<?php echo htmlspecialchars($_SESSION['old_data']['price'] ?? '0'); ?>">
                            <small class="form-hint">Nhập 0 nếu khóa học miễn phí</small>
                        </div>

                        <div class="form-group">
                            <label for="duration_weeks">Thời lượng (tuần)</label>
                            <input type="number" 
                                   id="duration_weeks" 
                                   name="duration_weeks" 
                                   class="form-control" 
                                   placeholder="4"
                                   min="1"
                                   value="<?php echo htmlspecialchars($_SESSION['old_data']['duration_weeks'] ?? ''); ?>">
                            <small class="form-hint">Ước tính thời gian hoàn thành khóa học</small>
                        </div>
                    </div>
                </div>

                <!-- Section: Ảnh thumbnail -->
                <div class="form-section">
                    <h2 class="section-title">🖼️ Ảnh đại diện khóa học</h2>
                    
                    <div class="form-group">
                        <label for="image">Upload ảnh (JPG, PNG, GIF - Tối đa 2MB)</label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               class="form-control-file"
                               accept="image/jpeg,image/png,image/gif">
                        <div class="image-preview" id="imagePreview">
                            <span class="preview-text">Chưa có ảnh</span>
                        </div>
                        <small class="form-hint">Kích thước đề xuất: 1280x720px (tỉ lệ 16:9)</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        ✅ Tạo khóa học
                    </button>
                    <a href="<?php echo BASE_URL; ?>/?controller=course&action=dashboard" class="btn btn-secondary">
                        ❌ Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview ảnh khi chọn file
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span class="preview-text">Chưa có ảnh</span>';
            }
        });

        // Format giá tiền
        document.getElementById('price').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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

        .form-control-file {
            width: 100%;
            padding: 10px;
            border: 2px dashed #d1d7dc;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-hint {
            display: block;
            color: #6a6f73;
            font-size: 13px;
            margin-top: 5px;
        }

        .image-preview {
            margin-top: 15px;
            border: 2px dashed #d1d7dc;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f9fa;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 4px;
        }

        .preview-text {
            color: #6a6f73;
            font-size: 14px;
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
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
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

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</body>
</html>
<?php 
// Clear old data after displaying
unset($_SESSION['old_data']); 
?>
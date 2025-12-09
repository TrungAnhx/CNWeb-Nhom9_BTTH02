<?php require_once 'views/layouts/header.php'; ?>

<!-- Banner Chào mừng -->
<div class="hero-banner">
    <div class="container">
        <h1>Học kỹ năng mới, mở tương lai mới</h1>
        <p>Hơn 1000 khóa học từ các chuyên gia hàng đầu về Lập trình, Thiết kế, Kinh doanh...</p>
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="index.php?controller=auth&action=register" class="btn btn-signup" style="font-size: 1.1rem; padding: 15px 30px;">Tham gia ngay</a>
        <?php endif; ?>
    </div>
</div>

<!-- Nội dung chính -->
<div class="container">
    <h2 class="section-title">Khóa học nổi bật</h2>

    <?php if (empty($courses)): ?>
        <!-- TRƯỜNG HỢP: CHƯA CÓ KHÓA HỌC NÀO -->
        <div style="text-align: center; padding: 50px; background-color: #fff; border-radius: 8px; border: 1px dashed #ccc;">
            <i class="fas fa-box-open" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
            <p style="font-size: 18px; color: #666;">Hiện chưa có khóa học nào được xuất bản.</p>
            <p>Vui lòng quay lại sau nhé!</p>
        </div>
    <?php else: ?>
        <!-- TRƯỜNG HỢP: CÓ KHÓA HỌC -> HIỆN GRID -->
        <div class="course-grid">
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <!-- Ảnh khóa học (Nếu null thì dùng ảnh placeholder) -->
                    <?php 
                        $imgSrc = !empty($course['image']) ? 'assets/uploads/courses/' . $course['image'] : 'https://via.placeholder.com/280x160?text=No+Image';
                    ?>
                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-image">
                    
                    <div class="course-info">
                        <h3 class="course-title" title="<?php echo htmlspecialchars($course['title']); ?>">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </h3>
                        
                        <div class="course-instructor">
                            Giảng viên: <?php echo htmlspecialchars($course['instructor_name']); ?>
                        </div>
                        
                        <div class="course-price">
                            <?php echo number_format($course['price'], 0, ',', '.'); ?>đ
                        </div>

                        <!-- Link xem chi tiết -->
                        <a href="index.php?controller=course&action=detail&id=<?php echo $course['id']; ?>" class="btn btn-login" style="display: block; text-align: center; margin-top: 10px;">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
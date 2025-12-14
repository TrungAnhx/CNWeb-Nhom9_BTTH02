<?php require_once 'views/layouts/header.php'; ?>

<!-- Container này sẽ thẳng hàng với Header/Footer -->
<div class="container admin-layout">
    
    <!-- Sidebar -->
    <?php require_once 'views/layouts/admin_sidebar.php'; ?>

    <!-- Nội dung chính -->
    <main class="admin-content">
        <h2 style="border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 30px; color: #333;">
            <i class="fas fa-chart-line"></i> Tổng quan hệ thống
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px;">
            <!-- Thẻ 1: Khóa học -->
            <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; border-bottom: 4px solid #a435f0;">
                <div style="font-size: 40px; color: #a435f0; margin-bottom: 10px;">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 style="font-size: 36px; margin: 0; color: #333;"><?php echo $countCourses; ?></h3>
                <p style="color: #666; margin-top: 5px;">Khóa học hiện có</p>
            </div>

            <!-- Thẻ 2: Học viên -->
            <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; border-bottom: 4px solid #28a745;">
                <div style="font-size: 40px; color: #28a745; margin-bottom: 10px;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 style="font-size: 36px; margin: 0; color: #333;"><?php echo $countStudents; ?></h3>
                <p style="color: #666; margin-top: 5px;">Học viên</p>
            </div>

            <!-- Thẻ 3: Giảng viên -->
            <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; border-bottom: 4px solid #dc3545;">
                <div style="font-size: 40px; color: #dc3545; margin-bottom: 10px;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 style="font-size: 36px; margin: 0; color: #333;"><?php echo $countInstructors; ?></h3>
                <p style="color: #666; margin-top: 5px;">Giảng viên</p>
            </div>
        </div>

        <!-- Phần hoạt động gần đây (Placeholder) -->
        <div style="margin-top: 50px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <h3><i class="fas fa-history"></i> Hoạt động gần đây</h3>
            <p style="color: #777; font-style: italic; margin-top: 15px;">Hệ thống đang hoạt động ổn định. Chưa có cảnh báo nào.</p>
        </div>
    </main>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

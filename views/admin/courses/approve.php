<?php require_once 'views/layouts/header.php'; ?>

<div class="container admin-layout">
    <!-- Sidebar -->
    <?php require_once 'views/layouts/admin_sidebar.php'; ?>

    <!-- Content -->
    <main class="admin-content">
        <h2 style="font-size: 24px; color: #2d2f31; margin-bottom: 20px;">Duyệt Khóa Học Mới</h2>

        <div class="card-box">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">ID</th>
                        <th style="width: 30%;">Tên khóa học</th>
                        <th>Giảng viên</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th style="width: 150px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pendingCourses)): ?>
                        <tr><td colspan="6" style="text-align: center; padding: 40px; color: #6a6f73;">
                            <i class="fas fa-check-circle" style="font-size: 32px; color: #28a745; margin-bottom: 10px; display: block;"></i>
                            Tuyệt vời! Không có khóa học nào đang chờ duyệt.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($pendingCourses as $course): ?>
                        <tr>
                            <td style="text-align: center; color: #6a6f73; font-weight: bold;">
                                #<?php echo $course['id']; ?>
                            </td>
                            <td>
                                <strong style="font-size: 15px; color: #2d2f31;"><?php echo htmlspecialchars($course['title']); ?></strong>
                                <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                    <?php echo htmlspecialchars(substr($course['description'], 0, 50)) . '...'; ?>
                                </div>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($course['instructor_name'] ?? 'Không xác định'); ?>
                            </td>
                            <td>
                                <span style="background: #e3f2fd; color: #0d47a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    <?php echo htmlspecialchars($course['category_name'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td style="font-weight: bold; color: #d32f2f;">
                                <?php echo number_format($course['price'], 0, ',', '.'); ?>đ
                            </td>
                            <td>
                                <!-- Nút Duyệt -->
                                <a href="index.php?controller=admin&action=approveCourse&id=<?php echo $course['id']; ?>" 
                                   class="btn btn-edit" style="background-color: #28a745; color: white;"
                                   onclick="return confirm('Bạn chắc chắn muốn duyệt khóa học này?');" title="Duyệt bài">
                                   <i class="fas fa-check"></i> Duyệt
                                </a>
                                
                                <!-- Nút Từ chối -->
                                <a href="index.php?controller=admin&action=deleteCourse&id=<?php echo $course['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Bạn muốn từ chối (xóa) khóa học này?');" title="Từ chối">
                                   <i class="fas fa-times"></i> Hủy
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

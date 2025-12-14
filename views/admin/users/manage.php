<?php require_once 'views/layouts/header.php'; ?>

<div class="container admin-layout">
    <!-- Sidebar -->
    <?php require_once 'views/layouts/admin_sidebar.php'; ?>

    <!-- Content -->
    <main class="admin-content">
        <h2 style="font-size: 24px; color: #2d2f31; margin-bottom: 20px;">Quản lý Người dùng</h2>

        <!-- THANH TÌM KIẾM & BỘ LỌC -->
        <div class="card-box" style="margin-bottom: 20px; padding: 15px;">
            <form action="" method="GET" style="display: flex; gap: 10px; align-items: center;">
                <input type="hidden" name="controller" value="admin">
                <input type="hidden" name="action" value="users">
                
                <!-- Tìm kiếm từ khóa -->
                <div style="flex: 1;">
                    <input type="text" name="keyword" 
                           value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"
                           placeholder="Nhập tên hoặc email..." 
                           style="width: 100%; padding: 10px; border: 1px solid #d1d7dc; border-radius: 4px;">
                </div>

                <!-- Lọc theo vai trò -->
                <div style="width: 200px;">
                    <select name="role" style="width: 100%; padding: 10px; border: 1px solid #d1d7dc; border-radius: 4px; cursor: pointer;">
                        <option value="">-- Tất cả vai trò --</option>
                        <option value="1" <?php echo (isset($_GET['role']) && $_GET['role'] == '1') ? 'selected' : ''; ?>>Giảng viên</option>
                        <option value="0" <?php echo (isset($_GET['role']) && $_GET['role'] == '0') ? 'selected' : ''; ?>>Học viên</option>
                        <option value="2" <?php echo (isset($_GET['role']) && $_GET['role'] == '2') ? 'selected' : ''; ?>>Quản trị viên</option>
                    </select>
                </div>

                <!-- Nút Tìm kiếm -->
                <button type="submit" class="btn btn-udemy" style="padding: 10px 20px;">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </form>
        </div>

        <!-- BẢNG DANH SÁCH -->
        <div class="card-box">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">ID</th>
                        <th style="width: 25%;">Họ tên / Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th style="width: 180px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($users)): ?>
                        <tr><td colspan="5" style="text-align: center; padding: 30px;">Không tìm thấy kết quả nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td style="text-align: center; color: #6a6f73; font-weight: bold;">
                                #<?php echo $u['id']; ?>
                            </td>
                            <td>
                                <div style="font-weight: bold; font-size: 15px; color: #2d2f31;">
                                    <?php echo htmlspecialchars($u['fullname']); ?>
                                </div>
                                <div style="font-size: 13px; color: #6a6f73;">
                                    <?php echo htmlspecialchars($u['email']); ?>
                                </div>
                            </td>
                            <td>
                                <?php if($u['role'] == 2): ?>
                                    <span style="background: #2d2f31; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">QUẢN TRỊ VIÊN</span>
                                <?php elseif($u['role'] == 1): ?>
                                    <span style="background: #a435f0; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">GIẢNG VIÊN</span>
                                <?php else: ?>
                                    <span style="background: #6a6f73; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">HỌC VIÊN</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['status'] == 1): ?>
                                    <span style="color: #28a745; font-weight: bold; font-size: 13px;">
                                        <i class="fas fa-check-circle"></i> Hoạt động
                                    </span>
                                <?php else: ?>
                                    <span style="color: #dc3545; font-weight: bold; font-size: 13px;">
                                        <i class="fas fa-lock"></i> Đã khóa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Nút Khóa / Mở Khóa -->
                                <?php if($u['status'] == 1): ?>
                                    <a href="index.php?controller=admin&action=toggleUserStatus&id=<?php echo $u['id']; ?>&status=0" 
                                       class="btn btn-delete" 
                                       onclick="return confirm('Bạn muốn khóa tài khoản này?');" title="Khóa tài khoản">
                                       <i class="fas fa-lock"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?controller=admin&action=toggleUserStatus&id=<?php echo $u['id']; ?>&status=1" 
                                       class="btn btn-edit" style="background-color: #28a745; color: white;"
                                       onclick="return confirm('Mở khóa tài khoản này?');" title="Mở khóa">
                                       <i class="fas fa-unlock"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <!-- Nút Xóa vĩnh viễn -->
                                <a href="index.php?controller=admin&action=deleteUser&id=<?php echo $u['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('CẢNH BÁO: Hành động này không thể hoàn tác! Bạn chắc chắn muốn xóa?');" title="Xóa vĩnh viễn">
                                   <i class="fas fa-trash"></i>
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
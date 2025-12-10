<?php require_once 'views/layouts/header.php'; ?>

<div class="container admin-layout">
    <!-- Sidebar -->
    <?php require_once 'views/layouts/admin_sidebar.php'; ?>

    <!-- Content -->
    <main class="admin-content">
        <!-- Header của trang -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 24px; color: #2d2f31; margin: 0;">Quản lý Danh mục</h2>
            <a href="index.php?controller=admin&action=createCategory" class="btn btn-udemy">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
        </div>

        <!-- Khối bảng dữ liệu -->
        <div class="card-box">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">ID</th>
                        <th style="width: 25%;">Tên danh mục</th>
                        <th>Mô tả</th>
                        <th style="width: 180px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($categories)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #6a6f73;">
                                <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                Chưa có danh mục nào. Hãy tạo mới!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td style="text-align: center; font-weight: bold; color: #6a6f73;">
                                #<?php echo $cat['id']; ?>
                            </td>
                            <td>
                                <strong style="font-size: 16px; color: #2d2f31;"><?php echo htmlspecialchars($cat['name']); ?></strong>
                            </td>
                            <td style="color: #555;">
                                <?php echo htmlspecialchars($cat['description']); ?>
                            </td>
                            <td>
                                <a href="index.php?controller=admin&action=editCategory&id=<?php echo $cat['id']; ?>" 
                                   class="btn btn-edit" title="Sửa">
                                    <i class="fas fa-pen"></i>
                                </a>
                                
                                <a href="index.php?controller=admin&action=deleteCategory&id=<?php echo $cat['id']; ?>" 
                                   class="btn btn-delete" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" title="Xóa">
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
<?php require_once 'views/layouts/header.php'; ?>

<div class="container admin-layout">
    <?php require_once 'views/layouts/admin_sidebar.php'; ?>

    <main class="admin-content">
        <h2>Chỉnh sửa danh mục</h2>
        
        <form action="" method="POST" style="background: #fff; padding: 30px; border-radius: 8px; max-width: 600px;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tên danh mục:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mô tả:</label>
                <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"><?php echo htmlspecialchars($category['description']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-signup">Cập nhật</button>
            <a href="index.php?controller=admin&action=categories" class="btn btn-login" style="margin-left: 10px;">Hủy</a>
        </form>
    </main>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

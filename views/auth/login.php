<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="display: flex; justify-content: center; margin-top: 50px; margin-bottom: 50px;">
    <div style="width: 100%; max-width: 400px; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; margin-bottom: 30px; color: #333;">Đăng nhập</h2>
        
        <?php if (!empty($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=login" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px;">Mật khẩu</label>
                <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;">
            </div>

            <button type="submit" class="btn btn-signup" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold; border-radius: 4px;">Đăng nhập</button>
        </form>

        <div style="margin-top: 20px; text-align: center; font-size: 14px;">
            Chưa có tài khoản? <a href="index.php?controller=auth&action=register" style="color: #a435f0; font-weight: bold;">Đăng ký ngay</a>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="display: flex; justify-content: center; margin-top: 50px; margin-bottom: 50px;">
    <div style="width: 100%; max-width: 500px; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; margin-bottom: 30px; color: #333;">Đăng ký thành viên</h2>
        
        <?php if (!empty($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <?php echo $success; ?> <a href="index.php?controller=auth&action=login">Đăng nhập ngay</a>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=register" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Họ và tên</label>
                <input type="text" name="fullname" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tên đăng nhập (Username)</label>
                <input type="text" name="username" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mật khẩu</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nhập lại mật khẩu</label>
                <input type="password" name="confirm_password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" class="btn btn-signup" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold;">Đăng ký</button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            Đã có tài khoản? <a href="index.php?controller=auth&action=login" style="color: #a435f0; font-weight: bold;">Đăng nhập</a>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

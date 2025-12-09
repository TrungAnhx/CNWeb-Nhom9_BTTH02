<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | E-Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #A435F0; /* Udemy Purple */
            --primary-hover: #8710D8;
        }

        body {
            font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, Roboto, 'Segoe UI', Helvetica, Arial, sans-serif;
            background-image: url('https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Lớp phủ tối màu lên ảnh nền để dễ đọc chữ */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4); /* Màu đen mờ 40% */
            z-index: 0;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1; /* Nổi lên trên lớp phủ */
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px); /* Hiệu ứng bay nhẹ khi di chuột */
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 25px;
        }
        .brand-logo img {
            height: 40px;
        }

        .login-title {
            text-align: center;
            font-weight: 700;
            color: #2d2f31;
            margin-bottom: 30px;
            font-size: 1.2rem;
        }

        .form-control {
            height: 50px;
            border-radius: 8px; /* Bo tròn mềm mại hơn */
            border: 1px solid #d1d7dc;
            padding-left: 20px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(164, 53, 240, 0.1); /* Hiệu ứng glow tím nhẹ */
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            height: 50px;
            font-weight: 700;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            border: none;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
            color: white;
        }

        .extra-links {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
        .extra-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        .extra-links a:hover {
            text-decoration: underline;
        }

        .alert-custom {
            font-size: 14px;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
        .alert-custom i { margin-right: 10px; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <!-- Logo Udemy -->
            <img src="https://www.udemy.com/staticx/udemy/images/v7/logo-udemy.svg" alt="Logo">
        </div>
        
        <h2 class="login-title">Chào mừng trở lại!</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-custom">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=login" method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" required placeholder="Email đăng nhập">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" required placeholder="Mật khẩu">
            </div>
            
            <button type="submit" class="btn btn-login">
                Đăng nhập
            </button>
        </form>

        <div class="extra-links">
            <p class="mb-2"><a href="#">Quên mật khẩu?</a></p>
            <p class="mb-0 text-muted">Chưa có tài khoản? <a href="index.php?controller=auth&action=register">Đăng ký ngay</a></p>
        </div>
    </div>

</body>
</html>
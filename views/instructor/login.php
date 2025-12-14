<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Giảng viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Udemy Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 48px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 440px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-header h1 {
            color: #5624d0;
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: #6a6f73;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #1c1d1f;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #d1d7dc;
            border-radius: 4px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #5624d0;
            box-shadow: 0 0 0 3px rgba(86, 36, 208, 0.1);
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #5624d0;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-login:hover {
            background: #401b9c;
        }
        
        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #d1d7dc;
        }
        
        .divider span {
            background: white;
            padding: 0 16px;
            position: relative;
            color: #6a6f73;
            font-size: 14px;
        }
        
        .test-login-link {
            text-align: center;
            margin-top: 16px;
        }
        
        .test-login-link a {
            color: #5624d0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        
        .test-login-link a:hover {
            text-decoration: underline;
        }
        
        .info-box {
            background: #f7f9fa;
            border-left: 4px solid #5624d0;
            padding: 16px;
            margin-top: 24px;
            border-radius: 4px;
        }
        
        .info-box h3 {
            color: #1c1d1f;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .info-box p {
            color: #6a6f73;
            font-size: 13px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🎓 Đăng nhập Giảng viên</h1>
            <p>Chào mừng quay trở lại! Đăng nhập để quản lý khóa học của bạn</p>
        </div>
        
        <?php
        // Hiển thị lỗi nếu có
        if (isset($_SESSION['login_error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
            unset($_SESSION['login_error']);
        }
        ?>
        
        <form method="POST" action="?controller=auth&action=instructorLogin">
            <div class="form-group">
                <label for="username">Tên đăng nhập hoặc Email</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Nhập username hoặc email"
                    required
                    autocomplete="username"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Nhập mật khẩu"
                    required
                    autocomplete="current-password"
                >
            </div>
            
            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>
        
        <div class="divider">
            <span>HOẶC</span>
        </div>
        
        <div class="test-login-link">
            <a href="fake_login.php">🔧 Fake Login (Để test nhanh)</a>
        </div>
        
        <div class="info-box">
            <h3>⚠️ Lưu ý:</h3>
            <p>Trang này chỉ dành cho <strong>Giảng viên</strong>. Nếu bạn là học viên, vui lòng truy cập trang đăng nhập học viên.</p>
        </div>
    </div>
</body>
</html>

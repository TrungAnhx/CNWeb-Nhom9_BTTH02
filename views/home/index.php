<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Khóa học Online</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 60px 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            text-align: center;
        }
        
        h1 {
            color: #2d2f31;
            font-size: 36px;
            margin-bottom: 20px;
        }
        
        p {
            color: #6a6f73;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        
        .btn-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 16px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #5624d0;
            color: white;
        }
        
        .btn-primary:hover {
            background: #401b9c;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(86, 36, 208, 0.3);
        }
        
        .btn-secondary {
            background: white;
            color: #5624d0;
            border: 2px solid #5624d0;
        }
        
        .btn-secondary:hover {
            background: #5624d0;
            color: white;
            transform: translateY(-2px);
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 40px;
            text-align: left;
        }
        
        .feature {
            padding: 20px;
            background: #f7f9fa;
            border-radius: 8px;
        }
        
        .feature-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .feature-title {
            font-weight: 600;
            color: #1c1d1f;
            margin-bottom: 5px;
        }
        
        .feature-desc {
            font-size: 14px;
            color: #6a6f73;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Chào mừng đến với Khóa học Online</h1>
        <p>Nền tảng học tập trực tuyến dành cho giảng viên và học viên.<br>
        Tạo khóa học, chia sẻ kiến thức, và phát triển kỹ năng của bạn.</p>
        
        <div class="btn-group">
            <a href="<?php echo BASE_URL; ?>/instructor/login" class="btn btn-primary">
                👨‍🏫 Đăng nhập Giảng viên
            </a>
            <a href="<?php echo BASE_URL; ?>/fake_login.php" class="btn btn-secondary">
                🔐 Fake Login (Test)
            </a>
        </div>
        
        <div class="features">
            <div class="feature">
                <div class="feature-icon">📚</div>
                <div class="feature-title">Tạo khóa học</div>
                <div class="feature-desc">Dễ dàng tạo và quản lý khóa học</div>
            </div>
            <div class="feature">
                <div class="feature-icon">🎥</div>
                <div class="feature-title">Video bài giảng</div>
                <div class="feature-desc">Upload video và tài liệu</div>
            </div>
            <div class="feature">
                <div class="feature-icon">📊</div>
                <div class="feature-title">Theo dõi tiến độ</div>
                <div class="feature-desc">Quản lý học viên hiệu quả</div>
            </div>
        </div>
    </div>
</body>
</html>
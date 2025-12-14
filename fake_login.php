<?php
/**
 * FAKE LOGIN FOR TESTING
 * Truy cập: http://localhost/your-project/fake_login.php
 * Hoặc: http://localhost/your-project/?controller=fake&action=login
 */
session_start();

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fake Login - Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, system-ui, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #5624d0;
            margin-bottom: 20px;
            text-align: center;
        }
        .user-card {
            border: 2px solid #e8e8e8;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        .user-card:hover {
            border-color: #5624d0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(86, 36, 208, 0.1);
        }
        .user-card.active {
            background: #f0ebff;
            border-color: #5624d0;
        }
        .user-card h3 {
            color: #1c1d1f;
            margin-bottom: 8px;
        }
        .user-card p {
            color: #6a6f73;
            font-size: 14px;
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
        }
        .badge.admin { background: #dc3545; color: white; }
        .badge.instructor { background: #5624d0; color: white; }
        .badge.student { background: #6c757d; color: white; }
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #5624d0;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
        }
        .btn:hover {
            background: #401b9c;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Xử lý login
        if (isset($_POST['login_as'])) {
            $user_id = intval($_POST['user_id']);
            $username = $_POST['username'];
            $role = intval($_POST['role']);
            $fullname = $_POST['fullname'];
            
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['fullname'] = $fullname;
            
            echo '<div class="success">';
            echo '<h2 style="margin-bottom: 10px;">✅ Đã đăng nhập thành công!</h2>';
            echo '<p><strong>User ID:</strong> ' . $user_id . '</p>';
            echo '<p><strong>Username:</strong> ' . $username . '</p>';
            echo '<p><strong>Role:</strong> ' . $role . ' (' . ['Student', 'Instructor', 'Admin'][$role] . ')</p>';
            echo '<p><strong>Fullname:</strong> ' . $fullname . '</p>';
            echo '</div>';
            
            echo '<a href="index.php?controller=course&action=dashboard" class="btn">Đi đến Dashboard →</a>';
            echo '<a href="test_db.php" class="btn" style="background: #6c757d; margin-top: 10px;">Test Database →</a>';
            echo '<a href="?" class="btn" style="background: #dc3545; margin-top: 10px;">Đăng nhập lại</a>';
            
        } else {
            ?>
            <h1>🔐 Fake Login</h1>
            <p style="text-align: center; color: #6a6f73; margin-bottom: 20px;">
                Chọn user để test hệ thống
            </p>
            
            <?php
            // Lấy users từ database
            require_once 'config/Database.php';
            $database = new Database();
            $db = $database->getConnection();
            
            if ($db) {
                try {
                    $stmt = $db->query("SELECT * FROM users ORDER BY role DESC, id ASC");
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($users) > 0) {
                        echo '<form method="POST">';
                        foreach ($users as $user) {
                            $roleNames = ['Student', 'Instructor', 'Admin'];
                            $roleName = $roleNames[$user['role']];
                            $badgeClass = strtolower($roleName);
                            
                            echo '<label>';
                            echo '<div class="user-card">';
                            echo '<h3>' . htmlspecialchars($user['fullname'] ?? $user['username']) . '</h3>';
                            echo '<p><strong>Username:</strong> ' . htmlspecialchars($user['username']) . '</p>';
                            echo '<p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>';
                            echo '<span class="badge ' . $badgeClass . '">' . $roleName . '</span>';
                            echo '<input type="radio" name="login_as" value="' . $user['id'] . '" required 
                                         style="float: right; width: 20px; height: 20px; margin-top: -60px;">';
                            echo '<input type="hidden" name="user_id" value="' . $user['id'] . '">';
                            echo '<input type="hidden" name="username" value="' . htmlspecialchars($user['username']) . '">';
                            echo '<input type="hidden" name="role" value="' . $user['role'] . '">';
                            echo '<input type="hidden" name="fullname" value="' . htmlspecialchars($user['fullname'] ?? $user['username']) . '">';
                            echo '</div>';
                            echo '</label>';
                        }
                        echo '<button type="submit" class="btn">Đăng nhập →</button>';
                        echo '</form>';
                    } else {
                        echo '<div class="info">⚠️ Chưa có users trong database. Import file database.sql trước!</div>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="info">❌ Lỗi: ' . $e->getMessage() . '</div>';
                }
            } else {
                echo '<div class="info">❌ Không thể kết nối database!</div>';
            }
            ?>
            
            <div class="info">
                <strong>💡 Lưu ý:</strong><br>
                - Chọn <strong>Instructor</strong> để test trang My Courses<br>
                - Nếu instructor chưa có khóa học → Hiển thị empty state<br>
                - Nếu có khóa học → Hiển thị danh sách
            </div>
        <?php } ?>
    </div>
    
    <script>
        // Highlight selected user
        document.querySelectorAll('input[name="login_as"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.user-card').forEach(card => {
                    card.classList.remove('active');
                });
                this.closest('.user-card').classList.add('active');
            });
        });
        
        // Auto select on click anywhere in card
        document.querySelectorAll('.user-card').forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
                document.querySelectorAll('.user-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>

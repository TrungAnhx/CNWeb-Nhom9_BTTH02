<?php
/**
 * TEST DATABASE CONNECTION
 * Truy cập: http://localhost:8000/test-db
 */

require_once 'config/Database.php';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Database Connection</title>
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
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            color: #5624d0;
            margin-bottom: 10px;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .status.success {
            background: #19a974;
            color: white;
        }
        .status.error {
            background: #dc3545;
            color: white;
        }
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h2 {
            color: #1c1d1f;
            margin-bottom: 15px;
            border-bottom: 2px solid #5624d0;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #f7f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #d1d7dc;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e8e8e8;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge.draft { background: #ffc107; color: #000; }
        .badge.pending { background: #17a2b8; color: #fff; }
        .badge.approved { background: #19a974; color: #fff; }
        .badge.admin { background: #dc3545; color: #fff; }
        .badge.instructor { background: #5624d0; color: #fff; }
        .badge.student { background: #6c757d; color: #fff; }
        .stat {
            display: inline-block;
            margin-right: 20px;
            font-size: 14px;
            color: #6a6f73;
        }
        .stat strong {
            color: #1c1d1f;
            font-size: 18px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #5624d0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: 600;
        }
        .btn:hover {
            background: #401b9c;
        }
        .error-msg {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Test Database Connection</h1>
            <?php
            $database = new Database();
            $db = $database->getConnection();
            
            if ($db) {
                echo '<span class="status success">✓ Kết nối thành công!</span>';
                echo '<p style="margin-top: 10px; color: #6a6f73;">Database: <strong>onlinecourse</strong></p>';
            } else {
                echo '<span class="status error">✗ Kết nối thất bại!</span>';
            }
            ?>
        </div>

        <?php if ($db): ?>
            <!-- USERS -->
            <div class="section">
                <h2>👥 Users</h2>
                <?php
                try {
                    $stmt = $db->query("SELECT * FROM users ORDER BY id");
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo '<div class="stat"><strong>' . count($users) . '</strong> users</div>';
                    
                    if (count($users) > 0) {
                        echo '<table>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Fullname</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>';
                        
                        foreach ($users as $user) {
                            $roleText = ['student', 'instructor', 'admin'][$user['role']];
                            $statusText = $user['status'] == 1 ? '✓ Active' : '✗ Blocked';
                            
                            echo '<tr>
                                    <td>' . $user['id'] . '</td>
                                    <td><strong>' . htmlspecialchars($user['username']) . '</strong></td>
                                    <td>' . htmlspecialchars($user['email']) . '</td>
                                    <td>' . htmlspecialchars($user['fullname'] ?? '-') . '</td>
                                    <td><span class="badge ' . $roleText . '">' . $roleText . '</span></td>
                                    <td>' . $statusText . '</td>
                                    <td>' . date('d/m/Y', strtotime($user['created_at'])) . '</td>
                                  </tr>';
                        }
                        echo '</table>';
                    } else {
                        echo '<p style="color: #999; margin-top: 10px;">Chưa có users nào.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="error-msg">Error: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>

            <!-- CATEGORIES -->
            <div class="section">
                <h2>📂 Categories</h2>
                <?php
                try {
                    $stmt = $db->query("SELECT * FROM categories ORDER BY id");
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo '<div class="stat"><strong>' . count($categories) . '</strong> categories</div>';
                    
                    if (count($categories) > 0) {
                        echo '<table>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                </tr>';
                        
                        foreach ($categories as $cat) {
                            echo '<tr>
                                    <td>' . $cat['id'] . '</td>
                                    <td><strong>' . htmlspecialchars($cat['name']) . '</strong></td>
                                    <td>' . htmlspecialchars($cat['description'] ?? '-') . '</td>
                                    <td>' . date('d/m/Y', strtotime($cat['created_at'])) . '</td>
                                  </tr>';
                        }
                        echo '</table>';
                    } else {
                        echo '<p style="color: #999; margin-top: 10px;">Chưa có categories nào.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="error-msg">Error: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>

            <!-- COURSES -->
            <div class="section">
                <h2>📚 Courses</h2>
                <?php
                try {
                    $stmt = $db->query("
                        SELECT c.*, 
                               cat.name as category_name,
                               u.fullname as instructor_name,
                               (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count,
                               (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
                        FROM courses c
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        LEFT JOIN users u ON c.instructor_id = u.id
                        ORDER BY c.id
                    ");
                    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo '<div class="stat"><strong>' . count($courses) . '</strong> courses</div>';
                    
                    if (count($courses) > 0) {
                        echo '<table>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Instructor</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Students</th>
                                    <th>Lessons</th>
                                    <th>Status</th>
                                </tr>';
                        
                        foreach ($courses as $course) {
                            $statusClass = strtolower($course['status']);
                            
                            echo '<tr>
                                    <td>' . $course['id'] . '</td>
                                    <td><strong>' . htmlspecialchars($course['title']) . '</strong></td>
                                    <td>' . htmlspecialchars($course['instructor_name'] ?? 'N/A') . '</td>
                                    <td>' . htmlspecialchars($course['category_name'] ?? 'N/A') . '</td>
                                    <td>' . ($course['price'] > 0 ? number_format($course['price']) . ' VNĐ' : 'Free') . '</td>
                                    <td>' . $course['student_count'] . '</td>
                                    <td>' . $course['lesson_count'] . '</td>
                                    <td><span class="badge ' . $statusClass . '">' . $course['status'] . '</span></td>
                                  </tr>';
                        }
                        echo '</table>';
                    } else {
                        echo '<p style="color: #999; margin-top: 10px;">Chưa có courses nào.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="error-msg">Error: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>

            <!-- ENROLLMENTS -->
            <div class="section">
                <h2>📝 Enrollments</h2>
                <?php
                try {
                    $stmt = $db->query("
                        SELECT e.*, 
                               c.title as course_title,
                               u.fullname as student_name
                        FROM enrollments e
                        LEFT JOIN courses c ON e.course_id = c.id
                        LEFT JOIN users u ON e.student_id = u.id
                        ORDER BY e.enrolled_date DESC
                        LIMIT 10
                    ");
                    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $stmt2 = $db->query("SELECT COUNT(*) as total FROM enrollments");
                    $total = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];
                    
                    echo '<div class="stat"><strong>' . $total . '</strong> enrollments</div>';
                    
                    if (count($enrollments) > 0) {
                        echo '<table>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Enrolled Date</th>
                                </tr>';
                        
                        foreach ($enrollments as $enroll) {
                            echo '<tr>
                                    <td>' . $enroll['id'] . '</td>
                                    <td>' . htmlspecialchars($enroll['student_name'] ?? 'N/A') . '</td>
                                    <td>' . htmlspecialchars($enroll['course_title'] ?? 'N/A') . '</td>
                                    <td>' . $enroll['progress'] . '%</td>
                                    <td>' . $enroll['status'] . '</td>
                                    <td>' . date('d/m/Y H:i', strtotime($enroll['enrolled_date'])) . '</td>
                                  </tr>';
                        }
                        echo '</table>';
                        echo '<p style="margin-top: 10px; color: #6a6f73; font-size: 14px;">Showing latest 10 enrollments</p>';
                    } else {
                        echo '<p style="color: #999; margin-top: 10px;">Chưa có enrollments nào.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="error-msg">Error: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>

            <!-- LESSONS -->
            <div class="section">
                <h2>📖 Lessons</h2>
                <?php
                try {
                    $stmt = $db->query("
                        SELECT l.*, c.title as course_title
                        FROM lessons l
                        LEFT JOIN courses c ON l.course_id = c.id
                        ORDER BY l.course_id, l.order_num
                        LIMIT 10
                    ");
                    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $stmt2 = $db->query("SELECT COUNT(*) as total FROM lessons");
                    $total = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];
                    
                    echo '<div class="stat"><strong>' . $total . '</strong> lessons</div>';
                    
                    if (count($lessons) > 0) {
                        echo '<table>
                                <tr>
                                    <th>ID</th>
                                    <th>Course</th>
                                    <th>Title</th>
                                    <th>Order</th>
                                    <th>Video URL</th>
                                </tr>';
                        
                        foreach ($lessons as $lesson) {
                            echo '<tr>
                                    <td>' . $lesson['id'] . '</td>
                                    <td>' . htmlspecialchars($lesson['course_title'] ?? 'N/A') . '</td>
                                    <td><strong>' . htmlspecialchars($lesson['title']) . '</strong></td>
                                    <td>#' . $lesson['order_num'] . '</td>
                                    <td>' . ($lesson['video_url'] ? '✓' : '-') . '</td>
                                  </tr>';
                        }
                        echo '</table>';
                        echo '<p style="margin-top: 10px; color: #6a6f73; font-size: 14px;">Showing first 10 lessons</p>';
                    } else {
                        echo '<p style="color: #999; margin-top: 10px;">Chưa có lessons nào.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="error-msg">Error: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>

            <div class="section" style="text-align: center;">
                <h2>🚀 Next Steps</h2>
                <p style="margin: 15px 0; color: #6a6f73;">Database đã kết nối thành công! Bây giờ bạn có thể:</p>
                <a href="/instructor/my-courses" class="btn">Xem trang Instructor (My Courses)</a>
                <a href="/" class="btn" style="background: #6c757d; margin-left: 10px;">Về trang chủ</a>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>

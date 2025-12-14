-- ================================================
-- FILE IMPORT DỮ LIỆU MẪU - COURSES & LESSONS
-- Chạy file này SAU KHI đã import database.sql
-- ================================================

USE onlinecourse;

-- Thêm instructor mẫu (nếu chưa có)
INSERT IGNORE INTO users (username, email, password, fullname, role, status) VALUES 
('instructor1', 'instructor1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn A - Giảng viên', 1, 1),
('instructor2', 'instructor2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị B - Giảng viên', 1, 1);

-- Thêm courses mẫu
INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, image, status) VALUES 
('Lập trình PHP từ A-Z', 'Khóa học PHP cơ bản đến nâng cao, từ cú pháp, OOP, đến xây dựng website hoàn chỉnh', 
 (SELECT id FROM users WHERE username='admin'), 1, 0, 8, 'Beginner', NULL, 'approved'),

('MySQL và Database Design', 'Học thiết kế cơ sở dữ liệu quan hệ, SQL queries, indexing và optimization', 
 (SELECT id FROM users WHERE username='admin'), 1, 199000, 6, 'Intermediate', NULL, 'approved'),

('JavaScript ES6+ Nâng cao', 'Khóa học JavaScript hiện đại: Arrow functions, Promises, Async/Await, Modules', 
 (SELECT id FROM users WHERE username='admin'), 1, 299000, 10, 'Advanced', NULL, 'pending');

-- Thêm lessons cho course "Lập trình PHP từ A-Z" (course_id = 1)
INSERT INTO lessons (course_id, title, content, video_url, order_num) VALUES 
-- Course 1: PHP từ A-Z
(1, 'Giới thiệu về PHP', 
 '<h2>PHP là gì?</h2><p>PHP (Hypertext Preprocessor) là ngôn ngữ lập trình server-side phổ biến nhất thế giới. PHP được sử dụng để xây dựng các website động, xử lý form, làm việc với database.</p><h3>Ưu điểm:</h3><ul><li>Dễ học, dễ sử dụng</li><li>Miễn phí và open-source</li><li>Hỗ trợ nhiều database</li><li>Cộng đồng lớn</li></ul>', 
 'https://www.youtube.com/watch?v=OK_JCtrrv-c', 1),

(1, 'Cài đặt môi trường XAMPP', 
 '<h2>Cài đặt XAMPP</h2><p>XAMPP là gói phần mềm tích hợp Apache, MySQL, PHP và Perl. Bạn cần XAMPP để chạy PHP trên máy tính.</p><h3>Các bước:</h3><ol><li>Download XAMPP từ apachefriends.org</li><li>Cài đặt và khởi động Apache, MySQL</li><li>Tạo file test.php trong htdocs</li><li>Truy cập localhost/test.php</li></ol>', 
 'https://www.youtube.com/watch?v=h6DEDm7C37A', 2),

(1, 'Biến và kiểu dữ liệu trong PHP', 
 '<h2>Biến trong PHP</h2><p>Biến trong PHP bắt đầu bằng dấu $</p><pre><code>$name = "John";\n$age = 25;\n$price = 99.99;</code></pre><h3>Các kiểu dữ liệu:</h3><ul><li>String - Chuỗi ký tự</li><li>Integer - Số nguyên</li><li>Float - Số thực</li><li>Boolean - true/false</li><li>Array - Mảng</li><li>Object - Đối tượng</li></ul>', 
 '', 3),

(1, 'Toán tử và Biểu thức', 
 '<h2>Toán tử trong PHP</h2><p>PHP hỗ trợ đầy đủ các toán tử: số học, so sánh, logic, gán...</p><h3>Toán tử số học:</h3><pre><code>$a + $b  // Cộng\n$a - $b  // Trừ\n$a * $b  // Nhân\n$a / $b  // Chia\n$a % $b  // Chia lấy dư</code></pre>', 
 '', 4),

(1, 'Cấu trúc điều kiện IF-ELSE', 
 '<h2>Câu lệnh IF-ELSE</h2><pre><code>if ($age >= 18) {\n    echo "Đủ tuổi";\n} else {\n    echo "Chưa đủ tuổi";\n}</code></pre><p>Sử dụng elseif cho nhiều điều kiện</p>', 
 '', 5),

(1, 'Vòng lặp FOR và WHILE', 
 '<h2>Vòng lặp</h2><h3>For loop:</h3><pre><code>for ($i = 1; $i <= 10; $i++) {\n    echo $i;\n}</code></pre><h3>While loop:</h3><pre><code>while ($i < 10) {\n    echo $i;\n    $i++;\n}</code></pre>', 
 '', 6),

-- Course 2: MySQL và Database Design
(2, 'Giới thiệu Database và SQL', 
 '<h2>Database là gì?</h2><p>Database (CSDL) là nơi lưu trữ dữ liệu có tổ chức. MySQL là hệ quản trị CSDL quan hệ phổ biến nhất.</p><h3>SQL - Structured Query Language:</h3><ul><li>DDL: CREATE, ALTER, DROP</li><li>DML: SELECT, INSERT, UPDATE, DELETE</li><li>DCL: GRANT, REVOKE</li></ul>', 
 'https://www.youtube.com/watch?v=HXV3zeQKqGY', 1),

(2, 'Tạo Database và Tables', 
 '<h2>Tạo Database</h2><pre><code>CREATE DATABASE mydb;</code></pre><h2>Tạo bảng</h2><pre><code>CREATE TABLE users (\n    id INT PRIMARY KEY AUTO_INCREMENT,\n    username VARCHAR(50),\n    email VARCHAR(100),\n    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n);</code></pre>', 
 '', 2),

(2, 'SELECT Query - Truy vấn dữ liệu', 
 '<h2>Câu lệnh SELECT</h2><pre><code>SELECT * FROM users;\nSELECT username, email FROM users;\nSELECT * FROM users WHERE age > 18;\nSELECT * FROM users ORDER BY created_at DESC;</code></pre>', 
 '', 3),

(2, 'INSERT, UPDATE, DELETE', 
 '<h2>Thêm dữ liệu</h2><pre><code>INSERT INTO users (username, email) VALUES ("john", "john@example.com");</code></pre><h2>Cập nhật</h2><pre><code>UPDATE users SET email="new@example.com" WHERE id=1;</code></pre><h2>Xóa</h2><pre><code>DELETE FROM users WHERE id=1;</code></pre>', 
 '', 4),

-- Course 3: JavaScript ES6+
(3, 'Arrow Functions', 
 '<h2>Arrow Functions</h2><p>Cú pháp ngắn gọn hơn function truyền thống</p><pre><code>// Old\nfunction add(a, b) {\n    return a + b;\n}\n\n// ES6\nconst add = (a, b) => a + b;</code></pre>', 
 '', 1),

(3, 'Promises và Async/Await', 
 '<h2>Xử lý bất đồng bộ</h2><h3>Promise:</h3><pre><code>fetch(url)\n    .then(response => response.json())\n    .then(data => console.log(data));</code></pre><h3>Async/Await:</h3><pre><code>async function getData() {\n    const response = await fetch(url);\n    const data = await response.json();\n    return data;\n}</code></pre>', 
 '', 2);

-- Thêm một vài enrollments mẫu
INSERT INTO enrollments (course_id, student_id, status, progress) VALUES 
(1, (SELECT id FROM users WHERE username='admin'), 'active', 30),
(2, (SELECT id FROM users WHERE username='admin'), 'active', 50);

-- ================================================
-- KẾT QUẢ:
-- - 3 courses
-- - Course 1: 6 lessons (PHP)
-- - Course 2: 4 lessons (MySQL)  
-- - Course 3: 2 lessons (JavaScript)
-- ================================================

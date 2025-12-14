# TÀI LIỆU CẤU TRÚC DATABASE - WEBSITE QUẢN LÝ KHÓA HỌC

**Database:** `onlinecourse`  
**Charset:** UTF8MB4  
**Collation:** utf8mb4_unicode_ci  
**Ngày cập nhật:** 06/12/2025

---

## 📊 TỔNG QUAN CƠ SỞ DỮ LIỆU

Database gồm **6 bảng chính**, quản lý toàn bộ hệ thống khóa học trực tuyến:

| Bảng | Mục đích | Số cột | Phụ trách |
|------|----------|--------|-----------|
| `users` | Quản lý người dùng (học viên, giảng viên, admin) | 9 | Thành viên 1 |
| `categories` | Danh mục khóa học | 4 | Thành viên 1 |
| `courses` | Khóa học | 12 | Thành viên 2 |
| `enrollments` | Đăng ký học | 6 | Thành viên 3 |
| `lessons` | Bài học trong khóa | 7 | Thành viên 2 |
| `materials` | Tài liệu đính kèm bài học | 6 | Thành viên 2 |

---

## 📋 CHI TIẾT CÁC BẢNG

### 1️⃣ Bảng `users` - NGƯỜI DÙNG
**Chức năng:** Lưu trữ thông tin tất cả người dùng trong hệ thống

| Cột | Kiểu dữ liệu | Ràng buộc | Mô tả | Sử dụng bởi |
|-----|--------------|-----------|-------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID duy nhất của user | Tất cả |
| `username` | VARCHAR(255) | UNIQUE, NOT NULL | Tên đăng nhập | Auth |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email (dùng để đăng nhập) | Auth |
| `password` | VARCHAR(255) | NOT NULL | Mật khẩu đã hash (bcrypt) | Auth |
| `fullname` | VARCHAR(255) | NULL | Họ tên đầy đủ | Profile |
| `avatar` | VARCHAR(255) | NULL | Đường dẫn ảnh đại diện | Profile |
| `role` | INT | DEFAULT 0 | **0**: Học viên<br>**1**: Giảng viên<br>**2**: Admin | Auth, Admin |
| `status` | TINYINT | DEFAULT 1 | **1**: Hoạt động<br>**0**: Bị khóa | Admin |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Ngày đăng ký | Admin |

#### 🔑 Khóa ngoại:
- Được tham chiếu bởi: `courses.instructor_id`, `enrollments.student_id`

#### 💡 Use Cases:

**Thành viên 1 (Auth & Admin):**
```sql
-- Đăng ký user mới
INSERT INTO users (username, email, password, fullname, role) 
VALUES (?, ?, ?, ?, 0);

-- Đăng nhập
SELECT * FROM users WHERE email = ? AND status = 1;

-- Quản lý user (Admin)
SELECT id, username, email, fullname, role, status, created_at 
FROM users 
ORDER BY created_at DESC;

-- Khóa/Mở khóa tài khoản
UPDATE users SET status = ? WHERE id = ?;
```

**Thành viên 2 (Instructor):**
```sql
-- Lấy thông tin giảng viên
SELECT * FROM users WHERE id = ? AND role = 1;

-- Kiểm tra quyền sở hữu khóa học
SELECT u.id, u.fullname 
FROM users u 
JOIN courses c ON u.id = c.instructor_id 
WHERE c.id = ?;
```

**Thành viên 3 (Student):**
```sql
-- Lấy thông tin học viên
SELECT * FROM users WHERE id = ? AND role = 0;

-- Danh sách học viên trong khóa học
SELECT u.id, u.fullname, u.email, u.avatar
FROM users u
JOIN enrollments e ON u.id = e.student_id
WHERE e.course_id = ?;
```

---

### 2️⃣ Bảng `categories` - DANH MỤC KHÓA HỌC
**Chức năng:** Phân loại khóa học theo chủ đề

| Cột | Kiểu dữ liệu | Ràng buộc | Mô tả | Sử dụng bởi |
|-----|--------------|-----------|-------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID danh mục | Tất cả |
| `name` | VARCHAR(255) | NOT NULL | Tên danh mục | Admin, Instructor |
| `description` | TEXT | NULL | Mô tả chi tiết | Admin |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Ngày tạo | Admin |

#### 🔑 Khóa ngoại:
- Được tham chiếu bởi: `courses.category_id`

#### 💡 Use Cases:

**Thành viên 1 (Admin):**
```sql
-- CRUD Categories
-- Tạo danh mục
INSERT INTO categories (name, description) VALUES (?, ?);

-- Sửa danh mục
UPDATE categories SET name = ?, description = ? WHERE id = ?;

-- Xóa danh mục
DELETE FROM categories WHERE id = ?;

-- Lấy tất cả danh mục
SELECT * FROM categories ORDER BY name;
```

**Thành viên 2 (Instructor):**
```sql
-- Lấy danh mục cho dropdown khi tạo khóa học
SELECT id, name FROM categories ORDER BY name;
```

**Thành viên 3 (Student):**
```sql
-- Lọc khóa học theo danh mục
SELECT c.* 
FROM courses c 
WHERE c.category_id = ? AND c.status = 'approved';

-- Đếm số khóa học theo danh mục
SELECT cat.id, cat.name, COUNT(c.id) as course_count
FROM categories cat
LEFT JOIN courses c ON cat.id = c.category_id
WHERE c.status = 'approved'
GROUP BY cat.id;
```

---

### 3️⃣ Bảng `courses` - KHÓA HỌC
**Chức năng:** Lưu trữ thông tin khóa học (bảng trung tâm)

| Cột | Kiểu dữ liệu | Ràng buộc | Mô tả | Sử dụng bởi |
|-----|--------------|-----------|-------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID khóa học | Tất cả |
| `title` | VARCHAR(255) | NOT NULL | Tiêu đề khóa học | Tất cả |
| `description` | TEXT | NULL | Mô tả chi tiết | Tất cả |
| `instructor_id` | INT | FOREIGN KEY → users(id) | ID giảng viên | Instructor |
| `category_id` | INT | FOREIGN KEY → categories(id) | ID danh mục | Tất cả |
| `price` | DECIMAL(10,2) | DEFAULT 0 | Giá khóa học (VNĐ) | Student |
| `duration_weeks` | INT | NULL | Thời lượng (tuần) | Student |
| `level` | VARCHAR(50) | NULL | **Beginner**: Cơ bản<br>**Intermediate**: Trung cấp<br>**Advanced**: Nâng cao | Student |
| `image` | VARCHAR(255) | NULL | Đường dẫn ảnh thumbnail | Tất cả |
| `status` | VARCHAR(50) | DEFAULT 'pending' | **draft**: Nháp<br>**pending**: Chờ duyệt<br>**approved**: Đã duyệt | Admin, Instructor |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Ngày tạo | Tất cả |
| `updated_at` | DATETIME | AUTO UPDATE | Ngày cập nhật | Tất cả |

#### 🔑 Khóa ngoại:
- `instructor_id` → `users.id` (ON DELETE SET NULL)
- `category_id` → `categories.id` (ON DELETE SET NULL)
- Được tham chiếu bởi: `enrollments.course_id`, `lessons.course_id`

#### 💡 Use Cases:

**Thành viên 1 (Admin):**
```sql
-- Xem tất cả khóa học chờ duyệt
SELECT c.*, u.fullname as instructor_name, cat.name as category_name
FROM courses c
LEFT JOIN users u ON c.instructor_id = u.id
LEFT JOIN categories cat ON c.category_id = cat.id
WHERE c.status = 'pending'
ORDER BY c.created_at DESC;

-- Duyệt khóa học
UPDATE courses SET status = 'approved' WHERE id = ?;

-- Từ chối khóa học
UPDATE courses SET status = 'draft' WHERE id = ?;

-- Thống kê
SELECT status, COUNT(*) as count FROM courses GROUP BY status;
```

**Thành viên 2 (Instructor) - ⭐ CHÍNH:**
```sql
-- Tạo khóa học mới
INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, image, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'draft');

-- Lấy danh sách khóa học của mình
SELECT * FROM courses 
WHERE instructor_id = ? 
ORDER BY created_at DESC;

-- Cập nhật khóa học
UPDATE courses 
SET title = ?, description = ?, category_id = ?, price = ?, duration_weeks = ?, level = ?, image = ?, updated_at = NOW()
WHERE id = ? AND instructor_id = ?;

-- Xóa khóa học
DELETE FROM courses WHERE id = ? AND instructor_id = ?;

-- Thống kê của giảng viên
SELECT 
    COUNT(*) as total_courses,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_courses,
    (SELECT COUNT(DISTINCT student_id) FROM enrollments WHERE course_id IN (SELECT id FROM courses WHERE instructor_id = ?)) as total_students
FROM courses 
WHERE instructor_id = ?;

-- Lấy khóa học có nhiều học viên nhất
SELECT c.*, COUNT(e.id) as student_count
FROM courses c
LEFT JOIN enrollments e ON c.id = e.course_id
WHERE c.instructor_id = ?
GROUP BY c.id
ORDER BY student_count DESC
LIMIT 1;
```

**Thành viên 3 (Student):**
```sql
-- Hiển thị danh sách khóa học (trang chủ)
SELECT c.*, cat.name as category_name, u.fullname as instructor_name,
       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
FROM courses c
LEFT JOIN categories cat ON c.category_id = cat.id
LEFT JOIN users u ON c.instructor_id = u.id
WHERE c.status = 'approved'
ORDER BY c.created_at DESC;

-- Tìm kiếm khóa học
SELECT c.* FROM courses c
WHERE c.status = 'approved' 
AND (c.title LIKE ? OR c.description LIKE ?)
ORDER BY c.created_at DESC;

-- Lọc khóa học
SELECT c.* FROM courses c
WHERE c.status = 'approved'
AND (c.category_id = ? OR ? IS NULL)
AND (c.level = ? OR ? IS NULL)
AND (c.price <= ? OR ? IS NULL)
ORDER BY c.created_at DESC;

-- Chi tiết khóa học
SELECT c.*, cat.name as category_name, u.fullname as instructor_name, u.avatar as instructor_avatar,
       (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
FROM courses c
LEFT JOIN categories cat ON c.category_id = cat.id
LEFT JOIN users u ON c.instructor_id = u.id
WHERE c.id = ? AND c.status = 'approved';
```

---

### 4️⃣ Bảng `enrollments` - ĐĂNG KÝ HỌC
**Chức năng:** Quản lý việc học viên đăng ký khóa học và tiến độ học

| Cột | Kiểu dữ liệu | Ràng buộc | Mô tả | Sử dụng bởi |
|-----|--------------|-----------|-------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID đăng ký | Tất cả |
| `course_id` | INT | FOREIGN KEY → courses(id) | ID khóa học | Tất cả |
| `student_id` | INT | FOREIGN KEY → users(id) | ID học viên | Student |
| `enrolled_date` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Ngày đăng ký | Tất cả |
| `status` | VARCHAR(50) | DEFAULT 'active' | **active**: Đang học<br>**completed**: Hoàn thành<br>**dropped**: Đã bỏ học | Student |
| `progress` | INT | DEFAULT 0 | Tiến độ (0-100%) | Student |

#### 🔑 Khóa ngoại:
- `course_id` → `courses.id` (ON DELETE CASCADE)
- `student_id` → `users.id` (ON DELETE CASCADE)

#### 💡 Use Cases:

**Thành viên 1 (Admin):**
```sql
-- Thống kê đăng ký
SELECT 
    COUNT(*) as total_enrollments,
    COUNT(DISTINCT student_id) as unique_students,
    COUNT(DISTINCT course_id) as active_courses
FROM enrollments;

-- Top khóa học có nhiều đăng ký
SELECT c.title, COUNT(e.id) as enrollment_count
FROM enrollments e
JOIN courses c ON e.course_id = c.id
GROUP BY c.id
ORDER BY enrollment_count DESC
LIMIT 10;
```

**Thành viên 2 (Instructor):**
```sql
-- Danh sách học viên trong khóa học của mình
SELECT u.id, u.fullname, u.email, u.avatar, 
       e.enrolled_date, e.status, e.progress
FROM enrollments e
JOIN users u ON e.student_id = u.id
JOIN courses c ON e.course_id = c.id
WHERE c.id = ? AND c.instructor_id = ?
ORDER BY e.enrolled_date DESC;

-- Thống kê học viên theo khóa học
SELECT c.id, c.title, COUNT(e.id) as student_count
FROM courses c
LEFT JOIN enrollments e ON c.id = e.course_id
WHERE c.instructor_id = ?
GROUP BY c.id
ORDER BY student_count DESC;

-- Học viên có tiến độ cao nhất
SELECT u.fullname, e.progress
FROM enrollments e
JOIN users u ON e.student_id = u.id
WHERE e.course_id = ?
ORDER BY e.progress DESC
LIMIT 10;
```

**Thành viên 3 (Student) - ⭐ CHÍNH:**
```sql
-- Đăng ký khóa học
INSERT INTO enrollments (course_id, student_id, status, progress)
VALUES (?, ?, 'active', 0);

-- Kiểm tra đã đăng ký chưa
SELECT * FROM enrollments 
WHERE course_id = ? AND student_id = ?;

-- Danh sách khóa học đã đăng ký
SELECT c.*, e.enrolled_date, e.status, e.progress,
       (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons
FROM enrollments e
JOIN courses c ON e.course_id = c.id
WHERE e.student_id = ?
ORDER BY e.enrolled_date DESC;

-- Cập nhật tiến độ
UPDATE enrollments 
SET progress = ?, status = CASE WHEN ? >= 100 THEN 'completed' ELSE status END
WHERE course_id = ? AND student_id = ?;

-- Bỏ học
UPDATE enrollments 
SET status = 'dropped' 
WHERE id = ?;

-- Tính tiến độ dựa trên số bài học hoàn thành
-- (Cần bảng lesson_progress - có thể thêm sau)
```

---

### 5️⃣ Bảng `lessons` - BÀI HỌC
**Chức năng:** Lưu trữ các bài học trong khóa học

| Cột | Kiểu dữ liệu | Ràng buộc | Mô tả | Sử dụng bởi |
|-----|--------------|-----------|-------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID bài học | Tất cả |
| `course_id` | INT | FOREIGN KEY → courses(id) | ID khóa học | Tất cả |
| `title` | VARCHAR(255) | NOT NULL | Tiêu đề bài học | Tất cả |
| `content` | LONGTEXT | NULL | Nội dung bài học (HTML) | Tất cả |
| `video_url` | VARCHAR(255) | NULL | URL video (YouTube/Vimeo) | Student |
| `order_num` | INT | DEFAULT 0 | Thứ tự hiển thị | Instructor |
| `created_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Ngày tạo | Tất cả |

#### 🔑 Khóa ngoại:
- `course_id` → `courses.id` (ON DELETE CASCADE)
- Được tham chiếu bởi: `materials.lesson_id`

#### 💡 Use Cases:

**Thành viên 1 (Admin):**
```sql
-- Xem tất cả bài học trong hệ thống
SELECT l.*, c.title as course_title
FROM lessons l
JOIN courses c ON l.course_id = c.id
ORDER BY l.created_at DESC;
```

**Thành viên 2 (Instructor) - ⭐ CHÍNH:**
```sql
-- Tạo bài học mới
INSERT INTO lessons (course_id, title, content, video_url, order_num)
VALUES (?, ?, ?, ?, ?);

-- Tự động tính order_num
INSERT INTO lessons (course_id, title, content, video_url, order_num)
VALUES (?, ?, ?, ?, (SELECT COALESCE(MAX(order_num), 0) + 1 FROM lessons WHERE course_id = ?));

-- Danh sách bài học của khóa học
SELECT * FROM lessons 
WHERE course_id = ?
ORDER BY order_num ASC;

-- Cập nhật bài học
UPDATE lessons 
SET title = ?, content = ?, video_url = ?, order_num = ?
WHERE id = ? AND course_id IN (SELECT id FROM courses WHERE instructor_id = ?);

-- Xóa bài học
DELETE FROM lessons 
WHERE id = ? AND course_id IN (SELECT id FROM courses WHERE instructor_id = ?);

-- Sắp xếp lại thứ tự
UPDATE lessons SET order_num = ? WHERE id = ?;

-- Đếm số bài học
SELECT COUNT(*) as lesson_count 
FROM lessons 
WHERE course_id = ?;
```

**Thành viên 3 (Student):**
```sql
-- Lấy danh sách bài học khi vào học
SELECT l.*, 
       (SELECT COUNT(*) FROM materials WHERE lesson_id = l.id) as material_count
FROM lessons l
WHERE l.course_id = ?
ORDER BY l.order_num ASC;

-- Xem chi tiết bài học
SELECT l.*, c.title as course_title
FROM lessons l
JOIN courses c ON l.course_id = c.id
WHERE l.id = ? AND c.id IN (SELECT course_id FROM enrollments WHERE student_id = ?);

-- Bài học tiếp theo
SELECT * FROM lessons 
WHERE course_id = ? AND order_num > ?
ORDER BY order_num ASC
LIMIT 1;

-- Bài học trước đó
SELECT * FROM lessons 
WHERE course_id = ? AND order_num < ?
ORDER BY order_num DESC
LIMIT 1;
```

---

### 6️⃣ Bảng `materials` - TÀI LIỆU ĐÍNH KÈM
**Chức năng:** Lưu trữ tài liệu (PDF, DOC, PPT...) đính kèm bài học

| Cột | Kiểu dữ liệu | Ràng buộc | Mô tả | Sử dụng bởi |
|-----|--------------|-----------|-------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | ID tài liệu | Tất cả |
| `lesson_id` | INT | FOREIGN KEY → lessons(id) | ID bài học | Tất cả |
| `filename` | VARCHAR(255) | NULL | Tên file gốc | Tất cả |
| `file_path` | VARCHAR(255) | NULL | Đường dẫn file trên server | Instructor |
| `file_type` | VARCHAR(50) | NULL | Loại file (pdf, doc, ppt, zip) | Tất cả |
| `uploaded_at` | DATETIME | DEFAULT CURRENT_TIMESTAMP | Ngày upload | Tất cả |

#### 🔑 Khóa ngoại:
- `lesson_id` → `lessons.id` (ON DELETE CASCADE)

#### 💡 Use Cases:

**Thành viên 2 (Instructor) - ⭐ CHÍNH:**
```sql
-- Upload tài liệu
INSERT INTO materials (lesson_id, filename, file_path, file_type)
VALUES (?, ?, ?, ?);

-- Danh sách tài liệu của bài học
SELECT * FROM materials 
WHERE lesson_id = ?
ORDER BY uploaded_at DESC;

-- Danh sách tài liệu của khóa học
SELECT m.*, l.title as lesson_title
FROM materials m
JOIN lessons l ON m.lesson_id = l.id
WHERE l.course_id = ?
ORDER BY l.order_num, m.uploaded_at;

-- Xóa tài liệu
DELETE FROM materials 
WHERE id = ? AND lesson_id IN (
    SELECT l.id FROM lessons l 
    JOIN courses c ON l.course_id = c.id 
    WHERE c.instructor_id = ?
);

-- Đếm tổng tài liệu theo khóa học
SELECT COUNT(*) as total_materials
FROM materials m
JOIN lessons l ON m.lesson_id = l.id
WHERE l.course_id = ?;
```

**Thành viên 3 (Student):**
```sql
-- Lấy tài liệu của bài học (khi đã đăng ký khóa)
SELECT m.* FROM materials m
JOIN lessons l ON m.lesson_id = l.id
WHERE m.lesson_id = ? 
AND l.course_id IN (SELECT course_id FROM enrollments WHERE student_id = ?);

-- Tải xuống tài liệu
SELECT * FROM materials WHERE id = ?;

-- Đếm tài liệu của khóa học
SELECT COUNT(*) as material_count
FROM materials m
JOIN lessons l ON m.lesson_id = l.id
WHERE l.course_id = ?;
```

---

## 🔗 QUAN HỆ GIỮA CÁC BẢNG

```
users (id)
  ├─→ courses (instructor_id) [1:N] - 1 giảng viên có nhiều khóa học
  └─→ enrollments (student_id) [1:N] - 1 học viên đăng ký nhiều khóa

categories (id)
  └─→ courses (category_id) [1:N] - 1 danh mục có nhiều khóa học

courses (id)
  ├─→ enrollments (course_id) [1:N] - 1 khóa học có nhiều đăng ký
  └─→ lessons (course_id) [1:N] - 1 khóa học có nhiều bài học

lessons (id)
  └─→ materials (lesson_id) [1:N] - 1 bài học có nhiều tài liệu
```

---

## 📌 PHÂN CÔNG SỬ DỤNG BẢNG

### 🔵 Thành viên 1 (Leader) - CHÍNH:
- ✅ `users` - CRUD, Authentication, Authorization
- ✅ `categories` - CRUD quản lý danh mục
- 📖 `courses` - Chỉ đọc (duyệt khóa học, thống kê)
- 📖 `enrollments` - Chỉ đọc (thống kê)

### 🟢 Thành viên 2 (Instructor) - CHÍNH:
- ✅ `courses` - **CRUD đầy đủ** (tạo, sửa, xóa khóa học)
- ✅ `lessons` - **CRUD đầy đủ** (quản lý bài học)
- ✅ `materials` - **CRUD đầy đủ** (upload, xóa tài liệu)
- 📖 `users` - Chỉ đọc (lấy thông tin giảng viên)
- 📖 `categories` - Chỉ đọc (dropdown khi tạo khóa học)
- 📖 `enrollments` - Chỉ đọc (xem học viên của mình)

### 🟠 Thành viên 3 (Student) - CHÍNH:
- ✅ `enrollments` - **INSERT, UPDATE** (đăng ký, cập nhật tiến độ)
- 📖 `courses` - Chỉ đọc (hiển thị danh sách, chi tiết)
- 📖 `lessons` - Chỉ đọc (xem bài học)
- 📖 `materials` - Chỉ đọc (tải tài liệu)
- 📖 `users` - Chỉ đọc (profile học viên)
- 📖 `categories` - Chỉ đọc (lọc khóa học)

---

## 🛡️ BẢO MẬT & KIỂM TRA QUYỀN

### Nguyên tắc quan trọng:

#### 1. **Instructor chỉ được sửa/xóa khóa học của mình:**
```sql
-- ✅ ĐÚNG
UPDATE courses SET ... WHERE id = ? AND instructor_id = ?;
DELETE FROM courses WHERE id = ? AND instructor_id = ?;

-- ❌ SAI
UPDATE courses SET ... WHERE id = ?;  -- Thiếu kiểm tra instructor_id
```

#### 2. **Student chỉ xem được khóa đã đăng ký:**
```sql
-- ✅ ĐÚNG
SELECT l.* FROM lessons l
JOIN courses c ON l.course_id = c.id
JOIN enrollments e ON c.id = e.course_id
WHERE l.id = ? AND e.student_id = ?;

-- ❌ SAI
SELECT * FROM lessons WHERE id = ?;  -- Thiếu kiểm tra enrollment
```

#### 3. **Chỉ hiển thị khóa học approved cho public:**
```sql
-- ✅ ĐÚNG (Student)
SELECT * FROM courses WHERE status = 'approved';

-- ✅ ĐÚNG (Instructor xem khóa của mình)
SELECT * FROM courses WHERE instructor_id = ?;  -- Thấy tất cả status

-- ✅ ĐÚNG (Admin)
SELECT * FROM courses;  -- Thấy tất cả
```

#### 4. **Cascade Delete được xử lý tự động:**
- Xóa `courses` → Tự động xóa `lessons` và `enrollments`
- Xóa `lessons` → Tự động xóa `materials`
- Xóa `users` (student) → Tự động xóa `enrollments`

---

## 🔍 QUERY MẪU THEO TÍNH NĂNG

### 📊 Dashboard Instructor:
```sql
-- Thống kê tổng quan
SELECT 
    (SELECT COUNT(*) FROM courses WHERE instructor_id = ?) as total_courses,
    (SELECT COUNT(DISTINCT e.student_id) 
     FROM enrollments e 
     JOIN courses c ON e.course_id = c.id 
     WHERE c.instructor_id = ?) as total_students,
    (SELECT COUNT(*) FROM courses WHERE instructor_id = ? AND status = 'approved') as approved_courses,
    (SELECT COUNT(*) FROM courses WHERE instructor_id = ? AND status = 'pending') as pending_courses;
```

### 📚 Trang chủ Student:
```sql
-- Khóa học nổi bật (nhiều học viên nhất)
SELECT c.*, cat.name as category_name, u.fullname as instructor_name,
       COUNT(e.id) as student_count,
       (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
FROM courses c
LEFT JOIN categories cat ON c.category_id = cat.id
LEFT JOIN users u ON c.instructor_id = u.id
LEFT JOIN enrollments e ON c.id = e.course_id
WHERE c.status = 'approved'
GROUP BY c.id
ORDER BY student_count DESC
LIMIT 10;
```

### 🎓 Tiến độ học tập:
```sql
-- Tính progress dựa trên số bài học (giả sử có bảng lesson_progress)
-- Nếu chưa có, dùng logic đơn giản:
UPDATE enrollments 
SET progress = 50  -- Cập nhật thủ công
WHERE course_id = ? AND student_id = ?;

-- Hoặc có thể tạo bảng lesson_progress:
-- CREATE TABLE lesson_progress (
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     enrollment_id INT,
--     lesson_id INT,
--     completed TINYINT DEFAULT 0,
--     completed_at DATETIME
-- );
```

---

## ⚠️ LƯU Ý QUAN TRỌNG

### 1. **Foreign Key Constraints:**
- `ON DELETE CASCADE`: Tự động xóa bản ghi con
- `ON DELETE SET NULL`: Đặt NULL khi xóa cha (dùng cho instructor_id, category_id)

### 2. **Index để tăng tốc:**
```sql
-- Thêm index cho các cột hay query
CREATE INDEX idx_courses_instructor ON courses(instructor_id);
CREATE INDEX idx_courses_status ON courses(status);
CREATE INDEX idx_enrollments_student ON enrollments(student_id);
CREATE INDEX idx_enrollments_course ON enrollments(course_id);
CREATE INDEX idx_lessons_course ON lessons(course_id);
CREATE INDEX idx_materials_lesson ON materials(lesson_id);
```

### 3. **Validation trong PHP:**
- Kiểm tra `role` trước khi cho phép thao tác
- Validate file upload (extension, size, MIME type)
- Sanitize input để chống XSS, SQL Injection
- Sử dụng Prepared Statements

### 4. **Dữ liệu mẫu:**
```sql
-- Admin account (đã có trong database.sql)
-- Username: admin
-- Password: 123456
-- Email: admin@example.com
```

---

## 🚀 QUERY TỐI ƯU HÓA

### Sử dụng JOIN thay vì Subquery khi có thể:
```sql
-- ❌ CHẬM
SELECT c.*, 
       (SELECT name FROM categories WHERE id = c.category_id) as category_name,
       (SELECT fullname FROM users WHERE id = c.instructor_id) as instructor_name
FROM courses c;

-- ✅ NHANH
SELECT c.*, cat.name as category_name, u.fullname as instructor_name
FROM courses c
LEFT JOIN categories cat ON c.category_id = cat.id
LEFT JOIN users u ON c.instructor_id = u.id;
```

### Pagination:
```sql
-- Phân trang cho danh sách khóa học
SELECT * FROM courses 
WHERE status = 'approved'
ORDER BY created_at DESC
LIMIT 10 OFFSET 0;  -- Page 1
-- LIMIT 10 OFFSET 10; -- Page 2
```

---

## 📖 TÀI LIỆU THAM KHẢO

- **MySQL Foreign Keys:** https://dev.mysql.com/doc/refman/8.0/en/create-table-foreign-keys.html
- **PDO PHP:** https://www.php.net/manual/en/book.pdo.php
- **SQL Injection Prevention:** https://www.php.net/manual/en/security.database.sql-injection.php

---

**Cập nhật lần cuối:** 06/12/2025  
**Tài liệu này là guideline chính thức cho team phát triển**

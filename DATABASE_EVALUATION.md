# ĐÁNH GIÁ VÀ ĐỀ XUẤT CẢI TIẾN DATABASE

**Ngày đánh giá:** 06/12/2025  
**Database hiện tại:** `onlinecourse`  
**Phiên bản:** v1.0 (Basic)

---

## 📊 ĐÁNH GIÁ TỔNG QUAN

### ✅ **ĐIỂM MẠNH** (Những gì đã tốt)

1. **Cấu trúc cơ bản đầy đủ:**
   - 6 bảng chính đã cover được các chức năng cốt lõi
   - Quan hệ giữa các bảng hợp lý (users → courses → lessons → materials)

2. **Foreign Key được thiết lập đúng:**
   - `ON DELETE CASCADE` cho dữ liệu phụ thuộc (lessons, materials, enrollments)
   - `ON DELETE SET NULL` cho dữ liệu tham chiếu (instructor_id, category_id)

3. **Charset UTF8MB4:**
   - Hỗ trợ emoji và tiếng Việt đầy đủ ✅

4. **Có dữ liệu mẫu:**
   - Admin account sẵn sàng
   - 2 categories mẫu

---

## ⚠️ **VẤN ĐỀ NGHIÊM TRỌNG** (Phải sửa ngay)

### 🔴 1. **THIẾU UNIQUE CONSTRAINT** trên enrollments
**Vấn đề:** Học viên có thể đăng ký cùng 1 khóa học **nhiều lần**!

```sql
-- Hiện tại: Cho phép
INSERT INTO enrollments (course_id, student_id) VALUES (1, 5);
INSERT INTO enrollments (course_id, student_id) VALUES (1, 5); -- Trùng lặp!
```

**Giải pháp:**
```sql
ALTER TABLE enrollments 
ADD UNIQUE KEY unique_enrollment (course_id, student_id);
```

---

### 🔴 2. **THIẾU INDEX** cho performance
**Vấn đề:** Query sẽ **rất chậm** khi có nhiều dữ liệu

**Các cột cần index:**
- `courses.instructor_id` - Query "khóa học của tôi" rất nhiều
- `courses.status` - Lọc theo trạng thái
- `lessons.course_id` - Join liên tục
- `enrollments.student_id` - "Khóa học của tôi"
- `enrollments.course_id` - "Học viên trong khóa"

---

### 🔴 3. **KHÔNG KIỂM TRA RÀNG BUỘC DỮ LIỆU**

**Vấn đề:**
- `progress` có thể là số âm hoặc > 100
- `role` có thể là 999 (không hợp lệ)
- `status` có thể là bất kỳ string nào
- `price` có thể là số âm

**Hiện tại không có CHECK constraint!**

---

### 🟡 4. **THIẾU CÁC BẢNG QUAN TRỌNG**

#### 🔸 **Bảng `lesson_progress`** (Theo dõi tiến độ chi tiết)
**Vấn đề:** Hiện tại chỉ có `enrollments.progress` (tổng thể), không biết:
- Học viên đã xem bài nào chưa?
- Bài nào đã hoàn thành?
- Khi nào hoàn thành?

**Thiếu bảng này → Không tính được tiến độ chính xác!**

#### 🔸 **Bảng `reviews`** (Đánh giá khóa học)
**Vấn đề:** Không có:
- Rating (1-5 sao)
- Comment/nhận xét
- Ảnh hưởng UX rất lớn!

#### 🔸 **Bảng `payments`** (Lịch sử thanh toán)
**Vấn đề:** Nếu khóa học có phí:
- Không theo dõi được ai đã thanh toán
- Không có lịch sử giao dịch
- Không quản lý được doanh thu

#### 🔸 **Bảng `notifications`** (Thông báo)
**Vấn đề:** Không có hệ thống thông báo:
- Khóa học được duyệt
- Có học viên mới
- Bài học mới được thêm

---

## 🔍 **VẤN ĐỀ VỪA PHẢI** (Nên sửa)

### 🟡 5. **Thiếu soft delete**
**Vấn đề:** Khi xóa dữ liệu → **MẤT VĨNH VIỄN**

**Giải pháp:** Thêm cột `deleted_at`:
```sql
ALTER TABLE courses ADD COLUMN deleted_at DATETIME NULL;
ALTER TABLE lessons ADD COLUMN deleted_at DATETIME NULL;
```

---

### 🟡 6. **Thiếu metadata cho courses**
**Thiếu các trường:**
- `total_students` - Tổng học viên (denormalize để query nhanh)
- `rating` - Điểm đánh giá trung bình
- `total_reviews` - Số lượt đánh giá
- `view_count` - Lượt xem
- `is_featured` - Khóa học nổi bật
- `language` - Ngôn ngữ (Tiếng Việt/English)
- `requirements` - Yêu cầu trước khi học
- `what_you_will_learn` - Học được gì (JSON array)

---

### 🟡 7. **Thiếu thông tin giảng viên**
Bảng `users` quá đơn giản cho giảng viên:
- Không có `bio` (Giới thiệu)
- Không có `expertise` (Chuyên môn)
- Không có `social_links` (Facebook, LinkedIn)
- Không có `total_students` (Tổng học viên đã dạy)

---

### 🟡 8. **Lessons thiếu duration**
**Vấn đề:** Không biết bài học dài bao lâu
- Không tính được tổng thời lượng khóa học
- Không hiển thị được "Bài học 15 phút"

**Cần thêm:** `duration_minutes INT`

---

### 🟡 9. **Materials thiếu file_size**
**Vấn đề:** Không biết file nặng bao nhiêu
- Không cảnh báo người dùng trước khi tải
- Không giới hạn dung lượng upload

**Cần thêm:** `file_size INT` (bytes)

---

### 🟡 10. **Không có bảng FAQ**
**Vấn đề:** Câu hỏi thường gặp cho từng khóa học

---

## 🟢 **CẢI TIẾN NÂNG CAO** (Tùy chọn)

### 🔹 11. **Bảng `certificates`** (Chứng chỉ hoàn thành)
```sql
CREATE TABLE certificates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT,
    certificate_code VARCHAR(50) UNIQUE,
    issued_date DATETIME,
    pdf_path VARCHAR(255),
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id)
);
```

### 🔹 12. **Bảng `quizzes` và `quiz_results`** (Bài kiểm tra)
```sql
CREATE TABLE quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT,
    title VARCHAR(255),
    passing_score INT DEFAULT 70,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id)
);

CREATE TABLE quiz_questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT,
    question TEXT,
    correct_answer TEXT,
    options JSON,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

CREATE TABLE quiz_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT,
    student_id INT,
    score INT,
    answers JSON,
    attempted_at DATETIME,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);
```

### 🔹 13. **Bảng `discussions`** (Thảo luận/Q&A)
```sql
CREATE TABLE discussions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT,
    user_id INT,
    parent_id INT NULL, -- For replies
    content TEXT,
    created_at DATETIME,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### 🔹 14. **Bảng `coupons`** (Mã giảm giá)
```sql
CREATE TABLE coupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE,
    discount_percent INT,
    discount_amount DECIMAL(10,2),
    valid_from DATETIME,
    valid_to DATETIME,
    max_uses INT,
    used_count INT DEFAULT 0
);
```

### 🔹 15. **Bảng `course_tags`** (Tags cho khóa học)
```sql
CREATE TABLE tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE
);

CREATE TABLE course_tags (
    course_id INT,
    tag_id INT,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);
```

### 🔹 16. **Bảng `wishlists`** (Danh sách yêu thích)
```sql
CREATE TABLE wishlists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    course_id INT,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);
```

### 🔹 17. **Full-text search**
```sql
ALTER TABLE courses ADD FULLTEXT INDEX ft_search (title, description);
```

---

## 📝 **FILE SQL CẢI TIẾN ĐỀ XUẤT**

### 🔥 **PHIÊN BẢN 2.0 - BẮT BUỘC PHẢI CÓ**

```sql
-- ============================================
-- DATABASE v2.0 - ESSENTIAL IMPROVEMENTS
-- ============================================

-- 1. Thêm UNIQUE constraint cho enrollments
ALTER TABLE enrollments 
ADD UNIQUE KEY unique_enrollment (course_id, student_id);

-- 2. Thêm INDEX cho performance
CREATE INDEX idx_courses_instructor ON courses(instructor_id);
CREATE INDEX idx_courses_status ON courses(status);
CREATE INDEX idx_courses_category ON courses(category_id);
CREATE INDEX idx_enrollments_student ON enrollments(student_id);
CREATE INDEX idx_enrollments_course ON enrollments(course_id);
CREATE INDEX idx_lessons_course ON lessons(course_id);
CREATE INDEX idx_materials_lesson ON materials(lesson_id);

-- 3. Thêm CHECK constraints (MySQL 8.0+)
ALTER TABLE enrollments 
ADD CONSTRAINT chk_progress CHECK (progress >= 0 AND progress <= 100);

ALTER TABLE courses
ADD CONSTRAINT chk_price CHECK (price >= 0);

ALTER TABLE users
ADD CONSTRAINT chk_role CHECK (role IN (0, 1, 2));

ALTER TABLE users
ADD CONSTRAINT chk_status CHECK (status IN (0, 1));

-- 4. Bảng lesson_progress (BẮT BUỘC)
CREATE TABLE lesson_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT NOT NULL,
    lesson_id INT NOT NULL,
    completed TINYINT DEFAULT 0 COMMENT '0: chưa xem, 1: đã hoàn thành',
    completed_at DATETIME NULL,
    last_position INT DEFAULT 0 COMMENT 'Vị trí video (giây)',
    UNIQUE KEY unique_progress (enrollment_id, lesson_id),
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

-- 5. Bảng reviews (BẮT BUỘC cho UX)
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL COMMENT '1-5 sao',
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_review (course_id, user_id),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CHECK (rating >= 1 AND rating <= 5)
);

-- 6. Thêm cột vào courses
ALTER TABLE courses 
ADD COLUMN total_students INT DEFAULT 0,
ADD COLUMN rating DECIMAL(3,2) DEFAULT 0.00 COMMENT 'Điểm TB (0-5)',
ADD COLUMN total_reviews INT DEFAULT 0,
ADD COLUMN view_count INT DEFAULT 0,
ADD COLUMN is_featured TINYINT DEFAULT 0,
ADD COLUMN language VARCHAR(20) DEFAULT 'vi' COMMENT 'vi, en',
ADD COLUMN requirements TEXT NULL,
ADD COLUMN what_you_will_learn TEXT NULL;

-- 7. Thêm cột vào lessons
ALTER TABLE lessons
ADD COLUMN duration_minutes INT DEFAULT 0 COMMENT 'Thời lượng bài học';

-- 8. Thêm cột vào materials
ALTER TABLE materials
ADD COLUMN file_size INT DEFAULT 0 COMMENT 'Kích thước file (bytes)';

-- 9. Thêm thông tin giảng viên
ALTER TABLE users
ADD COLUMN bio TEXT NULL COMMENT 'Giới thiệu bản thân',
ADD COLUMN expertise VARCHAR(255) NULL COMMENT 'Chuyên môn',
ADD COLUMN social_links JSON NULL COMMENT 'Facebook, LinkedIn, etc',
ADD COLUMN total_students INT DEFAULT 0 COMMENT 'Tổng học viên đã dạy';

-- 10. Soft delete
ALTER TABLE courses 
ADD COLUMN deleted_at DATETIME NULL;

ALTER TABLE lessons 
ADD COLUMN deleted_at DATETIME NULL;

-- 11. Bảng notifications
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL COMMENT 'course_approved, new_student, etc',
    title VARCHAR(255) NOT NULL,
    content TEXT,
    is_read TINYINT DEFAULT 0,
    related_id INT NULL COMMENT 'ID của course, enrollment, etc',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_notifications_user ON notifications(user_id, is_read);

-- 12. Trigger tự động cập nhật total_students
DELIMITER $$
CREATE TRIGGER after_enrollment_insert 
AFTER INSERT ON enrollments
FOR EACH ROW
BEGIN
    UPDATE courses 
    SET total_students = (SELECT COUNT(*) FROM enrollments WHERE course_id = NEW.course_id)
    WHERE id = NEW.course_id;
END$$

CREATE TRIGGER after_enrollment_delete
AFTER DELETE ON enrollments
FOR EACH ROW
BEGIN
    UPDATE courses 
    SET total_students = (SELECT COUNT(*) FROM enrollments WHERE course_id = OLD.course_id)
    WHERE id = OLD.course_id;
END$$
DELIMITER ;

-- 13. Trigger tự động cập nhật rating
DELIMITER $$
CREATE TRIGGER after_review_insert
AFTER INSERT ON reviews
FOR EACH ROW
BEGIN
    UPDATE courses SET
        rating = (SELECT AVG(rating) FROM reviews WHERE course_id = NEW.course_id),
        total_reviews = (SELECT COUNT(*) FROM reviews WHERE course_id = NEW.course_id)
    WHERE id = NEW.course_id;
END$$

CREATE TRIGGER after_review_update
AFTER UPDATE ON reviews
FOR EACH ROW
BEGIN
    UPDATE courses SET
        rating = (SELECT AVG(rating) FROM reviews WHERE course_id = NEW.course_id),
        total_reviews = (SELECT COUNT(*) FROM reviews WHERE course_id = NEW.course_id)
    WHERE id = NEW.course_id;
END$$

CREATE TRIGGER after_review_delete
AFTER DELETE ON reviews
FOR EACH ROW
BEGIN
    UPDATE courses SET
        rating = COALESCE((SELECT AVG(rating) FROM reviews WHERE course_id = OLD.course_id), 0),
        total_reviews = (SELECT COUNT(*) FROM reviews WHERE course_id = OLD.course_id)
    WHERE id = OLD.course_id;
END$$
DELIMITER ;
```

---

### 🌟 **PHIÊN BẢN 3.0 - NÂNG CAO (Tùy chọn)**

```sql
-- ============================================
-- DATABASE v3.0 - ADVANCED FEATURES
-- ============================================

-- 1. Bảng certificates
CREATE TABLE certificates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT NOT NULL,
    certificate_code VARCHAR(50) UNIQUE NOT NULL,
    issued_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    pdf_path VARCHAR(255),
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE
);

-- 2. Hệ thống Quiz
CREATE TABLE quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    passing_score INT DEFAULT 70,
    time_limit_minutes INT DEFAULT 30,
    attempts_allowed INT DEFAULT 3,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

CREATE TABLE quiz_questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT NOT NULL,
    question TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer') DEFAULT 'multiple_choice',
    options JSON NULL COMMENT 'Array of options for multiple choice',
    correct_answer TEXT NOT NULL,
    points INT DEFAULT 1,
    order_num INT DEFAULT 0,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE quiz_attempts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT NOT NULL,
    student_id INT NOT NULL,
    score DECIMAL(5,2),
    answers JSON,
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    submitted_at DATETIME NULL,
    passed TINYINT DEFAULT 0,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Hệ thống thảo luận
CREATE TABLE discussions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT NOT NULL,
    user_id INT NOT NULL,
    parent_id INT NULL,
    content TEXT NOT NULL,
    is_instructor_reply TINYINT DEFAULT 0,
    upvotes INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES discussions(id) ON DELETE CASCADE
);

-- 4. Hệ thống coupon
CREATE TABLE coupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('percent', 'fixed') DEFAULT 'percent',
    discount_value DECIMAL(10,2) NOT NULL,
    min_purchase DECIMAL(10,2) DEFAULT 0,
    max_uses INT DEFAULT NULL COMMENT 'NULL = unlimited',
    used_count INT DEFAULT 0,
    valid_from DATETIME NOT NULL,
    valid_to DATETIME NOT NULL,
    applicable_courses JSON NULL COMMENT 'NULL = all courses',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CHECK (discount_value > 0)
);

CREATE TABLE coupon_usage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    coupon_id INT NOT NULL,
    user_id INT NOT NULL,
    enrollment_id INT NOT NULL,
    discount_amount DECIMAL(10,2),
    used_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id)
);

-- 5. Hệ thống payment
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    enrollment_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    coupon_id INT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    final_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) COMMENT 'momo, vnpay, bank_transfer',
    transaction_id VARCHAR(255) UNIQUE,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    paid_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id),
    FOREIGN KEY (coupon_id) REFERENCES coupons(id)
);

-- 6. Tags
CREATE TABLE tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE course_tags (
    course_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- 7. Wishlist
CREATE TABLE wishlists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- 8. Course FAQ
CREATE TABLE course_faqs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    order_num INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- 9. Activity Log
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL COMMENT 'login, view_course, complete_lesson',
    entity_type VARCHAR(50) COMMENT 'course, lesson, quiz',
    entity_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_activity_user ON activity_logs(user_id, created_at);

-- 10. Full-text search
ALTER TABLE courses ADD FULLTEXT INDEX ft_search (title, description);
ALTER TABLE users ADD FULLTEXT INDEX ft_users (fullname, username, email);
```

---

## 📊 **SO SÁNH PHIÊN BẢN**

| Tính năng | v1.0 (Hiện tại) | v2.0 (Essential) | v3.0 (Advanced) |
|-----------|-----------------|------------------|-----------------|
| **Bảng cơ bản** | ✅ 6 bảng | ✅ 6 bảng | ✅ 6 bảng |
| **Index** | ❌ Không | ✅ Có | ✅ Có |
| **Unique constraints** | ❌ Không | ✅ Có | ✅ Có |
| **CHECK constraints** | ❌ Không | ✅ Có | ✅ Có |
| **Lesson progress** | ❌ Không | ✅ Có | ✅ Có |
| **Reviews** | ❌ Không | ✅ Có | ✅ Có |
| **Notifications** | ❌ Không | ✅ Có | ✅ Có |
| **Soft delete** | ❌ Không | ✅ Có | ✅ Có |
| **Triggers** | ❌ Không | ✅ Có (rating, students) | ✅ Có |
| **Certificates** | ❌ Không | ❌ Không | ✅ Có |
| **Quizzes** | ❌ Không | ❌ Không | ✅ Có |
| **Discussions** | ❌ Không | ❌ Không | ✅ Có |
| **Payments** | ❌ Không | ❌ Không | ✅ Có |
| **Coupons** | ❌ Không | ❌ Không | ✅ Có |
| **Tags** | ❌ Không | ❌ Không | ✅ Có |
| **Wishlist** | ❌ Không | ❌ Không | ✅ Có |
| **Activity Log** | ❌ Không | ❌ Không | ✅ Có |
| **Full-text search** | ❌ Không | ❌ Không | ✅ Có |

---

## 🎯 **KHUYẾN NGHỊ**

### 🔥 **BẮT BUỘC PHẢI LÀM (v2.0):**
1. ✅ Thêm UNIQUE constraint cho enrollments
2. ✅ Thêm tất cả INDEX
3. ✅ Thêm bảng `lesson_progress`
4. ✅ Thêm bảng `reviews`
5. ✅ Thêm cột metadata vào courses, lessons, materials
6. ✅ Thêm CHECK constraints

**Lý do:** Không có những thứ này → Ứng dụng sẽ có BUG nghiêm trọng và chậm!

---

### 🌟 **NÊN LÀM (v3.0):**
1. Certificates (quan trọng cho marketing)
2. Quizzes (tăng engagement)
3. Discussions (tạo cộng đồng)
4. Payments (nếu có thu phí)
5. Notifications (UX tốt hơn)

**Lý do:** Tăng trải nghiệm người dùng và giá trị sản phẩm

---

### 💡 **CÓ THỂ BỎ QUA:**
- Coupons (nếu không làm marketing)
- Activity logs (nếu không cần phân tích)
- Tags (nếu categories đủ)
- Wishlist (tính năng phụ)

---

## 📝 **KẾ HOẠCH TRIỂN KHAI**

### **Tuần 1:** Nâng cấp lên v2.0
```bash
# Backup database
mysqldump -u root -p onlinecourse > backup_v1.sql

# Chạy script v2.0
mysql -u root -p onlinecourse < database_v2.0.sql

# Test lại toàn bộ tính năng
```

### **Tuần 2-3:** Cập nhật code PHP
- Sửa Models để sử dụng bảng mới
- Implement lesson_progress tracking
- Implement review system
- Test kỹ lưỡng

### **Sau đó:** Xem xét v3.0
- Ưu tiên: Quizzes → Certificates → Discussions
- Triển khai từng tính năng một

---

## ⚠️ **LƯU Ý QUAN TRỌNG**

1. **Backup trước khi migrate!**
2. **Test trên local trước**
3. **Không chạy script v3.0 nếu chưa hoàn thành v2.0**
4. **CHECK constraints cần MySQL 8.0+**
5. **Triggers có thể ảnh hưởng performance** - monitor!

---

## 🎓 **KẾT LUẬN**

**Database hiện tại (v1.0):**
- ⚠️ **Đánh giá: 5/10** - Quá sơ sài cho production
- ❌ **Thiếu nhiều tính năng thiết yếu**
- ❌ **Không có index → Sẽ chậm**
- ❌ **Không có ràng buộc → Sẽ có bug**
- ✅ **Nhưng cấu trúc cơ bản OK** - dễ nâng cấp

**Khuyến nghị:**
- 🔴 **PHẢI nâng cấp lên v2.0** ngay lập tức
- 🟡 **NÊN làm v3.0** nếu có thời gian
- ✅ **Ưu tiên:** Index → Lesson Progress → Reviews → Triggers

**Thời gian ước tính:**
- v2.0: 4-6 giờ (script + testing + update code)
- v3.0: 15-20 giờ (nhiều tính năng phức tạp)

---

**Tài liệu này được tạo để hỗ trợ team đánh giá và quyết định nâng cấp database.**

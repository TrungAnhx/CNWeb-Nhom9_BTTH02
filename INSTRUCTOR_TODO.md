# TODO LIST - PHẦN GIẢNG VIÊN (INSTRUCTOR)
**Dự án:** Website Quản lý Khóa học Trực tuyến  
**Thành viên phụ trách:** Thành viên 2  
**Branch:** `feature/instructor-management`  
**Ngày cập nhật:** 06/12/2025

---

## 📋 TỔNG QUAN CÔNG VIỆC

Phần Instructor chịu trách nhiệm về **luồng nghiệp vụ chính** của hệ thống - cho phép giảng viên tạo và quản lý khóa học, bài học, tài liệu, và theo dõi học viên.

**Mức độ hoàn thành hiện tại:** ~5% (Chỉ có cấu trúc thư mục và file rỗng)

---

## ✅ CÁC TÍNH NĂNG CẦN HOÀN THÀNH

### 🎯 1. DASHBOARD GIẢNG VIÊN
**File:** `views/instructor/dashboard.php`, `controllers/CourseController.php`

#### Công việc cần làm:
- [ ] **Hiển thị thống kê tổng quan:**
  - Tổng số khóa học đang dạy
  - Tổng số học viên đã đăng ký
  - Khóa học có nhiều học viên nhất
  - Doanh thu (nếu có tính phí)

- [ ] **Danh sách khóa học nhanh:**
  - Top 5 khóa học gần đây
  - Trạng thái từng khóa (draft/pending/approved)
  - Link nhanh đến quản lý bài học

#### File cần tạo/sửa:
- `controllers/CourseController.php` → Method `instructorDashboard()`
- `models/Course.php` → Methods:
  - `getCoursesByInstructor($instructorId)`
  - `countStudentsByCourse($courseId)`
  - `getInstructorStatistics($instructorId)`
- `views/instructor/dashboard.php` → Giao diện dashboard

---

### 🎯 2. QUẢN LÝ KHÓA HỌC (COURSE MANAGEMENT)

#### 2.1. XEM DANH SÁCH KHÓA HỌC
**File:** `views/instructor/my_courses.php`

- [ ] **Hiển thị bảng danh sách khóa học của giảng viên:**
  - ID, Tiêu đề, Danh mục, Giá, Trạng thái, Số học viên
  - Nút: Xem chi tiết, Sửa, Xóa
  - Nút: Quản lý bài học, Xem học viên

- [ ] **Tìm kiếm và lọc:**
  - Tìm theo tên khóa học
  - Lọc theo trạng thái (draft/pending/approved)
  - Lọc theo danh mục

#### File cần tạo/sửa:
- `controllers/CourseController.php` → Method `myCourses()`
- `models/Course.php` → Method `getCoursesByInstructor($instructorId, $filters)`
- `views/instructor/my_courses.php` → Giao diện danh sách

---

#### 2.2. TẠO KHÓA HỌC MỚI (CREATE)
**File:** `views/instructor/course/create.php`

- [ ] **Form tạo khóa học:**
  - Tiêu đề (title) - required
  - Mô tả (description) - textarea
  - Danh mục (category_id) - dropdown từ DB
  - Giá (price) - number
  - Thời lượng (duration_weeks)
  - Cấp độ (level) - select: Beginner/Intermediate/Advanced
  - Upload ảnh thumbnail (image)

- [ ] **Validation:**
  - Kiểm tra các trường bắt buộc
  - Validate định dạng ảnh (jpg, png, gif)
  - Giới hạn kích thước ảnh (max 2MB)

- [ ] **Xử lý upload ảnh:**
  - Lưu vào thư mục `uploads/courses/`
  - Đổi tên file để tránh trùng lặp
  - Lưu đường dẫn vào DB

- [ ] **Lưu vào Database:**
  - Insert vào bảng `courses`
  - Gán `instructor_id` = user đang đăng nhập
  - Mặc định `status` = 'draft'

#### File cần tạo/sửa:
- `controllers/CourseController.php` → Methods:
  - `create()` - Hiển thị form
  - `store()` - Xử lý POST request
- `models/Course.php` → Method `createCourse($data)`
- `models/Category.php` → Method `getAllCategories()`
- `views/instructor/course/create.php` → Form HTML

---

#### 2.3. SỬA KHÓA HỌC (EDIT/UPDATE)
**File:** `views/instructor/course/edit.php`

- [ ] **Load dữ liệu khóa học:**
  - Lấy course theo ID từ URL
  - Kiểm tra quyền: Chỉ giảng viên sở hữu mới được sửa
  - Hiển thị form với dữ liệu đã điền sẵn

- [ ] **Form giống Create nhưng:**
  - Có ảnh cũ hiển thị
  - Cho phép upload ảnh mới (optional)
  - Nếu upload ảnh mới → Xóa ảnh cũ

- [ ] **Xử lý cập nhật:**
  - Validate dữ liệu
  - Update vào DB
  - Redirect về danh sách khóa học

#### File cần tạo/sửa:
- `controllers/CourseController.php` → Methods:
  - `edit($id)` - Hiển thị form edit
  - `update($id)` - Xử lý POST update
- `models/Course.php` → Methods:
  - `getCourseById($id)`
  - `updateCourse($id, $data)`
- `views/instructor/course/edit.php` → Form HTML

---

#### 2.4. XÓA KHÓA HỌC (DELETE)
**File:** `controllers/CourseController.php`

- [ ] **Xử lý xóa khóa học:**
  - Kiểm tra quyền sở hữu
  - Xóa ảnh thumbnail khỏi server
  - Xóa tất cả bài học liên quan (CASCADE)
  - Xóa tất cả enrollments liên quan
  - Xóa course khỏi DB

- [ ] **Xác nhận trước khi xóa:**
  - JavaScript confirm dialog
  - Hiển thị cảnh báo: "Xóa khóa học sẽ xóa tất cả bài học và dữ liệu học viên"

#### File cần tạo/sửa:
- `controllers/CourseController.php` → Method `delete($id)`
- `models/Course.php` → Method `deleteCourse($id)`
- `assets/js/script.js` → Confirm dialog

---

### 🎯 3. QUẢN LÝ BÀI HỌC (LESSON MANAGEMENT)

#### 3.1. XEM DANH SÁCH BÀI HỌC
**File:** `views/instructor/lessons/manage.php`

- [ ] **Hiển thị danh sách bài học của 1 khóa học:**
  - Breadcrumb: Khóa học > Quản lý bài học
  - Hiển thị thông tin khóa học ở đầu trang
  - Bảng danh sách bài học: STT, Tiêu đề, Video URL, Số tài liệu
  - Nút: Thêm bài học mới, Sửa, Xóa
  - Cho phép sắp xếp thứ tự bài học (drag & drop hoặc input order)

#### File cần tạo/sửa:
- `controllers/LessonController.php` → Method `manage($courseId)`
- `models/Lesson.php` → Method `getLessonsByCourse($courseId)`
- `views/instructor/lessons/manage.php` → Giao diện danh sách

---

#### 3.2. TẠO BÀI HỌC MỚI (CREATE LESSON)
**File:** `views/instructor/lessons/create.php`

- [ ] **Form tạo bài học:**
  - Chọn khóa học (nếu không có courseId từ URL)
  - Tiêu đề bài học (title) - required
  - Nội dung (content) - WYSIWYG editor (TinyMCE/CKEditor)
  - URL video (video_url) - YouTube/Vimeo embed
  - Thứ tự bài học (order_num)

- [ ] **Validation:**
  - Kiểm tra title không rỗng
  - Validate URL video (optional nhưng nếu có phải đúng format)

- [ ] **Lưu vào Database:**
  - Insert vào bảng `lessons`
  - Gán `course_id`
  - Auto tăng `order_num` nếu không nhập

#### File cần tạo/sửa:
- `controllers/LessonController.php` → Methods:
  - `create($courseId)` - Hiển thị form
  - `store()` - Xử lý POST
- `models/Lesson.php` → Method `createLesson($data)`
- `views/instructor/lessons/create.php` → Form HTML

---

#### 3.3. SỬA BÀI HỌC (EDIT LESSON)
**File:** `views/instructor/lessons/edit.php`

- [ ] **Load dữ liệu bài học:**
  - Lấy lesson theo ID
  - Kiểm tra quyền: Giảng viên phải sở hữu khóa học
  - Hiển thị form với dữ liệu đã điền

- [ ] **Xử lý cập nhật:**
  - Validate dữ liệu
  - Update vào DB

#### File cần tạo/sửa:
- `controllers/LessonController.php` → Methods:
  - `edit($id)` - Hiển thị form
  - `update($id)` - Xử lý POST
- `models/Lesson.php` → Methods:
  - `getLessonById($id)`
  - `updateLesson($id, $data)`
- `views/instructor/lessons/edit.php` → Form HTML

---

#### 3.4. XÓA BÀI HỌC (DELETE LESSON)

- [ ] **Xử lý xóa bài học:**
  - Kiểm tra quyền
  - Xóa tất cả materials liên quan
  - Xóa lesson khỏi DB

#### File cần tạo/sửa:
- `controllers/LessonController.php` → Method `delete($id)`
- `models/Lesson.php` → Method `deleteLesson($id)`

---

### 🎯 4. QUẢN LÝ TÀI LIỆU (MATERIALS MANAGEMENT)

#### 4.1. UPLOAD TÀI LIỆU ĐÍNH KÈM
**File:** `views/instructor/materials/upload.php`

- [ ] **Form upload tài liệu:**
  - Chọn bài học (lesson_id) - dropdown
  - Upload file (PDF, DOC, PPT, ZIP)
  - Mô tả tài liệu (optional)

- [ ] **Validation:**
  - Kiểm tra định dạng file cho phép
  - Giới hạn kích thước (max 10MB)
  - Kiểm tra giảng viên có quyền với lesson này

- [ ] **Xử lý upload:**
  - Lưu file vào `uploads/materials/`
  - Đổi tên file để tránh trùng
  - Insert vào bảng `materials`

- [ ] **Hiển thị danh sách tài liệu đã upload:**
  - Bảng: Tên file, Loại, Kích thước, Ngày upload
  - Nút: Tải xuống, Xóa

#### File cần tạo/sửa:
- `controllers/LessonController.php` hoặc tạo `MaterialController.php`
- Methods:
  - `uploadMaterial()` - Hiển thị form
  - `storeaterial()` - Xử lý POST upload
  - `deleteMaterial($id)` - Xóa tài liệu
- `models/Material.php` → Methods:
  - `createMaterial($data)`
  - `getMaterialsByLesson($lessonId)`
  - `deleteMaterial($id)`
- `views/instructor/materials/upload.php` → Form HTML

---

### 🎯 5. QUẢN LÝ HỌC VIÊN (STUDENTS IN COURSES)

#### 5.1. XEM DANH SÁCH HỌC VIÊN
**File:** `views/instructor/students/list.php`

- [ ] **Hiển thị học viên đã đăng ký khóa học:**
  - Chọn khóa học để xem học viên
  - Bảng: Tên học viên, Email, Ngày đăng ký, Tiến độ (%), Trạng thái
  - Tìm kiếm học viên theo tên/email

- [ ] **Chi tiết tiến độ học viên:**
  - Click vào học viên → Xem bài học nào đã hoàn thành
  - Biểu đồ tiến độ

#### File cần tạo/sửa:
- `controllers/EnrollmentController.php` (hoặc thêm vào CourseController)
- Method `getStudentsByCourse($courseId)`
- `models/Enrollment.php` → Methods:
  - `getEnrollmentsByCourse($courseId)`
  - `getStudentProgress($enrollmentId)`
- `views/instructor/students/list.php` → Giao diện danh sách

---

## 🔧 CÁC MODEL CẦN HOÀN THIỆN

### 📁 models/Course.php

```php
class Course {
    // Properties
    private $db;
    
    // Constructor
    public function __construct($db) { ... }
    
    // Methods cần implement:
    - getAllCourses()
    - getCourseById($id)
    - getCoursesByInstructor($instructorId)
    - getCoursesByCategory($categoryId)
    - createCourse($data)
    - updateCourse($id, $data)
    - deleteCourse($id)
    - searchCourses($keyword)
    - getInstructorStatistics($instructorId)
    - countStudentsByCourse($courseId)
}
```

### 📁 models/Lesson.php

```php
class Lesson {
    private $db;
    
    // Methods cần implement:
    - getLessonsByCourse($courseId)
    - getLessonById($id)
    - createLesson($data)
    - updateLesson($id, $data)
    - deleteLesson($id)
    - updateLessonOrder($lessonId, $newOrder)
}
```

### 📁 models/Material.php

```php
class Material {
    private $db;
    
    // Methods cần implement:
    - getMaterialsByLesson($lessonId)
    - getMaterialById($id)
    - createMaterial($data)
    - deleteMaterial($id)
}
```

### 📁 models/Enrollment.php

```php
class Enrollment {
    private $db;
    
    // Methods cần implement:
    - getEnrollmentsByCourse($courseId)
    - getEnrollmentsByStudent($studentId)
    - createEnrollment($courseId, $studentId)
    - updateProgress($enrollmentId, $progress)
    - getStudentProgress($enrollmentId)
}
```

---

## 🎨 CÁC VIEW CẦN HOÀN THIỆN

### 📁 views/instructor/

| File | Trạng thái | Mô tả |
|------|-----------|-------|
| `dashboard.php` | ❌ Chưa làm | Dashboard tổng quan |
| `my_courses.php` | ❌ Chưa làm | Danh sách khóa học |
| `course/create.php` | ❌ Chưa làm | Form tạo khóa học |
| `course/edit.php` | ❌ Chưa làm | Form sửa khóa học |
| `course/manage.php` | ❌ Chưa làm | Chi tiết quản lý 1 khóa |
| `lessons/manage.php` | ❌ Chưa làm | Danh sách bài học |
| `lessons/create.php` | ❌ Chưa làm | Form tạo bài học |
| `lessons/edit.php` | ❌ Chưa làm | Form sửa bài học |
| `materials/upload.php` | ❌ Chưa làm | Upload tài liệu |
| `students/list.php` | ❌ Chưa làm | Danh sách học viên |

---

## 🛠️ CONTROLLER CẦN HOÀN THIỆN

### 📁 controllers/CourseController.php

**Các method cần implement:**

```php
class CourseController {
    // Hiển thị trang chủ khóa học (cho học viên)
    public function index() { ... }
    
    // Chi tiết khóa học (cho học viên)
    public function detail($id) { ... }
    
    // ===== INSTRUCTOR =====
    // Dashboard giảng viên
    public function instructorDashboard() { ... }
    
    // Danh sách khóa học của giảng viên
    public function myCourses() { ... }
    
    // Form tạo khóa học
    public function create() { ... }
    
    // Xử lý tạo khóa học (POST)
    public function store() { ... }
    
    // Form sửa khóa học
    public function edit($id) { ... }
    
    // Xử lý cập nhật (POST)
    public function update($id) { ... }
    
    // Xóa khóa học
    public function delete($id) { ... }
}
```

### 📁 controllers/LessonController.php

**Các method cần implement:**

```php
class LessonController {
    // Quản lý bài học của 1 khóa
    public function manage($courseId) { ... }
    
    // Form tạo bài học
    public function create($courseId) { ... }
    
    // Xử lý tạo bài học (POST)
    public function store() { ... }
    
    // Form sửa bài học
    public function edit($id) { ... }
    
    // Xử lý cập nhật (POST)
    public function update($id) { ... }
    
    // Xóa bài học
    public function delete($id) { ... }
    
    // Upload tài liệu
    public function uploadMaterial() { ... }
    
    // Lưu tài liệu (POST)
    public function storeMaterial() { ... }
    
    // Xóa tài liệu
    public function deleteMaterial($id) { ... }
}
```

---

## 🔐 BẢO MẬT & KIỂM TRA QUYỀN

### Các điểm cần lưu ý:

- [ ] **Middleware kiểm tra đăng nhập:**
  - Tất cả trang instructor phải check session
  - Redirect về login nếu chưa đăng nhập

- [ ] **Kiểm tra role:**
  - Chỉ user có `role = 1` (Giảng viên) mới truy cập
  - Hoặc `role = 2` (Admin)

- [ ] **Kiểm tra quyền sở hữu:**
  - Khi sửa/xóa course: Check `instructor_id = $_SESSION['user_id']`
  - Khi sửa/xóa lesson: Check qua bảng courses
  - Ngăn giảng viên A sửa khóa học của giảng viên B

- [ ] **Validate input:**
  - Sử dụng prepared statements để chống SQL Injection
  - Sanitize dữ liệu từ form
  - Validate file upload (extension, size, MIME type)

- [ ] **CSRF Protection:**
  - Thêm token vào mọi form POST
  - Verify token trước khi xử lý

---

## 📂 CẤU TRÚC THƯ MỤC CẦN TẠO

```
uploads/
├── courses/          # Ảnh thumbnail khóa học
├── materials/        # Tài liệu đính kèm
└── avatars/          # Ảnh đại diện user (nếu có)
```

**Lưu ý:** Thêm file `.htaccess` trong `uploads/` để bảo vệ:

```apache
# Chặn execute PHP trong thư mục uploads
<FilesMatch "\.php$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
```

---

## 🧪 TESTING & VALIDATION

### Test cases cần kiểm tra:

#### Course Management:
- [ ] Tạo khóa học thành công
- [ ] Tạo khóa học thiếu trường bắt buộc → Hiển thị lỗi
- [ ] Upload ảnh không đúng định dạng → Hiển thị lỗi
- [ ] Sửa khóa học của người khác → Bị chặn
- [ ] Xóa khóa học → Xóa cả bài học và enrollments

#### Lesson Management:
- [ ] Tạo bài học thành công
- [ ] Thứ tự bài học tự động tăng
- [ ] Sửa bài học thành công
- [ ] Xóa bài học → Xóa cả materials

#### Materials:
- [ ] Upload tài liệu thành công
- [ ] Upload file quá lớn → Bị chặn
- [ ] Upload file không cho phép (.exe, .php) → Bị chặn
- [ ] Tải xuống tài liệu hoạt động

#### Students:
- [ ] Hiển thị đúng danh sách học viên của khóa học
- [ ] Tiến độ tính toán chính xác

---

## 📅 ƯỚC LƯỢNG THỜI GIAN

| Công việc | Thời gian | Độ ưu tiên |
|-----------|-----------|------------|
| **1. Setup Models (Course, Lesson, Material, Enrollment)** | 3-4 giờ | 🔴 Cao |
| **2. Dashboard Instructor** | 2 giờ | 🟡 Trung bình |
| **3. CRUD Courses** | 4-5 giờ | 🔴 Cao |
| **4. CRUD Lessons** | 3-4 giờ | 🔴 Cao |
| **5. Upload Materials** | 2-3 giờ | 🟡 Trung bình |
| **6. Quản lý Học viên** | 2-3 giờ | 🟢 Thấp |
| **7. Testing & Bug fixes** | 2-3 giờ | 🔴 Cao |
| **8. UI/UX Polish** | 2 giờ | 🟢 Thấp |
| **TỔNG** | **20-26 giờ** | |

---

## 🚀 LỘ TRÌNH THỰC HIỆN ĐỀ XUẤT

### TUẦN 1: Core Functions

**Ngày 1-2:**
- [ ] Setup Models: Course, Lesson, Material
- [ ] Tạo các method cơ bản trong Models
- [ ] Test kết nối database

**Ngày 3-4:**
- [ ] CRUD Courses (tạo, sửa, xóa, danh sách)
- [ ] Upload ảnh thumbnail
- [ ] Validation & Security

**Ngày 5:**
- [ ] Dashboard Instructor
- [ ] My Courses listing
- [ ] Testing

### TUẦN 2: Advanced Functions

**Ngày 1-2:**
- [ ] CRUD Lessons
- [ ] Quản lý thứ tự bài học

**Ngày 3:**
- [ ] Upload Materials
- [ ] Download & Delete materials

**Ngày 4:**
- [ ] Quản lý học viên
- [ ] Hiển thị tiến độ

**Ngày 5:**
- [ ] Testing tổng thể
- [ ] Bug fixes
- [ ] UI improvements

---

## 📚 TÀI NGUYÊN THAM KHẢO

### Thư viện/Tools cần dùng:

1. **WYSIWYG Editor:**
   - TinyMCE: https://www.tiny.cloud/
   - CKEditor: https://ckeditor.com/

2. **File Upload Library:**
   - PHP native functions
   - Hoặc: Dropzone.js (frontend)

3. **Chart/Statistics:**
   - Chart.js: https://www.chartjs.org/
   - Google Charts

4. **CSS Framework:**
   - Bootstrap 5 (nếu chưa có)
   - TailwindCSS (tùy chọn)

### Documentation:

- PHP File Upload: https://www.php.net/manual/en/features.file-upload.php
- PHP PDO: https://www.php.net/manual/en/book.pdo.php
- YouTube Embed API: https://developers.google.com/youtube/iframe_api_reference

---

## ⚠️ LƯU Ý QUAN TRỌNG

1. **Không tự ý sửa database schema** mà không báo Leader
2. **Commit code thường xuyên** với message rõ ràng
3. **Merge từ main** trước khi bắt đầu làm việc mỗi ngày
4. **Tạo backup database** trước khi test tính năng xóa
5. **Không push file upload lên Git** (thêm `uploads/` vào `.gitignore`)
6. **Test trên nhiều trình duyệt** (Chrome, Firefox, Edge)
7. **Responsive design** - Đảm bảo mobile-friendly

---

## 📞 HỖ TRỢ & LIÊN HỆ

- **Gặp lỗi database:** Liên hệ Thành viên 1 (Leader)
- **Cần API từ phần Student:** Liên hệ Thành viên 3
- **Vấn đề về UI/Layout:** Phối hợp với cả team

---

## ✅ CHECKLIST HOÀN THÀNH

### Phase 1: Core Setup
- [ ] Models hoàn thành và tested
- [ ] Database connection hoạt động
- [ ] CRUD Courses hoàn chỉnh
- [ ] Upload ảnh hoạt động

### Phase 2: Lessons & Materials
- [ ] CRUD Lessons hoạt động
- [ ] Upload materials thành công
- [ ] Download materials hoạt động

### Phase 3: Advanced Features
- [ ] Dashboard có dữ liệu thống kê
- [ ] Quản lý học viên hiển thị đúng
- [ ] Tất cả form có validation

### Phase 4: Polish & Testing
- [ ] Tất cả tính năng tested
- [ ] Không còn bug nghiêm trọng
- [ ] UI responsive và đẹp mắt
- [ ] Code được comment đầy đủ
- [ ] Ready to merge vào main

---

**Chúc bạn code hiệu quả! 💪**

*Tài liệu này sẽ được cập nhật theo tiến độ thực tế.*

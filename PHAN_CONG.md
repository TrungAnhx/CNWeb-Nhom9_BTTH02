# PHÂN CÔNG CÔNG VIỆC DỰ ÁN WEBSITE QUẢN LÝ KHÓA HỌC

**Mô hình:** MVC (Model-View-Controller)  
**Nhóm:** 3 Thành viên  
**Vai trò:**
1.  **Thành viên 1 (Leader):** Core System, Auth, Admin, Layout chung.
2.  **Thành viên 2:** Giảng viên (Instructor) & Quản lý nội dung khóa học.
3.  **Thành viên 3:** Học viên (Student) & Hiển thị trang chủ.

---

## 👤 THÀNH VIÊN 1 (LEADER) - KHUNG HỆ THỐNG & QUẢN TRỊ
*Chịu trách nhiệm thiết lập nền tảng, bảo mật và quản lý người dùng.*

**Công việc cụ thể:**
1.  **Hệ thống (Core):**
    *   Setup Git, Database, cấu trúc thư mục (Đã làm bước đầu).
    *   Xây dựng **Layout chung** (Header, Footer, Sidebar) trong `views/layouts/`.
    *   Viết CSS cơ bản (`assets/css/style.css`) để chia layout.
2.  **Module Xác thực (Auth):**
    *   Đăng ký, Đăng nhập, Đăng xuất (`AuthController.php`).
    *   Bảo mật: Password Hashing, Session Management.
    *   Middleware: Kiểm tra quyền (Role-based access control) - Chặn học viên vào trang admin.
3.  **Module Quản trị viên (Admin):**
    *   Dashboard thống kê cơ bản (`AdminController.php`).
    *   Quản lý Người dùng (`views/admin/users`): Xem, Xóa/Khóa tài khoản.
    *   Quản lý Danh mục (`views/admin/categories`): Tạo, sửa, xóa danh mục khóa học.

**File phụ trách chính:**
*   `controllers/AuthController.php`, `controllers/AdminController.php`
*   `models/User.php`, `models/Category.php`
*   `views/layouts/*`, `views/auth/*`, `views/admin/*`

---

## 👤 THÀNH VIÊN 2 - GIẢNG VIÊN & NỘI DUNG (BACKEND HEAVY)
*Chịu trách nhiệm về luồng nghiệp vụ chính: Tạo ra khóa học.*

**Công việc cụ thể:**
1.  **Dashboard Giảng viên:** Hiển thị các khóa học mình đang dạy.
2.  **Quản lý Khóa học (Courses - CRUD):**
    *   Tạo khóa học mới, Sửa thông tin, Upload ảnh thumbnail.
    *   Xóa khóa học.
3.  **Quản lý Bài học (Lessons & Materials):**
    *   Thêm bài học vào khóa học (Tiêu đề, Video URL, Nội dung).
    *   Upload tài liệu đính kèm (PDF, Doc) cho bài học (`views/instructor/materials`).
4.  **Quản lý Học viên (của Giảng viên):**
    *   Xem danh sách ai đã mua khóa học của mình.

**File phụ trách chính:**
*   `controllers/CourseController.php` (Phần create/edit/delete), `controllers/LessonController.php`
*   `models/Course.php`, `models/Lesson.php`, `models/Material.php`
*   `views/instructor/*`

---

## 👤 THÀNH VIÊN 3 - HỌC VIÊN & TRANG CHỦ (FRONTEND ORIENTED)
*Chịu trách nhiệm về luồng người dùng cuối và trải nghiệm học tập.*

**Công việc cụ thể:**
1.  **Trang chủ (Home):**
    *   Hiển thị danh sách khóa học nổi bật/mới nhất.
    *   Tìm kiếm khóa học, Lọc theo danh mục.
2.  **Trang Chi tiết & Học tập:**
    *   Trang chi tiết khóa học (Giá, mô tả, danh sách bài học).
    *   **Đăng ký khóa học (Enroll):** Xử lý logic lưu vào bảng `enrollments`.
    *   Màn hình vào học: Xem video, xem tài liệu.
3.  **Dashboard Học viên:**
    *   Xem "Khóa học của tôi".
    *   Cập nhật tiến độ học tập (ví dụ: bấm "Hoàn thành" bài học).

**File phụ trách chính:**
*   `controllers/HomeController.php`, `controllers/EnrollmentController.php`, `controllers/CourseController.php` (Phần view/index)
*   `models/Enrollment.php`
*   `views/home/*`, `views/courses/*`, `views/student/*`

---

## 📝 QUY TẮC LÀM VIỆC CHUNG
1.  **Git Branch:**
    *   Leader: `main`, `feature/auth-admin`
    *   TV2: `feature/instructor-management`
    *   TV3: `feature/student-experience`
2.  **Database:** Không ai được tự ý sửa cấu trúc bảng mà không báo Leader. Nếu cần thêm cột, viết câu lệnh `ALTER TABLE` gửi vào nhóm chat.
3.  **Views:** TV2 và TV3 `include` file header/footer do Leader tạo để đồng bộ giao diện.

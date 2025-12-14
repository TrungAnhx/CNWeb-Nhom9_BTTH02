# 🚀 HƯỚNG DẪN TEST VỚI XAMPP

## 📋 BƯỚC 1: Copy sang XAMPP

```bash
# Copy toàn bộ folder này sang:
C:\xampp\htdocs\your-project-name\
```

Ví dụ: `C:\xampp\htdocs\cnweb\`

---

## 📋 BƯỚC 2: Import Database

### Cách 1: Qua phpMyAdmin
1. Mở phpMyAdmin: `http://localhost/phpmyadmin`
2. Import các file SQL theo thứ tự:
   - `database.sql` (Cấu trúc database + admin + categories)
   - `dummy_users.sql` (Dữ liệu users)
   - `lesson_import.sql` (Courses + Lessons + Materials + Enrollments)
3. Database name: `onlinecourse`

### Cách 2: Qua Command Line (Nhanh hơn)
```powershell
cd C:\xampp\mysql\bin

# 1. Import cấu trúc database
.\mysql.exe -u root onlinecourse < "d:\CNWeb-Nhom9_BTTH02\database.sql"

# 2. Import users
.\mysql.exe -u root onlinecourse < "d:\CNWeb-Nhom9_BTTH02\dummy_users.sql"

# 3. Import courses, lessons, materials, enrollments
.\mysql.exe -u root onlinecourse < "d:\CNWeb-Nhom9_BTTH02\lesson_import.sql"
```

**Lưu ý:** Phải import đúng thứ tự vì có foreign key dependencies!

---

## 📋 BƯỚC 3: Kiểm tra Config

File `config/Database.php` đã OK:
```php
$host = "localhost";
$db_name = "onlinecourse";
$username = "root";
$password = "";  // XAMPP default không có password
```

---

## 🎯 BƯỚC 4: Test

### Test 1: Kiểm tra Database
```
http://localhost/your-project/test_db.php
```
Hoặc:
```
http://localhost/your-project/?controller=test&action=db
```

### Test 2: Fake Login
```
http://localhost/your-project/fake_login.php
```

### Test 3: My Courses (sau khi login)
```
http://localhost/your-project/?controller=course&action=myCourses
```

---

## 🔧 URLs HỖ TRỢ

### 1. **Với Query String** (luôn hoạt động):
- Test DB: `?controller=test&action=db`
- My Courses: `?controller=course&action=myCourses`
- Fake Login: `fake_login.php`

### 2. **Clean URLs** (nếu có .htaccess):
- Test DB: `/test-db`
- My Courses: `/instructor/my-courses`
- Fake Login: `/fake-login`

---

## ⚠️ LƯU Ý

1. **XAMPP cần Apache đang chạy**
2. **MySQL/MariaDB đang chạy**
3. **Đã import database.sql**
4. **Project folder không có khoảng trắng**

---

## 🐛 NẾU CÓ LỖI

### Lỗi: "Connection error"
→ Check XAMPP MySQL đang chạy

### Lỗi: "Table doesn't exist"
→ Import lại database.sql

### Lỗi: "Controller not found"
→ Check đường dẫn URL có đúng không

### Lỗi: CSS không load
→ Check đường dẫn trong HTML (phải là relative path)

---

## 📝 THÔNG TIN ĐĂNG NHẬP (sau khi import database)

Dùng `fake_login.php` để chọn user:
- **Admin** (role=2)
- **Instructor** (role=1) ← Chọn cái này để test
- **Student** (role=0)

---

## ✅ CHECKLIST

- [ ] Copy project sang `C:\xampp\htdocs\`
- [ ] Start Apache + MySQL trong XAMPP
- [ ] Import `database.sql` vào phpMyAdmin
- [ ] Truy cập `http://localhost/your-project/test_db.php`
- [ ] Test `fake_login.php`
- [ ] Login as Instructor
- [ ] Xem `?controller=course&action=myCourses`

---

**Good luck! 🎉**

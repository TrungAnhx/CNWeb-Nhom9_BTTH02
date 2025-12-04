-- Tạo CSDL
CREATE DATABASE IF NOT EXISTS onlinecourse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE onlinecourse;

-- Bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255),
    avatar VARCHAR(255),
    role INT DEFAULT 0 COMMENT '0: học viên, 1: giảng viên, 2: quản trị viên',
    status TINYINT DEFAULT 1 COMMENT '1: hoạt động, 0: bị khóa',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng categories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng courses
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT,
    category_id INT,
    price DECIMAL(10,2) DEFAULT 0,
    duration_weeks INT,
    level VARCHAR(50) COMMENT 'Beginner, Intermediate, Advanced',
    image VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending' COMMENT 'draft, pending, approved',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Bảng enrollments (Đăng ký học)
CREATE TABLE IF NOT EXISTS enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    student_id INT,
    enrolled_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'active' COMMENT 'active, completed, dropped',
    progress INT DEFAULT 0 COMMENT 'Phần trăm hoàn thành (0-100)',
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng lessons (Bài học)
CREATE TABLE IF NOT EXISTS lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT,
    video_url VARCHAR(255),
    order_num INT DEFAULT 0 COMMENT 'Thứ tự bài học',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Bảng materials (Tài liệu đính kèm)
CREATE TABLE IF NOT EXISTS materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT,
    filename VARCHAR(255),
    file_path VARCHAR(255),
    file_type VARCHAR(50) COMMENT 'pdf, doc, ppt, v.v.',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);

-- Insert dữ liệu mẫu (Admin mặc định: admin/123456)
-- Pass: 123456 (đã hash bằng bcrypt)
INSERT INTO users (username, email, password, fullname, role, status) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên', 2, 1);

-- Insert mẫu danh mục
INSERT INTO categories (name, description) VALUES 
('Lập trình Web', 'Các khóa học về Frontend, Backend'),
('Khoa học dữ liệu', 'Python, AI, Machine Learning');
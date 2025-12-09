-- Thêm 10 Giảng viên (Role = 1)
INSERT INTO users (username, email, password, fullname, role, status) VALUES 
('gv1', 'gv1@example.com', '123456', 'Nguyễn Văn Giảng A', 1, 1),
('gv2', 'gv2@example.com', '123456', 'Trần Thị Giảng B', 1, 1),
('gv3', 'gv3@example.com', '123456', 'Lê Văn Giảng C', 1, 1),
('gv4', 'gv4@example.com', '123456', 'Phạm Thị Giảng D', 1, 1),
('gv5', 'gv5@example.com', '123456', 'Hoàng Văn Giảng E', 1, 1),
('gv6', 'gv6@example.com', '123456', 'Đỗ Thị Giảng F', 1, 1),
('gv7', 'gv7@example.com', '123456', 'Bùi Văn Giảng G', 1, 1),
('gv8', 'gv8@example.com', '123456', 'Vũ Thị Giảng H', 1, 1),
('gv9', 'gv9@example.com', '123456', 'Đặng Văn Giảng I', 1, 1),
('gv10', 'gv10@example.com', '123456', 'Ngô Thị Giảng K', 1, 1);

-- Thêm 10 Học viên (Role = 0)
INSERT INTO users (username, email, password, fullname, role, status) VALUES 
('hv1', 'hv1@example.com', '123456', 'Nguyễn Sinh Viên 1', 0, 1),
('hv2', 'hv2@example.com', '123456', 'Trần Sinh Viên 2', 0, 1),
('hv3', 'hv3@example.com', '123456', 'Lê Sinh Viên 3', 0, 1),
('hv4', 'hv4@example.com', '123456', 'Phạm Sinh Viên 4', 0, 1),
('hv5', 'hv5@example.com', '123456', 'Hoàng Sinh Viên 5', 0, 1),
('hv6', 'hv6@example.com', '123456', 'Đỗ Sinh Viên 6', 0, 1),
('hv7', 'hv7@example.com', '123456', 'Bùi Sinh Viên 7', 0, 1),
('hv8', 'hv8@example.com', '123456', 'Vũ Sinh Viên 8', 0, 1),
('hv9', 'hv9@example.com', '123456', 'Đặng Sinh Viên 9', 0, 1),
('hv10', 'hv10@example.com', '123456', 'Ngô Sinh Viên 10', 0, 1);

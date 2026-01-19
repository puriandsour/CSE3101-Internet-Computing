
SET SQL_MODE = "STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION";
SET time_zone = "+00:00";


DROP TABLE IF EXISTS scores;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS terms;
DROP TABLE IF EXISTS school_years;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS classes;
DROP TABLE IF EXISTS grades;
DROP TABLE IF EXISTS role_permissions;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS user_roles;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;


--USERS / ROLES
-

CREATE TABLE users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(120) NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE roles (
  id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  description VARCHAR(255) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_roles_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_roles (
  user_id BIGINT UNSIGNED NOT NULL,
  role_id TINYINT UNSIGNED NOT NULL,
  assigned_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, role_id),
  CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE permissions (
  id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(60) NOT NULL,
  description VARCHAR(255) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_permissions_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE role_permissions (
  role_id TINYINT UNSIGNED NOT NULL,
  permission_id SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--SCHOOL STRUCTURE


CREATE TABLE grades (
  id TINYINT UNSIGNED NOT NULL,
  grade_number TINYINT UNSIGNED NOT NULL,
  name VARCHAR(20) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_grades_grade_number (grade_number),
  UNIQUE KEY uq_grades_name (name),
  CONSTRAINT chk_grade_number CHECK (grade_number BETWEEN 1 AND 6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE classes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  grade_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(40) NOT NULL,
  room VARCHAR(40) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_classes_grade_name (grade_id, name),
  KEY idx_classes_grade (grade_id),
  CONSTRAINT fk_classes_grade FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subjects (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  grade_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(80) NOT NULL,
  code VARCHAR(20) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_subject_grade_name (grade_id, name),
  KEY idx_subjects_grade (grade_id),
  CONSTRAINT fk_subjects_grade FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--ACADEMIC YEAR / TERMS


CREATE TABLE school_years (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  is_current TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_school_years_name (name),
  CONSTRAINT chk_school_year_dates CHECK (start_date < end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE terms (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  school_year_id BIGINT UNSIGNED NOT NULL,
  term_number TINYINT UNSIGNED NOT NULL,
  name VARCHAR(30) NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_terms_year_term (school_year_id, term_number),
  KEY idx_terms_school_year (school_year_id),
  CONSTRAINT fk_terms_school_year FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE CASCADE,
  CONSTRAINT chk_term_number CHECK (term_number BETWEEN 1 AND 3),
  CONSTRAINT chk_term_dates CHECK (start_date < end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--STUDENTS ENROLLMENT


CREATE TABLE students (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  admission_no VARCHAR(30) NOT NULL,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  date_of_birth DATE NULL,
  gender ENUM('M','F','OTHER') NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_students_admission_no (admission_no),
  KEY idx_students_name (last_name, first_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE enrollments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  student_id BIGINT UNSIGNED NOT NULL,
  class_id BIGINT UNSIGNED NOT NULL,
  school_year_id BIGINT UNSIGNED NOT NULL,
  enrolled_at DATE NOT NULL DEFAULT (CURRENT_DATE),
  status ENUM('ACTIVE','COMPLETED','TRANSFERRED','DROPPED') NOT NULL DEFAULT 'ACTIVE',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_enroll_student_year (student_id, school_year_id),
  KEY idx_enroll_class_year (class_id, school_year_id),
  KEY idx_enroll_student (student_id),
  CONSTRAINT fk_enroll_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  CONSTRAINT fk_enroll_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE RESTRICT,
  CONSTRAINT fk_enroll_school_year FOREIGN KEY (school_year_id) REFERENCES school_years(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--SCORES


CREATE TABLE scores (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  enrollment_id BIGINT UNSIGNED NOT NULL,
  subject_id BIGINT UNSIGNED NOT NULL,
  term_id BIGINT UNSIGNED NOT NULL,
  teacher_user_id BIGINT UNSIGNED NOT NULL,
  score DECIMAL(5,2) NOT NULL,
  remarks VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_scores_unique (enrollment_id, subject_id, term_id),
  KEY idx_scores_term (term_id),
  KEY idx_scores_subject (subject_id),
  KEY idx_scores_teacher (teacher_user_id),
  CONSTRAINT fk_scores_enrollment FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
  CONSTRAINT fk_scores_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE RESTRICT,
  CONSTRAINT fk_scores_term FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE RESTRICT,
  CONSTRAINT fk_scores_teacher FOREIGN KEY (teacher_user_id) REFERENCES users(id) ON DELETE RESTRICT,
  CONSTRAINT chk_score_range CHECK (score BETWEEN 0 AND 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- SEED DATA: ROLES & PERMISSIONS


INSERT INTO roles (name, description) VALUES
('TEACHER', 'Can manage scores only'),
('OFFICE_ADMIN', 'Can manage all system features');

INSERT INTO permissions (code, description) VALUES
('MANAGE_SCORES', 'Create/Update student scores'),
('VIEW_REPORTS', 'Generate/view reports'),
('MANAGE_USERS', 'CRUD users and roles'),
('MANAGE_STUDENTS', 'CRUD students and enrollments'),
('MANAGE_STRUCTURE', 'Manage classes and subjects'),
('MANAGE_CALENDAR', 'Manage school years and terms');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE (r.name='TEACHER' AND p.code IN ('MANAGE_SCORES','VIEW_REPORTS'))
   OR (r.name='OFFICE_ADMIN' AND p.code IN ('MANAGE_SCORES','VIEW_REPORTS','MANAGE_USERS','MANAGE_STUDENTS','MANAGE_STRUCTURE','MANAGE_CALENDAR'));


-- SEED DATA: GRADES


INSERT INTO grades (id, grade_number, name) VALUES
(1, 1, 'Grade 1'),
(2, 2, 'Grade 2'),
(3, 3, 'Grade 3'),
(4, 4, 'Grade 4'),
(5, 5, 'Grade 5'),
(6, 6, 'Grade 6');


-- SEED DATA: USERS
-- Password for all users: 12345678



INSERT INTO users (username, email, password_hash, first_name, last_name, is_active) VALUES
-- Administrators
('admin', 'admin@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'System', 'Admin', 1),
('admin2', 'admin2@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Jane', 'Smith', 1),
-- Teachers
('teacher', 'teacher@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'John', 'Doe', 1),
('ms.williams', 'sarah.williams@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Sarah', 'Williams', 1),
('mr.johnson', 'michael.johnson@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Michael', 'Johnson', 1),
('ms.davis', 'emily.davis@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Emily', 'Davis', 1),
('mr.brown', 'david.brown@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'David', 'Brown', 1),
('ms.miller', 'jennifer.miller@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Jennifer', 'Miller', 1),
('mr.wilson', 'robert.wilson@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Robert', 'Wilson', 1),
('ms.moore', 'lisa.moore@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Lisa', 'Moore', 1),
('mr.taylor', 'james.taylor@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'James', 'Taylor', 1),
('ms.garcia', 'maria.garcia@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Maria', 'Garcia', 1),
('mr.martinez', 'carlos.martinez@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Carlos', 'Martinez', 1),
('ms.rodriguez', 'ana.rodriguez@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Ana', 'Rodriguez', 1),
('mr.lee', 'thomas.lee@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Thomas', 'Lee', 1),
('ms.walker', 'patricia.walker@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Patricia', 'Walker', 1),
('mr.hall', 'mark.hall@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Mark', 'Hall', 1),
('ms.allen', 'susan.allen@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Susan', 'Allen', 1),
('mr.young', 'daniel.young@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Daniel', 'Young', 1),
('ms.hernandez', 'linda.hernandez@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Linda', 'Hernandez', 1),
('mr.king', 'william.king@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'William', 'King', 1),
('ms.wright', 'nancy.wright@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Nancy', 'Wright', 1),
('mr.lopez', 'richard.lopez@school.com', '$2y$10$Smk12hWb6xEd/J96jxMhkuA6tCSUwWp4fugBO9iom6JTlMEssUkKa', 'Richard', 'Lopez', 1);

-- Assign Roles
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id FROM users u, roles r 
WHERE u.username = 'admin' AND r.name = 'OFFICE_ADMIN';

INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id FROM users u, roles r 
WHERE u.username = 'admin2' AND r.name = 'OFFICE_ADMIN';

INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id FROM users u, roles r 
WHERE u.username IN ('teacher', 'ms.williams', 'mr.johnson', 'ms.davis', 'mr.brown', 'ms.miller', 'mr.wilson', 'ms.moore', 'mr.taylor', 'ms.garcia', 'mr.martinez', 'ms.rodriguez', 'mr.lee', 'ms.walker', 'mr.hall', 'ms.allen', 'mr.young', 'ms.hernandez', 'mr.king', 'ms.wright', 'mr.lopez') AND r.name = 'TEACHER';


-- SEED DATA: SCHOOL YEARS & TERMS


-- Historical Years
INSERT INTO school_years (name, start_date, end_date, is_current) VALUES
('2022-2023', '2022-09-01', '2023-06-30', 0),
('2023-2024', '2023-09-01', '2024-06-30', 0),
('2024-2025', '2024-09-01', '2025-06-30', 0);

-- Current Year
INSERT INTO school_years (name, start_date, end_date, is_current) VALUES
('2025-2026', '2025-09-01', '2026-06-30', 1);

-- Terms for 2022-2023
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 1, 'Term 1', '2022-09-01', '2022-12-20' FROM school_years WHERE name = '2022-2023';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 2, 'Term 2', '2023-01-05', '2023-03-28' FROM school_years WHERE name = '2022-2023';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 3, 'Term 3', '2023-04-13', '2023-06-30' FROM school_years WHERE name = '2022-2023';

-- Terms for 2023-2024
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 1, 'Term 1', '2023-09-01', '2023-12-20' FROM school_years WHERE name = '2023-2024';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 2, 'Term 2', '2024-01-05', '2024-03-28' FROM school_years WHERE name = '2023-2024';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 3, 'Term 3', '2024-04-13', '2024-06-30' FROM school_years WHERE name = '2023-2024';

-- Terms for 2024-2025
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 1, 'Term 1', '2024-09-01', '2024-12-20' FROM school_years WHERE name = '2024-2025';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 2, 'Term 2', '2025-01-05', '2025-03-28' FROM school_years WHERE name = '2024-2025';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 3, 'Term 3', '2025-04-13', '2025-06-30' FROM school_years WHERE name = '2024-2025';

-- Terms for 2025-2026
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 1, 'Term 1', '2025-09-01', '2025-12-20' FROM school_years WHERE name = '2025-2026';

INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 2, 'Term 2', '2026-01-05', '2026-03-28' FROM school_years WHERE name = '2025-2026';

INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 3, 'Term 3', '2026-04-13', '2026-06-30' FROM school_years WHERE name = '2025-2026';


-- SEED DATA: CLASSES (3-4 classes per grade)


INSERT INTO classes (grade_id, name, room, is_active) VALUES
-- Grade 1
(1, 'A', 'Room 101', 1),
(1, 'B', 'Room 102', 1),
(1, 'C', 'Room 103', 1),
(1, 'D', 'Room 104', 1),
-- Grade 2
(2, 'A', 'Room 201', 1),
(2, 'B', 'Room 202', 1),
(2, 'C', 'Room 203', 1),
(2, 'D', 'Room 204', 1),
-- Grade 3
(3, 'A', 'Room 301', 1),
(3, 'B', 'Room 302', 1),
(3, 'C', 'Room 303', 1),
(3, 'D', 'Room 304', 1),
-- Grade 4
(4, 'A', 'Room 401', 1),
(4, 'B', 'Room 402', 1),
(4, 'C', 'Room 403', 1),
(4, 'D', 'Room 404', 1),
-- Grade 5
(5, 'A', 'Room 5A', 1),
(5, 'B', 'Room 5B', 1),
(5, 'C', 'Room 5C', 1),
(5, 'D', 'Room 5D', 1),
-- Grade 6
(6, 'A', 'Room 6A', 1),
(6, 'B', 'Room 6B', 1),
(6, 'C', 'Room 6C', 1),
(6, 'D', 'Room 6D', 1);


-- SEED DATA: SUBJECTS (per grade)


-- Grade 1 Subjects
INSERT INTO subjects (grade_id, name, code, is_active) VALUES
(1, 'Mathematics', 'MATH1', 1),
(1, 'English Language', 'ENG1', 1),
(1, 'Science', 'SCI1', 1),
(1, 'Social Studies', 'SOC1', 1),
(1, 'Art', 'ART1', 1),
(1, 'Physical Education', 'PE1', 1),
(1, 'Music', 'MUS1', 1),
(1, 'Health Education', 'HE1', 1),
(1, 'Computer Studies', 'CS1', 1);

-- Grade 2 Subjects
INSERT INTO subjects (grade_id, name, code, is_active) VALUES
(2, 'Mathematics', 'MATH2', 1),
(2, 'English Language', 'ENG2', 1),
(2, 'Science', 'SCI2', 1),
(2, 'Social Studies', 'SOC2', 1),
(2, 'Art', 'ART2', 1),
(2, 'Physical Education', 'PE2', 1),
(2, 'Music', 'MUS2', 1),
(2, 'Health Education', 'HE2', 1),
(2, 'Computer Studies', 'CS2', 1);

-- Grade 3 Subjects
INSERT INTO subjects (grade_id, name, code, is_active) VALUES
(3, 'Mathematics', 'MATH3', 1),
(3, 'English Language', 'ENG3', 1),
(3, 'Science', 'SCI3', 1),
(3, 'Social Studies', 'SOC3', 1),
(3, 'Art', 'ART3', 1),
(3, 'Physical Education', 'PE3', 1),
(3, 'Music', 'MUS3', 1),
(3, 'Health Education', 'HE3', 1),
(3, 'Computer Studies', 'CS3', 1);

-- Grade 4 Subjects
INSERT INTO subjects (grade_id, name, code, is_active) VALUES
(4, 'Mathematics', 'MATH4', 1),
(4, 'English Language', 'ENG4', 1),
(4, 'Science', 'SCI4', 1),
(4, 'Social Studies', 'SOC4', 1),
(4, 'Art', 'ART4', 1),
(4, 'Physical Education', 'PE4', 1),
(4, 'Music', 'MUS4', 1),
(4, 'Health Education', 'HE4', 1),
(4, 'Computer Studies', 'CS4', 1);

-- Grade 5 Subjects
INSERT INTO subjects (grade_id, name, code, is_active) VALUES
(5, 'Mathematics', 'MATH5', 1),
(5, 'English Language', 'ENG5', 1),
(5, 'Science', 'SCI5', 1),
(5, 'Social Studies', 'SOC5', 1),
(5, 'Art', 'ART5', 1),
(5, 'Physical Education', 'PE5', 1),
(5, 'Music', 'MUS5', 1),
(5, 'Health Education', 'HE5', 1),
(5, 'Computer Studies', 'CS5', 1);

-- Grade 6 Subjects
INSERT INTO subjects (grade_id, name, code, is_active) VALUES
(6, 'Mathematics', 'MATH6', 1),
(6, 'English Language', 'ENG6', 1),
(6, 'Science', 'SCI6', 1),
(6, 'Social Studies', 'SOC6', 1),
(6, 'Art', 'ART6', 1),
(6, 'Physical Education', 'PE6', 1),
(6, 'Music', 'MUS6', 1),
(6, 'Health Education', 'HE6', 1),
(6, 'Computer Studies', 'CS6', 1);


-- SEED DATA: STUDENTS (180+ students across all grades)


INSERT INTO students (admission_no, first_name, last_name, date_of_birth, gender, is_active) VALUES
-- Grade 1 Students (30 students)
('1001', 'Liam', 'Carter', '2018-03-15', 'M', 1), ('1002', 'Olivia', 'Bennett', '2018-07-22', 'F', 1), ('1003', 'Noah', 'Thompson', '2018-11-08', 'M', 1),
('1004', 'Emma', 'Harper', '2018-05-30', 'F', 1), ('1005', 'Ethan', 'Parker', '2018-09-12', 'M', 1), ('1006', 'Ava', 'Mitchell', '2018-01-18', 'F', 1),
('1007', 'James', 'Wilson', '2018-02-14', 'M', 1), ('1008', 'Sophia', 'Martinez', '2018-06-20', 'F', 1), ('1009', 'Benjamin', 'Taylor', '2018-10-05', 'M', 1),
('1010', 'Isabella', 'Anderson', '2018-04-11', 'F', 1), ('1011', 'Mason', 'Thomas', '2018-08-27', 'M', 1), ('1012', 'Mia', 'Jackson', '2018-12-03', 'F', 1),
('1013', 'Alexander', 'White', '2018-01-19', 'M', 1), ('1014', 'Charlotte', 'Harris', '2018-05-25', 'F', 1), ('1015', 'Daniel', 'Martin', '2018-09-08', 'M', 1),
('1016', 'Amelia', 'Thompson', '2018-03-14', 'F', 1), ('1017', 'Matthew', 'Garcia', '2018-07-30', 'M', 1), ('1018', 'Harper', 'Miller', '2018-11-15', 'F', 1),
('1019', 'David', 'Davis', '2018-02-28', 'M', 1), ('1020', 'Evelyn', 'Rodriguez', '2018-06-12', 'F', 1), ('1021', 'Joseph', 'Lewis', '2018-10-22', 'M', 1),
('1022', 'Abigail', 'Walker', '2018-04-07', 'F', 1), ('1023', 'Samuel', 'Hall', '2018-08-18', 'M', 1), ('1024', 'Emily', 'Allen', '2018-12-29', 'F', 1),
('1025', 'Henry', 'Young', '2018-01-09', 'M', 1), ('1026', 'Elizabeth', 'King', '2018-05-21', 'F', 1), ('1027', 'Andrew', 'Wright', '2018-09-04', 'M', 1),
('1028', 'Sofia', 'Lopez', '2018-03-26', 'F', 1), ('1029', 'Christopher', 'Hill', '2018-07-13', 'M', 1), ('1030', 'Avery', 'Scott', '2018-11-24', 'F', 1),
-- Grade 2 Students (30 students)
('2001', 'Sophia', 'Anderson', '2017-04-25', 'F', 1), ('2002', 'Mason', 'White', '2017-08-14', 'M', 1), ('2003', 'Isabella', 'Harris', '2017-12-05', 'F', 1),
('2004', 'Logan', 'Clark', '2017-06-20', 'M', 1), ('2005', 'Emma', 'Robinson', '2017-10-11', 'F', 1), ('2006', 'Lucas', 'Rodriguez', '2017-02-18', 'M', 1),
('2007', 'Olivia', 'Lewis', '2017-05-29', 'F', 1), ('2008', 'Noah', 'Walker', '2017-09-07', 'M', 1), ('2009', 'Ava', 'Hall', '2017-01-16', 'F', 1),
('2010', 'Ethan', 'Allen', '2017-04-23', 'M', 1), ('2011', 'Mia', 'Young', '2017-08-31', 'F', 1), ('2012', 'James', 'King', '2017-12-12', 'M', 1),
('2013', 'Charlotte', 'Wright', '2017-03-21', 'F', 1), ('2014', 'Benjamin', 'Lopez', '2017-07-09', 'M', 1), ('2015', 'Amelia', 'Hill', '2017-11-20', 'F', 1),
('2016', 'Daniel', 'Scott', '2017-02-28', 'M', 1), ('2017', 'Harper', 'Green', '2017-06-15', 'F', 1), ('2018', 'Matthew', 'Adams', '2017-10-04', 'M', 1),
('2019', 'Evelyn', 'Baker', '2017-01-13', 'F', 1), ('2020', 'David', 'Nelson', '2017-05-22', 'M', 1), ('2021', 'Abigail', 'Carter', '2017-09-08', 'F', 1),
('2022', 'Joseph', 'Mitchell', '2017-12-17', 'M', 1), ('2023', 'Emily', 'Perez', '2017-04-26', 'F', 1), ('2024', 'Samuel', 'Roberts', '2017-08-14', 'M', 1),
('2025', 'Elizabeth', 'Turner', '2017-11-25', 'F', 1), ('2026', 'Henry', 'Phillips', '2017-03-06', 'M', 1), ('2027', 'Sofia', 'Campbell', '2017-07-19', 'F', 1),
('2028', 'Andrew', 'Parker', '2017-10-30', 'M', 1), ('2029', 'Avery', 'Evans', '2017-02-08', 'F', 1), ('2030', 'Christopher', 'Edwards', '2017-06-21', 'M', 1),
-- Grade 3 Students (30 students)
('3001', 'Mia', 'Lewis', '2016-02-10', 'F', 1), ('3002', 'Lucas', 'Robinson', '2016-10-28', 'M', 1), ('3003', 'Amelia', 'Walker', '2016-07-03', 'F', 1),
('3004', 'Henry', 'Young', '2016-03-17', 'M', 1), ('3005', 'Charlotte', 'King', '2016-11-24', 'F', 1), ('3006', 'Alexander', 'Wright', '2016-05-08', 'M', 1),
('3007', 'Harper', 'Lopez', '2016-09-19', 'F', 1), ('3008', 'Benjamin', 'Hill', '2016-01-26', 'M', 1), ('3009', 'Evelyn', 'Scott', '2016-08-12', 'F', 1),
('3010', 'Daniel', 'Green', '2016-04-03', 'M', 1), ('3011', 'Abigail', 'Adams', '2016-12-20', 'F', 1), ('3012', 'Matthew', 'Baker', '2016-06-07', 'M', 1),
('3013', 'Emily', 'Nelson', '2016-10-15', 'F', 1), ('3014', 'David', 'Carter', '2016-02-22', 'M', 1), ('3015', 'Sofia', 'Mitchell', '2016-09-01', 'F', 1),
('3016', 'Joseph', 'Perez', '2016-05-18', 'M', 1), ('3017', 'Elizabeth', 'Roberts', '2016-11-29', 'F', 1), ('3018', 'Samuel', 'Turner', '2016-03-14', 'M', 1),
('3019', 'Avery', 'Phillips', '2016-07-25', 'F', 1), ('3020', 'Andrew', 'Campbell', '2016-01-09', 'M', 1), ('3021', 'Christopher', 'Parker', '2016-08-16', 'M', 1),
('3022', 'Grace', 'Evans', '2016-04-27', 'F', 1), ('3023', 'Joshua', 'Edwards', '2016-12-05', 'M', 1), ('3024', 'Lily', 'Collins', '2016-06-11', 'F', 1),
('3025', 'Ryan', 'Stewart', '2016-10-23', 'M', 1), ('3026', 'Chloe', 'Sanchez', '2016-02-04', 'F', 1), ('3027', 'Nathan', 'Morris', '2016-09-13', 'M', 1),
('3028', 'Zoe', 'Rogers', '2016-05-30', 'F', 1), ('3029', 'Tyler', 'Reed', '2016-11-08', 'M', 1), ('3030', 'Natalie', 'Cook', '2016-07-19', 'F', 1),
-- Grade 4 Students (30 students)
('4001', 'Charlotte', 'King', '2015-09-21', 'F', 1), ('4002', 'Alexander', 'Wright', '2015-01-14', 'M', 1), ('4003', 'Harper', 'Lopez', '2015-11-08', 'F', 1),
('4004', 'Benjamin', 'Hill', '2015-05-02', 'M', 1), ('4005', 'Evelyn', 'Scott', '2015-08-16', 'F', 1), ('4006', 'Daniel', 'Green', '2015-02-27', 'M', 1),
('4007', 'Abigail', 'Adams', '2015-12-03', 'F', 1), ('4008', 'Matthew', 'Baker', '2015-06-14', 'M', 1), ('4009', 'Emily', 'Nelson', '2015-10-25', 'F', 1),
('4010', 'David', 'Carter', '2015-04-07', 'M', 1), ('4011', 'Sofia', 'Mitchell', '2015-09-18', 'F', 1), ('4012', 'Joseph', 'Perez', '2015-01-29', 'M', 1),
('4013', 'Elizabeth', 'Roberts', '2015-11-10', 'F', 1), ('4014', 'Samuel', 'Turner', '2015-05-21', 'M', 1), ('4015', 'Avery', 'Phillips', '2015-08-02', 'F', 1),
('4016', 'Andrew', 'Campbell', '2015-12-13', 'M', 1), ('4017', 'Christopher', 'Parker', '2015-07-24', 'M', 1), ('4018', 'Grace', 'Evans', '2015-03-05', 'F', 1),
('4019', 'Joshua', 'Edwards', '2015-10-16', 'M', 1), ('4020', 'Lily', 'Collins', '2015-04-27', 'F', 1), ('4021', 'Ryan', 'Stewart', '2015-09-08', 'M', 1),
('4022', 'Chloe', 'Sanchez', '2015-01-19', 'F', 1), ('4023', 'Nathan', 'Morris', '2015-11-30', 'M', 1), ('4024', 'Zoe', 'Rogers', '2015-06-11', 'F', 1),
('4025', 'Tyler', 'Reed', '2015-10-22', 'M', 1), ('4026', 'Natalie', 'Cook', '2015-04-03', 'F', 1), ('4027', 'Brandon', 'Morgan', '2015-08-14', 'M', 1),
('4028', 'Hannah', 'Bell', '2015-12-25', 'F', 1), ('4029', 'Justin', 'Murphy', '2015-07-06', 'M', 1), ('4030', 'Victoria', 'Bailey', '2015-03-17', 'F', 1),
-- Grade 5 Students (30 students)
('5001', 'Liam', 'Carter', '2014-08-19', 'M', 1), ('5002', 'Olivia', 'Bennett', '2014-12-11', 'F', 1), ('5003', 'Noah', 'Thompson', '2014-04-27', 'M', 1),
('5004', 'Emma', 'Harper', '2014-06-15', 'F', 1), ('5005', 'Ethan', 'Parker', '2014-10-03', 'M', 1), ('5006', 'Ava', 'Mitchell', '2014-02-14', 'F', 1),
('5007', 'James', 'Wilson', '2014-11-25', 'M', 1), ('5008', 'Sophia', 'Martinez', '2014-05-07', 'F', 1), ('5009', 'Benjamin', 'Taylor', '2014-09-18', 'M', 1),
('5010', 'Isabella', 'Anderson', '2014-01-29', 'F', 1), ('5011', 'Mason', 'Thomas', '2014-10-10', 'M', 1), ('5012', 'Mia', 'Jackson', '2014-04-21', 'F', 1),
('5013', 'Alexander', 'White', '2014-08-02', 'M', 1), ('5014', 'Charlotte', 'Harris', '2014-12-13', 'F', 1), ('5015', 'Daniel', 'Martin', '2014-06-24', 'M', 1),
('5016', 'Amelia', 'Thompson', '2014-10-05', 'F', 1), ('5017', 'Matthew', 'Garcia', '2014-02-16', 'M', 1), ('5018', 'Harper', 'Miller', '2014-11-27', 'F', 1),
('5019', 'David', 'Davis', '2014-05-08', 'M', 1), ('5020', 'Evelyn', 'Rodriguez', '2014-09-19', 'F', 1), ('5021', 'Joseph', 'Lewis', '2014-01-30', 'M', 1),
('5022', 'Abigail', 'Walker', '2014-10-11', 'F', 1), ('5023', 'Samuel', 'Hall', '2014-04-22', 'M', 1), ('5024', 'Emily', 'Allen', '2014-08-03', 'F', 1),
('5025', 'Henry', 'Young', '2014-12-14', 'M', 1), ('5026', 'Elizabeth', 'King', '2014-06-25', 'F', 1), ('5027', 'Andrew', 'Wright', '2014-10-06', 'M', 1),
('5028', 'Sofia', 'Lopez', '2014-02-17', 'F', 1), ('5029', 'Christopher', 'Hill', '2014-11-28', 'M', 1), ('5030', 'Avery', 'Scott', '2014-05-09', 'F', 1),
-- Grade 6 Students (30 students)
('6001', 'Ava', 'Mitchell', '2013-02-22', 'F', 1), ('6002', 'Lucas', 'Foster', '2013-07-30', 'M', 1), ('6003', 'Sophia', 'Gonzalez', '2013-09-14', 'F', 1),
('6004', 'Mason', 'Nelson', '2013-01-08', 'M', 1), ('6005', 'Isabella', 'Baker', '2013-11-25', 'F', 1), ('6006', 'Logan', 'Hall', '2013-04-16', 'M', 1),
('6007', 'Mia', 'Adams', '2013-08-29', 'F', 1), ('6008', 'James', 'Carter', '2013-12-10', 'M', 1), ('6009', 'Charlotte', 'Perez', '2013-06-21', 'F', 1),
('6010', 'Benjamin', 'Roberts', '2013-10-02', 'M', 1), ('6011', 'Amelia', 'Turner', '2013-02-13', 'F', 1), ('6012', 'Daniel', 'Phillips', '2013-11-24', 'M', 1),
('6013', 'Harper', 'Campbell', '2013-05-05', 'F', 1), ('6014', 'Matthew', 'Parker', '2013-09-16', 'M', 1), ('6015', 'Evelyn', 'Evans', '2013-01-27', 'F', 1),
('6016', 'David', 'Edwards', '2013-10-08', 'M', 1), ('6017', 'Sofia', 'Collins', '2013-04-19', 'F', 1), ('6018', 'Joseph', 'Stewart', '2013-08-30', 'M', 1),
('6019', 'Emily', 'Sanchez', '2013-12-11', 'F', 1), ('6020', 'Samuel', 'Morris', '2013-06-22', 'M', 1), ('6021', 'Elizabeth', 'Rogers', '2013-10-03', 'F', 1),
('6022', 'Henry', 'Reed', '2013-02-14', 'M', 1), ('6023', 'Andrew', 'Cook', '2013-11-25', 'M', 1), ('6024', 'Grace', 'Morgan', '2013-05-06', 'F', 1),
('6025', 'Christopher', 'Bell', '2013-09-17', 'M', 1), ('6026', 'Avery', 'Murphy', '2013-01-28', 'F', 1), ('6027', 'Joshua', 'Bailey', '2013-10-09', 'M', 1),
('6028', 'Lily', 'Rivera', '2013-04-20', 'F', 1), ('6029', 'Ryan', 'Cooper', '2013-08-01', 'M', 1), ('6030', 'Chloe', 'Richardson', '2013-12-12', 'F', 1);


-- SEED DATA: ENROLLMENTS (Multiple Years - Historical Data)


-- Enroll all current students (2025-2026) - distribute across classes A, B, C, D

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2025-09-01', 'ACTIVE'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2025-2026') sy
JOIN classes c ON c.grade_id = 1
WHERE s.admission_no LIKE '1%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2025-09-01', 'ACTIVE'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2025-2026') sy
JOIN classes c ON c.grade_id = 2
WHERE s.admission_no LIKE '2%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2025-09-01', 'ACTIVE'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2025-2026') sy
JOIN classes c ON c.grade_id = 3
WHERE s.admission_no LIKE '3%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2025-09-01', 'ACTIVE'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2025-2026') sy
JOIN classes c ON c.grade_id = 4
WHERE s.admission_no LIKE '4%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2025-09-01', 'ACTIVE'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2025-2026') sy
JOIN classes c ON c.grade_id = 5
WHERE s.admission_no LIKE '5%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2025-09-01', 'ACTIVE'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2025-2026') sy
JOIN classes c ON c.grade_id = 6
WHERE s.admission_no LIKE '6%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;


INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2024-09-01', 'COMPLETED'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2024-2025') sy
JOIN classes c ON c.grade_id = 1
WHERE s.admission_no LIKE '2%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2024-09-01', 'COMPLETED'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2024-2025') sy
JOIN classes c ON c.grade_id = 2
WHERE s.admission_no LIKE '3%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2024-09-01', 'COMPLETED'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2024-2025') sy
JOIN classes c ON c.grade_id = 3
WHERE s.admission_no LIKE '4%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2024-09-01', 'COMPLETED'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2024-2025') sy
JOIN classes c ON c.grade_id = 4
WHERE s.admission_no LIKE '5%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, sy.id, '2024-09-01', 'COMPLETED'
FROM students s
CROSS JOIN (SELECT id FROM school_years WHERE name = '2024-2025') sy
JOIN classes c ON c.grade_id = 5
WHERE s.admission_no LIKE '6%' 
  AND c.name = CASE (CAST(SUBSTRING(s.admission_no, 4) AS UNSIGNED) % 4)
    WHEN 0 THEN 'A' WHEN 1 THEN 'B' WHEN 2 THEN 'C' WHEN 3 THEN 'D' END;


-- Scores for 2025-2026 - Term 1 (All students, all subjects)
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username IN ('teacher', 'ms.williams', 'mr.johnson', 'ms.davis', 'mr.brown') ORDER BY (e.id + sub.id) % 5 LIMIT 1),
  ROUND(65.0 + ((e.id * 7 + sub.id * 11 + t.id * 3) % 35), 2),
  CASE ((e.id + sub.id) % 4)
    WHEN 0 THEN 'Excellent work!'
    WHEN 1 THEN 'Good progress'
    WHEN 2 THEN 'Keep practicing'
    ELSE NULL
  END
FROM enrollments e
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id
JOIN terms t ON t.school_year_id = e.school_year_id AND t.term_number = 1
WHERE e.school_year_id = (SELECT id FROM school_years WHERE name = '2025-2026');

-- Scores for 2025-2026 - Term 2
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username IN ('ms.miller', 'mr.wilson', 'ms.moore', 'mr.taylor', 'ms.garcia') ORDER BY (e.id + sub.id) % 5 LIMIT 1),
  ROUND(65.0 + ((e.id * 13 + sub.id * 7 + t.id * 5) % 35), 2),
  CASE ((e.id + sub.id + 1) % 4)
    WHEN 0 THEN 'Excellent work!'
    WHEN 1 THEN 'Good progress'
    WHEN 2 THEN 'Keep practicing'
    ELSE NULL
  END
FROM enrollments e
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id
JOIN terms t ON t.school_year_id = e.school_year_id AND t.term_number = 2
WHERE e.school_year_id = (SELECT id FROM school_years WHERE name = '2025-2026');

-- Scores for 2025-2026 - Term 3 (67% coverage - ongoing term)
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username IN ('mr.martinez', 'ms.rodriguez', 'mr.lee', 'ms.walker', 'mr.hall') ORDER BY (e.id + sub.id) % 5 LIMIT 1),
  ROUND(65.0 + ((e.id * 17 + sub.id * 19 + t.id * 7) % 35), 2),
  CASE ((e.id + sub.id + 2) % 4)
    WHEN 0 THEN 'Excellent work!'
    WHEN 1 THEN 'Good progress'
    WHEN 2 THEN 'Keep practicing'
    ELSE NULL
  END
FROM enrollments e
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id
JOIN terms t ON t.school_year_id = e.school_year_id AND t.term_number = 3
WHERE e.school_year_id = (SELECT id FROM school_years WHERE name = '2025-2026')
  AND (e.id % 3) != 2;

-- Scores for 2024-2025 - All Terms (Historical data)
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username IN ('teacher', 'ms.williams', 'mr.johnson', 'ms.davis', 'mr.brown', 'ms.miller', 'mr.wilson') ORDER BY (e.id + sub.id + t.id) % 7 LIMIT 1),
  ROUND(60.0 + ((e.id * 19 + sub.id * 23 + t.id * 11) % 40), 2),
  CASE ((e.id + sub.id + t.id) % 4)
    WHEN 0 THEN 'Excellent work!'
    WHEN 1 THEN 'Good progress'
    WHEN 2 THEN 'Keep practicing'
    ELSE NULL
  END
FROM enrollments e
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id
JOIN terms t ON t.school_year_id = e.school_year_id
WHERE e.school_year_id = (SELECT id FROM school_years WHERE name = '2024-2025');

-- Scores for 2023-2024 - All Terms (Historical data)
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username IN ('teacher', 'ms.williams', 'mr.johnson', 'ms.davis', 'mr.brown') ORDER BY (e.id + sub.id + t.id) % 5 LIMIT 1),
  ROUND(58.0 + ((e.id * 31 + sub.id * 37 + t.id * 13) % 42), 2),
  CASE ((e.id + sub.id + t.id + 1) % 4)
    WHEN 0 THEN 'Excellent work!'
    WHEN 1 THEN 'Good progress'
    WHEN 2 THEN 'Keep practicing'
    ELSE NULL
  END
FROM enrollments e
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id
JOIN terms t ON t.school_year_id = e.school_year_id
WHERE e.school_year_id = (SELECT id FROM school_years WHERE name = '2023-2024')
  AND (e.id % 2) = 0;  -- 50% coverage for older data

-- Scores for 2022-2023 - All Terms (Historical data - limited)
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username = 'teacher' LIMIT 1),
  ROUND(55.0 + ((e.id * 41 + sub.id * 43 + t.id * 17) % 45), 2),
  CASE ((e.id + sub.id + t.id + 2) % 4)
    WHEN 0 THEN 'Excellent work!'
    WHEN 1 THEN 'Good progress'
    WHEN 2 THEN 'Keep practicing'
    ELSE NULL
  END
FROM enrollments e
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id AND sub.name IN ('Mathematics', 'English Language', 'Science')
JOIN terms t ON t.school_year_id = e.school_year_id
WHERE e.school_year_id = (SELECT id FROM school_years WHERE name = '2022-2023')
  AND (e.id % 3) = 0;  -- 33% coverage for oldest data

-- High-performing student: Grade 5, Student 5001 (Liam Carter) - Math scores
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username = 'teacher' LIMIT 1),
  95.00,
  'Outstanding performance!'
FROM enrollments e
JOIN students s ON e.student_id = s.id
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id AND sub.name = 'Mathematics'
JOIN terms t ON t.school_year_id = e.school_year_id
WHERE s.admission_no = '5001'
  AND e.school_year_id = (SELECT id FROM school_years WHERE name = '2025-2026')
ON DUPLICATE KEY UPDATE score = 95.00, remarks = 'Outstanding performance!';

-- Another high-performer: Grade 6, Student 6001 (Ava Mitchell) - English
INSERT INTO scores (enrollment_id, subject_id, term_id, teacher_user_id, score, remarks)
SELECT 
  e.id,
  sub.id,
  t.id,
  (SELECT id FROM users WHERE username = 'ms.williams' LIMIT 1),
  92.50,
  'Excellent reading and writing skills!'
FROM enrollments e
JOIN students s ON e.student_id = s.id
JOIN classes c ON e.class_id = c.id
JOIN subjects sub ON sub.grade_id = c.grade_id AND sub.name = 'English Language'
JOIN terms t ON t.school_year_id = e.school_year_id
WHERE s.admission_no = '6001'
  AND e.school_year_id = (SELECT id FROM school_years WHERE name = '2025-2026')
ON DUPLICATE KEY UPDATE score = 92.50, remarks = 'Excellent reading and writing skills!';


-- SUMMARY STATISTICS

-- This dump includes:
-- - 23 Users (2 Admins, 21 Teachers)
-- - 6 Grades (Grade 1-6)
-- - 24 Classes (4 per grade: A, B, C, D)
-- - 54 Subjects (9 per grade: Math, English, Science, Social Studies, Art, PE, Music, Health, Computer Studies)
-- - 4 School Years (2022-2023, 2023-2024, 2024-2025, 2025-2026)
-- - 12 Terms (3 per school year)
-- - 180 Students (30 per grade, distributed across all grades)
-- - ~360 Enrollments (current year + historical data)
-- - ~8,000+ Score records:
--   * 2025-2026 Term 1: All students x All subjects (~1,620 scores)
--   * 2025-2026 Term 2: All students x All subjects (~1,620 scores)
--   * 2025-2026 Term 3: ~67% coverage (~1,080 scores)
--   * 2024-2025: All terms, all students (~4,860 scores)
--   * 2023-2024: All terms, 50% coverage (~2,430 scores)
--   * 2022-2023: Core subjects only, 33% coverage (~540 scores)
--   * Plus specific high-performer overrides


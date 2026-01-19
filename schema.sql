-- =========================================================
-- School Management System (SMS) Database Schema
-- Compatible with XAMPP / phpMyAdmin
-- =========================================================

SET SQL_MODE = "STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION";
SET time_zone = "+00:00";

-- ---------------------------------------------------------
-- Drop tables (safe order)
-- ---------------------------------------------------------
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

-- ---------------------------------------------------------
-- USERS / ROLES
-- ---------------------------------------------------------

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
  name VARCHAR(40) NOT NULL, -- e.g. 'TEACHER', 'OFFICE_ADMIN'
  description VARCHAR(255) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_roles_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If you want only ONE role per user, enforce unique(user_id).
-- If you want future flexibility, allow multiple roles per user.
CREATE TABLE user_roles (
  user_id BIGINT UNSIGNED NOT NULL,
  role_id TINYINT UNSIGNED NOT NULL,
  assigned_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (user_id, role_id),
  CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OPTIONAL (recommended for clean permission checks in MVC)
CREATE TABLE permissions (
  id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(60) NOT NULL, -- e.g. 'MANAGE_SCORES', 'MANAGE_USERS'
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

-- ---------------------------------------------------------
-- SCHOOL STRUCTURE
-- ---------------------------------------------------------

-- Grades must be predefined 1..6 and cannot be renamed.
-- We store both an immutable "code" and a display label.
CREATE TABLE grades (
  id TINYINT UNSIGNED NOT NULL,
  grade_number TINYINT UNSIGNED NOT NULL, -- 1..6
  name VARCHAR(20) NOT NULL,              -- 'Grade 1'...'Grade 6'
  PRIMARY KEY (id),
  UNIQUE KEY uq_grades_grade_number (grade_number),
  UNIQUE KEY uq_grades_name (name),
  CONSTRAINT chk_grade_number CHECK (grade_number BETWEEN 1 AND 6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Each grade has 1..6 classes
-- Classes are uniquely named and cannot switch grades.
CREATE TABLE classes (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  grade_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(40) NOT NULL,   -- e.g. '1A', '1B', 'Blue', etc (must be unique globally per requirement)
  room VARCHAR(40) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_classes_grade_name (grade_id, name),
  KEY idx_classes_grade (grade_id),
  CONSTRAINT fk_classes_grade FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subjects are shared across all classes in the same grade.
-- So subject belongs to a grade (NOT a class).
CREATE TABLE subjects (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  grade_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(80) NOT NULL,       -- e.g. 'Mathematics', 'English'
  code VARCHAR(20) NULL,           -- optional like 'MATH'
  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_subject_grade_name (grade_id, name),
  KEY idx_subjects_grade (grade_id),
  CONSTRAINT fk_subjects_grade FOREIGN KEY (grade_id) REFERENCES grades(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- ACADEMIC YEAR / TERMS
-- ---------------------------------------------------------

CREATE TABLE school_years (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,         -- e.g. '2025/2026'
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  is_current TINYINT(1) NOT NULL DEFAULT 0,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),
  UNIQUE KEY uq_school_years_name (name),
  CONSTRAINT chk_school_year_dates CHECK (start_date < end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Terms: 3 per year. Use term_number 1..3.
CREATE TABLE terms (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  school_year_id BIGINT UNSIGNED NOT NULL,
  term_number TINYINT UNSIGNED NOT NULL,  -- 1..3
  name VARCHAR(30) NOT NULL,              -- 'Term 1', 'Term 2', 'Term 3'
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

-- ---------------------------------------------------------
-- STUDENTS + ENROLLMENT
-- ---------------------------------------------------------

CREATE TABLE students (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  admission_no VARCHAR(30) NOT NULL,  -- unique student ID
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

-- Enrollment enforces:
-- - student belongs to only ONE class per school year
-- - also supports moving to a different class in a new year
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

-- ---------------------------------------------------------
-- SCORES
-- ---------------------------------------------------------

-- Scores recorded for each term (3 terms/year), tied to subjects, per student (via enrollment),
-- and optionally store which teacher recorded it (user_id of TEACHER).
CREATE TABLE scores (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  enrollment_id BIGINT UNSIGNED NOT NULL,
  subject_id BIGINT UNSIGNED NOT NULL,
  term_id BIGINT UNSIGNED NOT NULL,
  teacher_user_id BIGINT UNSIGNED NOT NULL,

  score DECIMAL(5,2) NOT NULL, -- e.g. 0.00 to 100.00
  remarks VARCHAR(255) NULL,

  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  -- one score per student enrollment per subject per term:
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

-- =========================================================
-- SEED DATA: Grades + Roles + Permissions (recommended)
-- =========================================================

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

-- Role -> permissions mapping (teacher gets only scores + view reports)
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE (r.name='TEACHER' AND p.code IN ('MANAGE_SCORES','VIEW_REPORTS'))
   OR (r.name='OFFICE_ADMIN' AND p.code IN ('MANAGE_SCORES','VIEW_REPORTS','MANAGE_USERS','MANAGE_STUDENTS','MANAGE_STRUCTURE','MANAGE_CALENDAR'));

-- Grades fixed 1..6
INSERT INTO grades (id, grade_number, name) VALUES
(1,1,'Grade 1'),
(2,2,'Grade 2'),
(3,3,'Grade 3'),
(4,4,'Grade 4'),
(5,5,'Grade 5'),
(6,6,'Grade 6');

-- =========================================================
-- DEFAULT USERS FOR TESTING in production we ar e not supposed to comment password in db schema. next time we need an env or something
-- admin@school.com / password
-- teacher@school.com / password
-- =========================================================

-- Admin User
INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES
('admin', 'admin@school.com', '$2a$12$uC7c/3/t/ZgCpQJlvY0tNuDWWuUIaEuD.37esw/Lo2Cne1xksgNEG', 'System', 'Admin'); -- Its password

-- Assign OFFICE_ADMIN Role
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id FROM users u, roles r 
WHERE u.username = 'admin' AND r.name = 'OFFICE_ADMIN';

-- create the testing account for team entity teacher User
INSERT INTO users (username, email, password_hash, first_name, last_name) VALUES
('teacher', 'teacher@school.com', '$2a$12$uC7c/3/t/ZgCpQJlvY0tNuDWWuUIaEuD.37esw/Lo2Cne1xksgNEG', 'John', 'Doe'); -- same here its password

-- Assign TEACHER Role to testing account
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id FROM users u, roles r 
WHERE u.username = 'teacher' AND r.name = 'TEACHER';

-- =========================================================
-- ADDITIONAL SEED DATA FOR DEMO (Matches Figma Design as intructed. Someone needs to add the rest of the seed data)
-- =========================================================

-- School Year 2025-2026
INSERT INTO school_years (name, start_date, end_date, is_current) 
VALUES ('2025-2026', '2025-09-01', '2026-06-30', 1);

-- Terms
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 1, 'Term 1', '2025-09-01', '2025-12-20' FROM school_years WHERE name = '2025-2026';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 2, 'Term 2', '2026-01-05', '2026-03-28' FROM school_years WHERE name = '2025-2026';
INSERT INTO terms (school_year_id, term_number, name, start_date, end_date)
SELECT id, 3, 'Term 3', '2026-04-13', '2026-06-30' FROM school_years WHERE name = '2025-2026';

-- Classes A & B for Grades 5, 6, 7, 8
INSERT INTO classes (name, grade_id, room, is_active) VALUES
('A', 5, 'Room 5A', 1), ('B', 5, 'Room 5B', 1),
('A', 6, 'Room 6A', 1), ('B', 6, 'Room 6B', 1);

-- Sample Students from Image
INSERT INTO students (admission_no, first_name, last_name, is_active) VALUES
('1001', 'Liam', 'Carter', 1),
('1002', 'Olivia', 'Bennett', 1),
('1003', 'Noah', 'Thompson', 1),
('1004', 'Emma', 'Harper', 1),
('1005', 'Ethan', 'Parker', 1),
('1006', 'Ava', 'Mitchell', 1),
('1007', 'Lucas', 'Foster', 1);

-- Enrollments matching image
INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, (SELECT id FROM school_years WHERE name='2025-2026'), '2025-09-01', 'ACTIVE'
FROM students s, classes c, grades g
WHERE s.admission_no = '1001' AND c.name = 'A' AND c.grade_id = g.id AND g.grade_number = 5;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, (SELECT id FROM school_years WHERE name='2025-2026'), '2025-09-01', 'ACTIVE'
FROM students s, classes c, grades g
WHERE s.admission_no = '1002' AND c.name = 'B' AND c.grade_id = g.id AND g.grade_number = 5;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, (SELECT id FROM school_years WHERE name='2025-2026'), '2025-09-01', 'ACTIVE'
FROM students s, classes c, grades g
WHERE s.admission_no = '1003' AND c.name = 'A' AND c.grade_id = g.id AND g.grade_number = 6;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, (SELECT id FROM school_years WHERE name='2025-2026'), '2025-09-01', 'ACTIVE'
FROM students s, classes c, grades g
WHERE s.admission_no = '1004' AND c.name = 'B' AND c.grade_id = g.id AND g.grade_number = 6;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, (SELECT id FROM school_years WHERE name='2025-2026'), '2025-09-01', 'ACTIVE'
FROM students s, classes c, grades g
WHERE s.admission_no = '1005' AND c.name = 'A' AND c.grade_id = g.id AND g.grade_number = 5;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, (SELECT id FROM school_years WHERE name='2025-2026'), '2025-09-01', 'ACTIVE'
FROM students s, classes c, grades g
WHERE s.admission_no = '1006' AND c.name = 'B' AND c.grade_id = g.id AND g.grade_number = 6;

INSERT INTO enrollments (student_id, class_id, school_year_id, enrolled_at, status)
SELECT s.id, c.id, (SELECT id FROM school_years WHERE name='2025-2026'), '2025-09-01', 'ACTIVE'
FROM students s, classes c, grades g
WHERE s.admission_no = '1007' AND c.name = 'A' AND c.grade_id = g.id AND g.grade_number = 6;

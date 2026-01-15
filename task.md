# Project Tasks - School Management System

- [x] **Project Setup & Organization** <!-- id: 8 -->
    - [x] Move design assets to appropriate folder
    - [x] Verify `schema.sql` completeness
    - [x] Create `install.php` for automatic Database creation and schema import
    - [x] Update `README.md` with setup instructions
    - [x] **Authentication Core** (Model & Database Configured)

- [x] **Authentication & Authorization** <!-- id: 1 -->
    - [x] **AuthMiddleware**: Secure routes implemented in `index.php`.
    - [x] **AuthController**: Login logic with Role fetching.
    - [x] **Views**: Styled `views/auth/login.php`.

- [ ] **User Management (Office Admin)** <!-- id: 2 -->
    - [ ] **Model**: Add `User::getAll()`, `User::update()`, `User::delete()`.
    - [ ] **Controller**: Implement `UserController`.
    - [ ] **Views**: Create `views/users/list.php`, `views/users/create.php`.

- [ ] **Academic Structure Management** <!-- id: 3 -->
    - [ ] **Models**: Implement CRUD in `SchoolYear`, `Term`, `ClassModel`, `Subject`.
    - [ ] **Controllers**: `SchoolYearController`, `TermController`, `ClassController`, `SubjectController`.
    - [ ] **Views**: Management forms for all the above.

- [ ] **Student Management** <!-- id: 4 -->
    - [ ] **Model**: Implement `Student::create()`, `Student::enroll($class_id)`.
    - [ ] **Controller**: `StudentController` (handle bio-data & enrollment).
    - [ ] **Views**: `views/students/add.php`, `views/students/list.php`.

- [ ] **Score Management (Teachers)** <!-- id: 5 -->
    - [ ] **Model**: `Score::add($student_id, $subject_id, $term_id, $score)`.
    - [ ] **Controller**: `ScoreController` (Validation: 0-100).
    - [ ] **View**: `views/scores/enter.php` (Grid view recommended).

- [ ] **Reports & Analytics** <!-- id: 6 -->
    - [ ] **Model**: `Report::getStudentReport($student_id, $term_id)`.
    - [ ] **Controller**: `ReportController`.
    - [ ] **View**: `views/report/report_card.php`.

- [ ] **Final Verification** <!-- id: 7 -->
    - [ ] Security Audit (SQL Injection checks, XSS).
    - [ ] UI Polish (CSS).
    - [ ] Prepare `sms.zip` content.

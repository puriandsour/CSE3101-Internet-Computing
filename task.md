# Project Tasks - School Management System

- [x] **Project Setup & Organization**
    - [x] Move design assets to appropriate folder
    - [x] Verify `schema.sql` completeness
    - [x] Create `install.php` for automatic Database creation and schema import
    - [x] Update `README.md` with setup instructions
    - [x] **Authentication Core** (Model & Database Configured)

- [x] **Authentication & Authorization**
    - [x] **AuthMiddleware**: Secure routes implemented in `index.php`.
    - [x] **AuthController**: Login logic with Role fetching.
    - [x] **Views**: Styled `views/auth/login.php`.

- [ ] **User Management (Office Admin)** <!-- id: 2 -->
    - [ ] **Model**: Add `update($id, $data)` and `delete($id)` messages to `User.php`.
    - [ ] **Controller**: Implement `UserController` (index, create, store, edit, update, delete).
    - [ ] **Views**: Create `views/users/index.php` (List) and `views/users/edit.php` (Form).

- [ ] **Academic Structure Management** <!-- id: 3 -->
    - [ ] **Models**: `SchoolYear`, `Term`, `ClassModel`, `Subject` are present. Verify `update`/`delete` methods.
    - [ ] **Controllers**: Implement CRUD actions in `SchoolYearController`, `TermController`, `ClassController`, `SubjectController`.
    - [ ] **Views**: Create management forms (Add/Edit/List) for each resource.

- [ ] **Student Management** <!-- id: 4 -->
    - [x] **Model**: `Student.php` is complete (Create, Update, Delete, Enrollment logic).
    - [ ] **Controller**: `StudentController` `add` is fixed. Validated `index`, `edit`, `update`, `delete` actions still needed.
    - [ ] **Views**: Create `views/students/index.php` (List with Filters) and `views/students/edit.php`.

- [ ] **Score Management (Teachers)** <!-- id: 5 -->
    - [x] **Model**: `Score.php` is complete (Add, Update, Batch Add).
    - [ ] **Controller**: `ScoreController` `add` is fixed. Needs `index` (Class Sheet), `edit` actions.
    - [ ] **View**: Create `views/scores/index.php` (Grid View for teacher to enter scores for whole class).

- [ ] **Reports & Analytics** <!-- id: 6 -->
    - [x] **Model**: `Report.php` is complete (`getStudentReportCard`, `getGradeAverages`).
    - [ ] **Controller**: `ReportController` needs to call these methods and render views.
    - [ ] **View**: Create `views/report/student_card.php` and `views/report/average_performance.php`.

- [ ] **Final Verification** <!-- id: 7 -->
    - [ ] Security Audit (SQL Injection checks, XSS).
    - [ ] UI Polish (CSS).
    - [ ] Prepare `sms.zip` content.

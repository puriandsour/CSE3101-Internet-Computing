<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/ClassModel.php';

class StudentController
{

    public function index()
    {
        require_once __DIR__ . '/../models/Grade.php';
        require_once __DIR__ . '/../models/ClassModel.php';

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $filters = [
            'search' => $_GET['search'] ?? '',
            'grade_id' => $_GET['grade_id'] ?? '',
            'class_id' => $_GET['class_id'] ?? ''
        ];

        $studentsData = Student::list($filters, $page, 10);
        $grades = Grade::getAll();

        $classes = [];
        if (!empty($filters['grade_id'])) {
            $classes = ClassModel::getByGrade($filters['grade_id']);
        } else {
            $classes = ClassModel::getAll();
        }

        return [
            'students' => $studentsData['data'],
            'pagination' => [
                'total' => $studentsData['total'],
                'page' => $studentsData['page'],
                'total_pages' => $studentsData['total_pages']
            ],
            'filters' => $filters,
            'grades' => $grades,
            'classes' => $classes
        ];
    }

    /**
     * Display the Create Student form
     */
    public function add()
    {
        return render_view('views/admin/students/add.php');
    }

    /**
     * Handle the POST request to create a student
     */
    public function create($data)
    {
        $studentModel = new Student();
        $studentData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'admission_no' => $data['admission_no'] ?? null
        ];

        try {
            $studentId = $studentModel->create($studentData);
            if ($studentId) {
                $_SESSION['success'] = "Student created successfully. Please complete the enrollment.";
                header("Location: index.php?controller=student&action=enroll&id=" . $studentId);
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        // If failed, redirect back to add
        header("Location: index.php?controller=student&action=add");
        exit;
    }

    /**
     * Display or handle student enrollment
     */
    public function enroll()
    {
        $studentId = $_GET['id'] ?? null;
        if (!$studentId) {
            $_SESSION['error'] = "Student ID required for enrollment.";
            header("Location: index.php?controller=student&action=index");
            exit;
        }

        $student = Student::find($studentId);
        if (!$student) {
            $_SESSION['error'] = "Student not found.";
            header("Location: index.php?controller=student&action=index");
            exit;
        }

        // Get Grades and Classes for the form
        require_once __DIR__ . '/../models/Grade.php';
        require_once __DIR__ . '/../models/ClassModel.php';

        $db = Database::connect();
        $years = $db->query("SELECT * FROM school_years ORDER BY is_current DESC, name DESC")->fetchAll(PDO::FETCH_OBJ);
        $classes = ClassModel::getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../models/Enrollment.php';
            $enrollment = new Enrollment();
            $success = $enrollment->create($studentId, $_POST['class_id'], $_POST['school_year_id']);

            if ($success) {
                $_SESSION['success'] = "Student enrolled successfully.";
                header("Location: index.php?controller=student&action=index");
                exit;
            } else {
                $_SESSION['error'] = "Student already enrolled in this class for this school year. Come back next year. If this was a mistake, oops youre doomed you require out of scope feature";
            }
        }

        // Random child avatars for the neat look
        $avatars = [
            "https://fraserportraits.com/wp-content/uploads/2019/09/9-1.jpg",
            "https://static.vecteezy.com/system/resources/thumbnails/071/816/403/small/back-to-school-little-boy-sitting-at-table-with-a-stacks-of-books-looking-at-camera-library-or-classroom-blurred-background-portrait-of-joyful-child-during-education-funny-kid-like-to-read-book-photo.jpg",
            "https://media.istockphoto.com/id/2160439229/photo/cute-african-american-girl-wearing-eyeglasses-at-elemetary-school.jpg?s=612x612&w=0&k=20&c=c0VCUkg-4GFWKXmCp1cBOQAewPutaAltuCfSD44vrzQ=",
            "https://jpphotographic.co.uk/school-photography/wp-content/uploads/2014/01/SJP_0424a-Medium.jpg",
            "https://www.shutterstock.com/image-photo/portrait-serious-preteen-girl-standing-600nw-2120761079.jpg"
        ];
        $randomAvatar = $avatars[array_rand($avatars)];

        render_view('views/admin/students/enroll.php', [
            'student' => $student,
            'years' => $years,
            'classes' => $classes,
            'randomAvatar' => $randomAvatar
        ]);
    }
}

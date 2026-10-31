<?php
// Project 1 Index File
// Referenced GitHub -ase230/module1/code/6_RestAPI/api/index.php & ase230/module1/code/6_RestAPI/api/handlers_pdo.php & ase230/module1/code/6_RestAPI/api/handlers.php & module1/code/7_Security/Basics/bearer/index.php
 
/**
* Available endpoints:
* GET    /students      - Get all students
* GET    /courses       - Get all courses
* POST   /students     - Create a new student
* POST   /courses      - Create a new course
* GET    /students/{id} - Get student by ID
* GET    /courses/{id}  - Get course by ID
* PUT    /students     - Update a student
* PUT    /courses      - Update a course
* DELETE /students/{id}- Delete student by ID
* DELETE /courses/{id}  - Delete course by ID
*/
 
 
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
 
// Set JSON response header
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
 
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');
 
$segments = explode('/', $path);
$resource = $segments[0] ?? ''; // students or courses
$id = $segments[1] ?? null; // 123

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];


if (empty($resource)) {
    // Root endpoint - show API info
    echo json_encode([
        'success' => true,
        'message' => 'Students and Courses API',
        'endpoints' => [
            'GET /students' => 'Get all students',
            'GET /students/{id}' => 'Get student by ID',
            'POST /students' => 'Create new student',
            'PUT /students' => 'Update student (ID in JSON body)',
            'DELETE /students/{id}' => 'Delete student (Bearer token required)',
            'GET /courses' => 'Get all courses',
            'GET /courses/{id}' => 'Get course by ID',
            'POST /courses' => 'Create new course',
            'PUT /courses' => 'Update course (ID in JSON body)',
            'DELETE /courses/{id}' => 'Delete course (Bearer token required)'
        ]
    ]);
    exit;
}


if ($resource !== 'students' && $resource !== 'courses') {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Resource not found']);
    exit;
}
 
if (count($segments) > 2) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Resource not found']);
    exit;
}
 
$resource_id = null;
if (isset($segments[1]) && $segments[1] !== '') {
    if (!ctype_digit($segments[1])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid ID']);
        exit;
    }
    $resource_id = (int)$segments[1];
}
 
// Each path allows a different set of methods, so we check that first and
// reply with 405 + Allow for anything else, instead of hard-coding one list.
// With an ID in the URL: GET and DELETE. Without one: GET, POST, and PUT (ID in the JSON body).
$allowed = ($resource_id !== null) ? ['GET', 'DELETE'] : ['GET', 'POST', 'PUT'];
 
if (!in_array($method, $allowed)) {
    header('Allow: ' . implode(', ', $allowed));
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}
 
try {
    // Get all students
    if ($method === 'GET' && $resource === 'students' && $resource_id === null) {
        get_all_students();
 
    //Get all courses
    } elseif ($method === 'GET' && $resource === 'courses' && $resource_id === null) {
        get_all_courses();
 
    //Create a new student
    } elseif ($method === 'POST' && $resource === 'students') {
        create_student();
 
    //Create a new course
    } elseif ($method === 'POST' && $resource === 'courses') {
        create_course();
 
    //Get a student by ID
    } elseif ($method === 'GET' && $resource === 'students') {
        get_student($resource_id);
 
    //Get course by ID
    } elseif ($method === 'GET' && $resource === 'courses') {
        get_course($resource_id);
 
    //Update student
    } elseif ($method === 'PUT' && $resource === 'students') {
        update_student(get_pdo_body_id());
 
    //Update course
    } elseif ($method === 'PUT' && $resource === 'courses') {
        update_course(get_pdo_body_id());
 
    //Delete student by ID (Bearer token)
    } elseif ($method === 'DELETE' && $resource === 'students') {
        try {
            check_token();
        } catch (Exception $e) {
            sendJsonError(401, $e->getMessage());
        }
        delete_student($resource_id);
 
    //Delete course by ID (Bearer token)
    } elseif ($method === 'DELETE' && $resource === 'courses') {
        try {
            check_token();
        } catch (Exception $e) {
            sendJsonError(401, $e->getMessage());
        }
        delete_course($resource_id);
    }
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {   // students.email is UNIQUE
        http_response_code(409);
        echo json_encode(['success' => false, 'error' => 'A student with that email already exists']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
}




# Functions for the test (referenced from module1/code/6_RestAPI/api/handlers_pdo.php )

function get_all_students(): void
{
    $students = db()->query(
        'SELECT id, name, email, major, year, created_at, updated_at FROM students ORDER BY id'
    )->fetchAll();
    echo json_encode(['success' => true, 'data' => $students, 'count' => count($students)]);
}
 
function get_all_courses(): void
{
    $courses = db()->query(
        'SELECT id, title, department, credits FROM courses ORDER BY id'
    )->fetchAll();
    echo json_encode(['success' => true, 'data' => $courses, 'count' => count($courses)]);
}
 
function create_student(): void
{
    $input = get_pdo_request_data();
 
    // Same POST contract as handlers.php: only name/email are required,
    // major/year default like the JSON version does.
    if (!array_key_exists('name', $input) || !array_key_exists('email', $input)) {
        send_pdo_error('name and email are required', 400);
        return;
    }
    $input['major'] = $input['major'] ?? '';
    $input['year'] = $input['year'] ?? 1;
 
    $error = validate_pdo_student($input, false);
    if ($error !== null) {
        send_pdo_error($error, 400);
        return;
    }
 
    $statement = db()->prepare(
        'INSERT INTO students (name, email, major, year) VALUES (:name, :email, :major, :year)'
    );
    $statement->execute([
        ':name' => trim($input['name']),
        ':email' => trim($input['email']),
        ':major' => trim($input['major']),
        ':year' => (int)$input['year'],
    ]);
 
    $id = (int)db()->lastInsertId();
    $statement = db()->prepare(
        'SELECT id, name, email, major, year, created_at, updated_at FROM students WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $student = $statement->fetch();
 
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Student created successfully',
        'data' => $student,
    ]);
}
 
 
function create_course(): void
{
    $input = get_pdo_request_data();
 
    // Same POST contract as handlers.php: only title/department/credits are required,
    if (!array_key_exists('title', $input) || !array_key_exists('credits', $input) || !array_key_exists('department', $input)) {
        send_pdo_error('title, credits, and department are required', 400);
        return;
    }
    $input['title'] = $input['title'] ?? '';
    $input['credits'] = $input['credits'] ?? 1;
    $input['department'] = $input['department'] ?? '';
 
    $error = validate_pdo_course($input, false);
    if ($error !== null) {
        send_pdo_error($error, 400);
        return;
    }
 
    $statement = db()->prepare(
        'INSERT INTO courses (title, department, credits) VALUES (:title, :department, :credits)'
    );
    $statement->execute([
        ':title' => trim($input['title']),
        ':department' => trim($input['department']),
        ':credits' => (int)$input['credits'],
    ]);
 
    $id = (int)db()->lastInsertId();
    $statement = db()->prepare(
        'SELECT id, title, department, credits FROM courses WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $course = $statement->fetch();
 
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Course created successfully',
        'data' => $course,
    ]);
}
 
function get_student(int $id): void
{
    $statement = db()->prepare(
        'SELECT id, name, email, major, year, created_at, updated_at FROM students WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $student = $statement->fetch();
 
    if (!$student) {
        send_pdo_error('Student not found', 404);
        return;
    }
    echo json_encode(['success' => true, 'data' => $student]);
}
 
function get_course(int $id): void
{
    $statement = db()->prepare(
        'SELECT id, title, department, credits FROM courses WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $course = $statement->fetch();
 
    if (!$course) {
        send_pdo_error('Course not found', 404);
        return;
    }
    echo json_encode(['success' => true, 'data' => $course]);
}
 


function update_student(int $id): void
{
    $input = get_pdo_request_data();
    $error = validate_pdo_student($input, true);
    if ($error !== null) {
        send_pdo_error($error, 400);
        return;
    }
 
    // PUT replaces the whole resource and validate_pdo_student(..., true)
    // already guarantees all four fields are present, so a fixed SET clause
    // is enough (no need to build it dynamically field-by-field).
    $statement = db()->prepare(
        'UPDATE students SET name = :name, email = :email, major = :major, year = :year, updated_at = CURRENT_TIMESTAMP WHERE id = :id'
    );
    $statement->execute([
        ':id' => $id,
        ':name' => trim($input['name']),
        ':email' => trim($input['email']),
        ':major' => trim($input['major']),
        ':year' => (int)$input['year'],
    ]);
 
    $statement = db()->prepare(
        'SELECT id, name, email, major, year, created_at, updated_at FROM students WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $student = $statement->fetch();
    if (!$student) {
        send_pdo_error('Student not found', 404);
        return;
    }
 
    echo json_encode([
        'success' => true,
        'message' => 'Student updated successfully',
        'data' => $student,
    ]);
}
 
function update_course(int $id): void
{
    $input = get_pdo_request_data();
    $error = validate_pdo_course($input, true);
    if ($error !== null) {
        send_pdo_error($error, 400);
        return;
    }
 
    // PUT replaces the whole resource and validate_pdo_course(..., true)
    // already guarantees all three fields are present, so a fixed SET clause
    // is enough (no need to build it dynamically field-by-field).
    $statement = db()->prepare(
        'UPDATE courses SET title = :title, department = :department, credits = :credits WHERE id = :id'
    );
    $statement->execute([
        ':id' => $id,
        ':title' => trim($input['title']),
        ':department' => trim($input['department']),
        ':credits' => (int)$input['credits'],
    ]);
 
    $statement = db()->prepare(
        'SELECT id, title, department, credits FROM courses WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $course = $statement->fetch();
    if (!$course) {
        send_pdo_error('Course not found', 404);
        return;
    }
 
    echo json_encode([
        'success' => true,
        'message' => 'Course updated successfully',
        'data' => $course,
    ]);
}
 
function delete_student(int $id): void
{
    $statement = db()->prepare(
        'SELECT id, name, email, major, year, created_at, updated_at FROM students WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $student = $statement->fetch();
    if (!$student) {
        send_pdo_error('Student not found', 404);
        return;
    }
 
    db()->prepare('DELETE FROM students WHERE id = :id')->execute([':id' => $id]);
    echo json_encode([
        'success' => true,
        'message' => 'Student deleted successfully',
        'data' => $student,
    ]);
}
 
 
function delete_course(int $id): void
{
    $statement = db()->prepare(
        'SELECT id, title, department, credits FROM courses WHERE id = :id'
    );
    $statement->execute([':id' => $id]);
    $course = $statement->fetch();
    if (!$course) {
        send_pdo_error('Course not found', 404);
        return;
    }
 
    db()->prepare('DELETE FROM courses WHERE id = :id')->execute([':id' => $id]);
    echo json_encode([
        'success' => true,
        'message' => 'Course deleted successfully',
        'data' => $course,
    ]);
}
 
 
function get_pdo_request_data(): array
{
    $decoded = json_decode(file_get_contents('php://input'), true);
    return is_array($decoded) ? $decoded : [];
}
 
function validate_pdo_student(array $input, bool $allRequired): ?string
{
    foreach (['name', 'email', 'major', 'year'] as $field) {
        if ($allRequired && !array_key_exists($field, $input)) {
            return "{$field} is required";
        }
    }
    if (array_key_exists('name', $input) && (!is_string($input['name']) || trim($input['name']) === '')) {
        return 'name must be a non-empty string';
    }
    if (array_key_exists('email', $input) && (!is_string($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL))) {
        return 'email is not a valid email address';
    }
    if (array_key_exists('major', $input) && !is_string($input['major'])) {
        return 'major must be a string';
    }
    if (array_key_exists('year', $input) && (!is_int($input['year']) || $input['year'] < 1 || $input['year'] > 6)) {
        return 'year must be an integer from 1 to 6';
    }
    return null;
}
 
function validate_pdo_course(array $input, bool $allRequired): ?string
{
    foreach (['title', 'department', 'credits'] as $field) {
        if ($allRequired && !array_key_exists($field, $input)) {
            return "{$field} is required";
        }
    }
    if (array_key_exists('title', $input) && (!is_string($input['title']) || trim($input['title']) === '')) {
        return 'title must be a non-empty string';
    }
    if (array_key_exists('department', $input) && (!is_string($input['department']) || trim($input['department']) === '')) {
        return 'department must be a non-empty string';
    }
    if (array_key_exists('credits', $input) && (!is_int($input['credits']) || $input['credits'] < 1 || $input['credits'] > 255)) {
        return 'credits must be an integer from 1 to 255';
    }
    return null;
}
 
function send_pdo_error(string $message, int $status): void
{
    http_response_code($status);
    echo json_encode(['success' => false, 'error' => $message]);
}


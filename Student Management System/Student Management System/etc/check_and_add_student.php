<?php
require('../database.php'); // Ensure this initializes $conn

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$firstName = $data['first_name'] ?? '';
$lastName = $data['last_name'] ?? '';
$course = $data['course'] ?? '';

if (!$firstName || !$lastName || !$course) {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
    exit;
}

// Check if the student exists
$query = "SELECT * FROM students WHERE first_name = ? AND last_name = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ss', $firstName, $lastName);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    echo json_encode(['success' => false, 'error' => 'Student not found.']);
    exit;
}

// Check if the student already has a course
if (!empty($student['course'])) {
    echo json_encode(['success' => false, 'error' => 'Student is already enrolled in another course.']);
    exit;
}

// Update the student's course
$updateQuery = "UPDATE students SET course = ? WHERE student_id = ?";
$updateStmt = mysqli_prepare($conn, $updateQuery);
mysqli_stmt_bind_param($updateStmt, 'ss', $course, $student['student_id']);
if (mysqli_stmt_execute($updateStmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to add student to the course.']);
}



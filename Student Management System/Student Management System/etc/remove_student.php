<?php
require('../database.php'); // Include the database connection
include('functions.php'); // Include the function for logging activities
session_start(); // Ensure session is started

// Check if the request is POST and contains the necessary data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['action'])) {
    $student_id = intval($_POST['student_id']); // Sanitize input
    $action = $_POST['action'];

    if ($action === 'remove') {
        // Fetch the student's details for logging
        $fetch_query = "SELECT first_name, last_name, course FROM students WHERE student_id = ?";
        $fetch_stmt = mysqli_prepare($conn, $fetch_query);
        mysqli_stmt_bind_param($fetch_stmt, "i", $student_id);
        mysqli_stmt_execute($fetch_stmt);
        $result = mysqli_stmt_get_result($fetch_stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $student = mysqli_fetch_assoc($result);
            $studentName = $student['first_name'] . ' ' . $student['last_name'];
            $courseName = $student['course'] ?? 'Unknown Course';
        } else {
            $studentName = "Unknown Student";
            $courseName = "Unknown Course";
        }

        // Clear the course column
        $update_query = "UPDATE students SET course = NULL WHERE student_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "i", $student_id);

        if (mysqli_stmt_execute($stmt)) {
            // Log the activity
            $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
            $activityDescription = "Removed student $studentName from course: $courseName";
            logActivity($conn, $userName, $activityDescription);

            header("Location: students.php?message=Student removed from course successfully");
            exit;
        } else {
            echo "Error removing student from course: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Invalid request.";
}
?>

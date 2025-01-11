<?php
require('../database.php'); // Ensure $conn initializes mysqli connection
include('functions.php'); // Include the function for logging activities
session_start(); // Ensure session is started

// Check if the request is a POST and contains course_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']); // Sanitize the course_id input

    // Fetch the course details before deletion for logging
    $fetch_query = "SELECT course FROM courses WHERE course_id = ?";
    $fetch_stmt = mysqli_prepare($conn, $fetch_query);
    mysqli_stmt_bind_param($fetch_stmt, "i", $course_id);
    mysqli_stmt_execute($fetch_stmt);
    $result = mysqli_stmt_get_result($fetch_stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
        $courseName = $course['course'];
    } else {
        $courseName = "Unknown Course";
    }

    // SQL query to delete the course
    $delete_query = "DELETE FROM courses WHERE course_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Log the activity
        $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
        $activityDescription = "Deleted course: " . $courseName;
        logActivity($conn, $userName, $activityDescription);

        header("Location: students.php?message=Course deleted successfully");
        exit;
    } else {
        echo "Error deleting course: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>

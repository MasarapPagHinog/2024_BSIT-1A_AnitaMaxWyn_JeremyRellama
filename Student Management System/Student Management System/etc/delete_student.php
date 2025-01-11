<?php
require('../database.php');
include('functions.php'); // Include the function for logging activities
session_start(); // Ensure session is started

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // Fetch the student's details before deletion for activity logging
    $fetch_query = "SELECT first_name, last_name FROM students WHERE student_id = ?";
    $fetch_stmt = mysqli_prepare($conn, $fetch_query);
    mysqli_stmt_bind_param($fetch_stmt, "i", $student_id);
    mysqli_stmt_execute($fetch_stmt);
    $result = mysqli_stmt_get_result($fetch_stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
        $studentName = $student['first_name'] . ' ' . $student['last_name'];
    } else {
        $studentName = "Unknown Student";
    }

    // Delete the student
    $delete_query = "DELETE FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);

    if (mysqli_stmt_execute($stmt)) {
        // Log the activity
        $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
        $activityDescription = "Drop student: " . $studentName;
        logActivity($conn, $userName, $activityDescription);

        header("Location: students.php?message=Student deleted successfully");
        exit;
    } else {
        echo "Error deleting student: " . mysqli_error($conn);
    }
}
?>

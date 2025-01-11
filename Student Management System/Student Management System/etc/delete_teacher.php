<?php
require('../database.php'); // Ensure database connection is established
include('functions.php'); // Include the function for logging activities
session_start(); // Ensure session is active

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];

    // Fetch teacher details before deletion for logging purposes
    $fetch_query = "SELECT name, last_name FROM teachers WHERE teacher_id = ?";
    $fetch_stmt = mysqli_prepare($conn, $fetch_query);
    mysqli_stmt_bind_param($fetch_stmt, "i", $teacher_id);
    mysqli_stmt_execute($fetch_stmt);
    $result = mysqli_stmt_get_result($fetch_stmt);
    $teacher = mysqli_fetch_assoc($result);

    if ($teacher) {
        // Delete the teacher
        $delete_query = "DELETE FROM teachers WHERE teacher_id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $teacher_id);

        if (mysqli_stmt_execute($stmt)) {
            // Log the activity
            $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
            $activityDescription = "Deleted teacher: " . $teacher['first_name'] . " " . $teacher['last_name'];
            logActivity($conn, $userName, $activityDescription);

            // Redirect to the teacher page with success message
            header("Location: teacher.php?message=Teacher deleted successfully");
            exit;
        } else {
            echo "Error deleting teacher: " . mysqli_error($conn);
        }
    } else {
        echo "Teacher not found.";
    }
}
?>

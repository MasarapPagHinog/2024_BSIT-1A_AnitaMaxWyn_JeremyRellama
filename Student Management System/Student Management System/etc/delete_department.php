<?php
require('../database.php'); // Ensure database connection
include('functions.php'); // Include logging function
session_start(); // Start session

// Check if the request is a POST and contains department_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['department_id'])) {
    $department_id = intval($_POST['department_id']); // Sanitize department_id input

    // Fetch the department details for logging before deletion
    $fetch_query = "SELECT department FROM departments WHERE department_id = ?";
    $fetch_stmt = mysqli_prepare($conn, $fetch_query);
    mysqli_stmt_bind_param($fetch_stmt, "i", $department_id);
    mysqli_stmt_execute($fetch_stmt);
    $result = mysqli_stmt_get_result($fetch_stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $department = mysqli_fetch_assoc($result);
        $departmentName = $department['department'];
    } else {
        $departmentName = "Unknown Department";
    }

    // SQL query to delete the department
    $delete_query = "DELETE FROM departments WHERE department_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $department_id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Log the activity
        $userName = $_SESSION['username'] ?? 'Unknown User';
        $activityDescription = "Deleted department: " . $departmentName;
        logActivity($conn, $userName, $activityDescription);

        // Redirect to the departments page with a success message
        header("Location: teacher.php?message=Department deleted successfully");
        exit;
    } else {
        echo "Error deleting department: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>

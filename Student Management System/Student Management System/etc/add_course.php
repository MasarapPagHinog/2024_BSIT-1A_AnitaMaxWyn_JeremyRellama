<?php
require('../database.php'); // Ensure $conn initializes mysqli connection
include('functions.php'); // Include the function for logging activities
session_start(); // Ensure session is started

// Initialize error/success message
$message = ''; // Fix: Initialize the $message variable

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    // Convert course name and code to uppercase
    $course_name = strtoupper(mysqli_real_escape_string($conn, $_POST['course_name']));
    $course_code = strtoupper(mysqli_real_escape_string($conn, $_POST['course_code']));
    
    // Get the course description
    $description = mysqli_real_escape_string($conn, $_POST['description']); // Accept description input

    // Validate course code input
    if (empty($course_code)) {
        $message = "Course code is required.";
    } else {
        // Check if course already exists
        $check_query = "SELECT * FROM courses WHERE course = '$course_name' OR course_code = '$course_code'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Error: A course with the same name or course code already exists.";
        } else {
            // Insert course into the database
            $insert_query = "INSERT INTO courses (course, course_code, description) VALUES ('$course_name', '$course_code', '$description')";
            if (mysqli_query($conn, $insert_query)) {
                $message = "Course added successfully!";

                // Log activity
                $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
                $activityDescription = "Added a new course: $course_name ($course_code)";
                logActivity($conn, $userName, $activityDescription);
            } else {
                $message = "Error adding course: " . mysqli_error($conn);
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course - Student Management System</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/add_course.css">
  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
    </style>


</head>
<body>
    <main>
    <section>
        <div class="group_header">
            <h1>Add <span> New Course </span></h1>
            <p>Fill out the form below to add a new course to the system.</p>
        </div>

        <?php if (!empty($message)): ?>
    <div class="message <?= strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
        <p><?= htmlspecialchars($message); ?></p>
    </div>
<?php endif; ?>


        <form method="POST" action="add_course.php">
            <div>
                <label for="course_name">Course Name:</label>
                <input type="text" name="course_name" id="course_name" required>
            </div>
            <div>
                <label for="course_code">Course Code:</label>
                <input type="text" name="course_code" id="course_code" required>
            </div>
             <div>
                <label for="description">Course Description:</label>
                <input type="text" name="description" id="description" required>
            </div>
            <button type="submit" name="add_course">Add Course</button>
        </form>

        <div class="back_button">
    <button id="closeFormButton" onclick="window.location.href='students.php';">
        Go Back
    </button>
</div>


    </section>

    <section></section>

    </main>
</body>
</html>

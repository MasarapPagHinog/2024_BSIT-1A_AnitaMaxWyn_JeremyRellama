<?php
require('../database.php'); // Ensure $conn initializes mysqli connection
require('../signin/session_check.php');

// Check if a course is selected
$selected_course = isset($_GET['course']) ? urldecode($_GET['course']) : null;

// Redirect if no course is selected
if (!$selected_course) {
    header("Location: students.php");
    exit;
}

$message = "";
$student_details = null;
$is_already_enrolled = false; // Flag to track enrollment status

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_another_student'])) {
        // Reset student details to allow searching for another student
        $student_details = null;
        $message = "You can now search for another student.";
    } else {
        $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';

        if ($first_name && $last_name) {
            // Query to find the student by name
            $student_query = "SELECT * FROM students WHERE first_name = ? AND last_name = ?";
            $stmt = mysqli_prepare($conn, $student_query);
            mysqli_stmt_bind_param($stmt, "ss", $first_name, $last_name);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $student_details = mysqli_fetch_assoc($result);

                // Check if the student is already enrolled in the selected course
                if ($student_details['course'] === $selected_course) {
                    $is_already_enrolled = true;
                    $message = "The student is already enrolled in the selected course.";
                }
            } else {
                $message = "No student found with the name '{$first_name} {$last_name}'.";
            }
        } else {
            $message = "Both First Name and Last Name are required.";
        }
    }
}

// Handle confirm addition
if (isset($_POST['confirm_add']) && $student_details && !$is_already_enrolled) {
    $update_query = "UPDATE students SET course = ? WHERE student_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "si", $selected_course, $student_details['student_id']);

    if (mysqli_stmt_execute($update_stmt)) {
        $message = "Student successfully added to the course!";
        $student_details = null; // Clear details after successful addition
    } else {
        $message = "Error adding student to the course: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - <?= htmlspecialchars($selected_course); ?></title>
    <link rel="stylesheet" href="/Student Management System/etc/css/enroll_student.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
     <!-- fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

</head>
<body>
    <main>
        <section>
            <div class="group_header">
                <h1>Add Student to <?= htmlspecialchars($selected_course); ?></h1>
                <p>Search for a student or confirm their addition to the course.</p>
            </div>

            <?php if ($message): ?>
                <div class="message">
                    <p><?= htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!$student_details): ?>
                <form method="POST">
                    <div>
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name" required>
                    </div>
                    <div>
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" required>
                    </div>
                    <button type="submit">Search Student</button>
                </form>
            <?php else: ?>
                <div>
                    <h3>Confirm Adding Student</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($student_details['first_name'] . ' ' . $student_details['last_name']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="confirm_add" value="1">
                        <button type="submit" <?= $is_already_enrolled ? 'disabled' : ''; ?>>
                            <?= $is_already_enrolled ? 'Already Enrolled' : 'Confirm Add'; ?>
                        </button>
                    </form>
                    <a href="add_student.php?course=<?= urlencode($selected_course); ?>" class="add-another-button">Add Another Student</a>
                </div>
            <?php endif; ?>

            <a href="students.php" class="back-button">Go Back</a>
        </section>

        <section></section>
    </main>
</body>
</html>

<?php
require('../database.php'); // Ensure the connection to the database is established
require('../signin/session_check.php'); // Ensure the session is active
include('functions.php'); // Include the function for logging activities

// Fetch the course from the URL
$course = isset($_GET['course']) ? urldecode($_GET['course']) : null;

// If the form is submitted, process the input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    $age = intval($_POST['age']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);
    $year = intval($_POST['year']);
    $GWA = floatval($_POST['GWA']);

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($course)) {
        $error_message = "First name, last name, and course are required.";
    } else {
        // Insert the new student into the database
        $insert_query = "INSERT INTO students (first_name, last_name, middle_name, age, phone_number, address, course, year, GWA) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssisssid", $first_name, $last_name, $middle_name, $age, $phone_number, $address, $course, $year, $GWA);

        if (mysqli_stmt_execute($stmt)) {
            // Log the activity
            $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
            $activityDescription = "Added a new student: $first_name $last_name to the course $course";
            logActivity($conn, $userName, $activityDescription);

            // Redirect back to the course page with a success message
            header("Location: students.php?course=" . urlencode($course) . "&message=Student added successfully");
            exit;
        } else {
            $error_message = "Error adding student: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>

    <link rel="stylesheet" href="/Student Management System/etc/css/add_student.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
  <main>

  <section></section>

  <section>

    <h1>Add Student to Course: <span> <?= htmlspecialchars($course); ?> </span></h1>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>


     <div class="form-container">
        <h2>Student Information</h2>
        <form action="add_student.php?course=<?= urlencode($course); ?>" method="POST">
            <label>First Name:
                <input type="text" name="first_name" required>
            </label>
            <label>Last Name:
                <input type="text" name="last_name" required>
            </label>
            <label>Middle Name:
                <input type="text" name="middle_name">
            </label>
            <label>Age:
                <input type="number" name="age" min="1" required>
            </label>
            <label>Phone Number:
                <input type="text" name="phone_number">
            </label>
            <label>Address:
                <input type="text" name="address">
            </label>
            <label>Year:
                <select name="year" required>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </label>
            <label>GWA:
                <input type="number" step="0.01" name="GWA">
            </label>
            <button type="submit">Add Student</button>
        </form>
        <a href="students.php?course=<?= urlencode($course); ?>">Back to Students</a>
    </div>
    </main>
  </section>
</body>
</html>

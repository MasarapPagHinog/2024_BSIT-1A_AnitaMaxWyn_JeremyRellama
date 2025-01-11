<?php
require('../database.php'); // Include database connection
include('functions.php'); // Include the function for logging activities
session_start(); // Start the session to access username

// Check if teacher ID is provided
if (!isset($_GET['id'])) {
    echo "No teacher selected.";
    exit;
}

$teacher_id = $_GET['id'];
$message = '';

// Fetch the teacher's current details
$query = "SELECT * FROM teachers WHERE teacher_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $teacher_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $teacher = mysqli_fetch_assoc($result);
} else {
    echo "Teacher not found.";
    exit;
}

// Handle form submission for updating teacher details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $department = $_POST['department'];
    $hire_date = $_POST['hire_date'];

    $update_query = "UPDATE teachers 
                     SET name = ?, last_name = ?, middle_name = ?, email = ?, phone_number = ?, department = ?, hire_date = ?
                     WHERE teacher_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssssss", $first_name, $last_name, $middle_name, $email, $phone_number, $department, $hire_date, $teacher_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Teacher details updated successfully.";

        // Log the activity
        $userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';
        $activityDescription = "Updated teacher details: " . $first_name . " " . $last_name;
        logActivity($conn, $userName, $activityDescription);
    } else {
        $message = "Error updating teacher: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Teacher</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/update_teacher.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>


<body>
<header></header>


<main>
<section>

  <div class="signin_header">
<h1>Update <span> Teacher Details </span></h1>
<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quo, nisi.</p>
</div>

    <form method="POST" action="">
        
        <?php if ($message): ?>
            <p style="text-align:center; color: green;"><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($teacher['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($teacher['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($teacher['middle_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($teacher['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($teacher['phone_number']); ?>" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" id="department" name="department" value="<?= htmlspecialchars($teacher['department']); ?>" required>
        </div>
        <div class="form-group">
            <label for="hire_date">Hire Date</label>
            <input type="date" id="hire_date" name="hire_date" value="<?= htmlspecialchars($teacher['hire_date']); ?>" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="update-btn">Update</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='teacher.php'">Cancel</button>
        </div>
    </form>


    </section>

    <section>

    </section>

    </main>
</body>
</html>

<?php
session_start();
require('../database.php'); // Include database connection
include('functions.php'); // Include the function for logging activities

// Check if student ID is provided
if (!isset($_GET['id'])) {
    echo "No student selected.";
    exit;
}

$student_id = $_GET['id'];
$message = '';

// Fetch the student's current details
$query = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
} else {
    echo "Student not found.";
    exit;
}

// Handle form submission for updating student details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $age = $_POST['age'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $year = $_POST['year'];
    $GWA = $_POST['GWA'];

    $update_query = "UPDATE students 
                    SET first_name = ?, last_name = ?, middle_name = ?, age = ?, phone_number = ?, address = ?, year = ?, GWA = ?
                    WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssssssd", $first_name, $last_name, $middle_name, $age, $phone_number, $address, $year, $GWA, $student_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Student details updated successfully.";

        // Log the activity
        $userName = $_SESSION['username']; // Assuming the username is stored in the session
        $activityDescription = "Updated student details: " . $first_name . " " . $last_name;
        logActivity($conn, $userName, $activityDescription);
    } else {
        $message = "Error updating student: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/update_student.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<header></header>

<main>
<section>
  <div class="header">
    <h1>Update <span> Student Details </span></h1>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quo, nisi.</p>
  </div>

  <form method="POST" action="">
      <?php if ($message): ?>
          <p style="text-align:center; color: green;"><?= htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <div class="form-group">
          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($student['first_name']); ?>" required>
      </div>
      <div class="form-group">
          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($student['last_name']); ?>" required>
      </div>
      <div class="form-group">
          <label for="middle_name">Middle Name</label>
          <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($student['middle_name']); ?>" required>
      </div>
      <div class="form-group">
          <label for="age">Age</label>
          <input type="number" id="age" name="age" value="<?= htmlspecialchars($student['age']); ?>" required>
      </div>
      <div class="form-group">
          <label for="phone_number">Phone Number</label>
          <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($student['phone_number']); ?>" required>
      </div>
      <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" value="<?= htmlspecialchars($student['address']); ?>" required>
      </div>
      <div class="form-group">
          <label for="year">Year</label>
          <input type="text" id="year" name="year" value="<?= htmlspecialchars($student['year']); ?>" required>
      </div>
      <div class="form-group">
          <label for="GWA">GWA</label>
          <input type="number" step="0.01" id="GWA" name="GWA" value="<?= htmlspecialchars($student['GWA']); ?>" required>
      </div>
      <div class="form-actions">
          <button type="submit" class="update-btn">Update</button>
          <button type="button" class="cancel-btn" onclick="window.location.href='students.php'">Cancel</button>
      </div>
  </form>


  <div class="back_button">
    <button onclick="window.location.href='students.php'">Go Back</button>
</div>

</section>

<section></section>
</main>
</body>
</html>

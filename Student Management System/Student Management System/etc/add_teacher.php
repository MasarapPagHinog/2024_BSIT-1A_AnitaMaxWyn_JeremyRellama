<?php
require('../database.php'); // Ensure the connection to the database is established
require('../signin/session_check.php'); // Ensure the session is active

// Fetch the department from the URL
$department = isset($_GET['department']) ? urldecode($_GET['department']) : null;

// If the form is submitted, process the input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $hire_date = trim($_POST['hire_date']); // Assuming a date field for hire date

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($department) || empty($email)) {
        $error_message = "First name, last name, email, and department are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Insert the new teacher into the database
        $insert_query = "INSERT INTO teachers (name, last_name, middle_name, email, phone_number, department, hire_date) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $last_name, $middle_name, $email, $phone_number, $department, $hire_date);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the department page with a success message
            header("Location: teacher.php?department=" . urlencode($department) . "&message=Teacher added successfully");
            exit;
        } else {
            $error_message = "Error adding teacher: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/add_teacher.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
<main>

<section></section>

<section>
    <h1>Add Teacher to Department: <span> <?= htmlspecialchars($department); ?> </span> </h1>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <div class="form-container">
        <h2>Teacher Information</h2>
    <form action="add_teacher.php?department=<?= urlencode($department); ?>" method="POST">
        <label>First Name: <input type="text" name="first_name" required></label><br>
        <label>Last Name: <input type="text" name="last_name" required></label><br>
        <label>Middle Name: <input type="text" name="middle_name"></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Phone Number: <input type="text" name="phone_number"></label><br>
        <label>Hire Date: <input type="date" name="hire_date" required></label><br>
        <button type="submit">Add Teacher</button>
    </form>
    <a href="teacher.php?department=<?= urlencode($department); ?>">Back to Teachers</a>
    </div>
    </section>
</main>
</body>
</html>

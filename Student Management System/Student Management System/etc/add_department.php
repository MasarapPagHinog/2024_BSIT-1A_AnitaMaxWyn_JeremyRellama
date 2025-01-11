<?php
require('../database.php'); // Include database connection
require('../signin/session_check.php'); // Check user session


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = trim($_POST['department']);
    $department_code = trim($_POST['department_code']);
    $date_added = date('Y-m-d'); // Get the current date

    // Validate inputs
    if (empty($department) || empty($department_code)) {
        $error_message = "Both Department Name and Department Code are required.";
    } else {
        // Insert into the database
        $insert_query = "INSERT INTO departments (department, department_code, date_added) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sss", $department, $department_code, $date_added);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Department added successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/add_department.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
<main>
<section></section>

    <section>
        <div class="form-container">
            <h1>Add a <span>New Department</span></h1>
            <p>Fill out the form below to add a new department.</p>

            <?php if (!empty($success_message)): ?>
                <div class="success-message"><?= htmlspecialchars($success_message); ?></div>
            <?php elseif (!empty($error_message)): ?>
                <div class="error-message"><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form action="add_department.php" method="POST">
                <label for="department">Department Name:</label>
                <input type="text" id="department" name="department" required><br>

                <label for="department_code">Department Code:</label>
                <input type="text" id="department_code" name="department_code" required><br>

                <button type="submit">Add Department</button>
            </form>

            <div class="back-button">
                <button onclick="window.location.href='teacher.php'">Back to Departments</button>
            </div>
        </div>
    </section>
</main>
</body>
</html>

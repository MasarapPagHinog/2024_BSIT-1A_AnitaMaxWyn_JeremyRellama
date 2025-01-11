<?php
include("../database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    $course = isset($_POST['course']) ? trim($_POST['course']) : null;
    $department = isset($_POST['department']) ? trim($_POST['department']) : null;

    // Input validation
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        header("Location: signup.php?message=All fields are required.");
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?message=Invalid email format.");
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?message=Email already exists. Please use a different email.");
        exit;
    }

    // Prepare course or department data based on role
    if ($role === 'student') {
        $insertQuery = "INSERT INTO users (username, email, password, course, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $course, $role);
    } elseif ($role === 'teacher') {
        $insertQuery = "INSERT INTO users (username, email, password, department, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $department, $role);
    } else {
        header("Location: signup.php?message=Invalid role selected.");
        exit;
    }

    // Execute the query
    if ($stmt->execute()) {
        header("Location: signup.php?message=Signup successful!");
    } else {
        header("Location: signup.php?message=Error: Unable to complete signup. Please try again later.");
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

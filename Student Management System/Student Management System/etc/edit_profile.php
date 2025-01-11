<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../signin/signin.php");
    exit;
}

// Include the database connection
include("../database.php");

// Fetch the logged-in user's details
$user_id = $_SESSION["user_id"];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If no user is found, redirect to login (additional security)
if (!$user) {
    session_destroy();
    header("Location: ../signin/signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/edit_profile.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="edit-profile-container">
    <div class="container">



        <div class="profile-header">
            <h1>Edit Profile</h1>
            <p>Update your account information below.</p>
        </div>
        <form action="update_profile.php" method="POST" class="edit-profile-form">
            <label>
                Username:
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
            </label>
            <label>
                Email:
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
            </label>
            <label>
                Password:
                <input type="password" name="password" placeholder="Enter new password (optional)">
            </label>
            <div class="form-buttons">
                <button type="submit" class="save-btn">Save Changes</button>
                <a href="index.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
        <div class="shape-blob"></div>
	<div class="shape-blob one"></div>
	<div class="shape-blob two"></div>
	<div class="shape-blob two"></div>
	<div class="shape-blob three"></div>
    </div>
    </div>
</body>
</html>


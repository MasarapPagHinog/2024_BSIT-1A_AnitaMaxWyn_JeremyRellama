<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../signin/signin.php");
    exit;
}

// Fetch logged-in user details
include("../database.php");
$user_id = $_SESSION["user_id"];

// Fetch user data securely
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // If user data isn't found, clear the session and redirect
    session_unset();
    session_destroy();
    header("Location: ../signin/signin.php");
    exit;
}

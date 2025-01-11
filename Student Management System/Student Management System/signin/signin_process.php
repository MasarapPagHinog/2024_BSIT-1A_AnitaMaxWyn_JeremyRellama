<?php
include("../database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if username and password are set
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        // Check if username or password is empty
        if (empty($username) || empty($password)) {
            header("Location: signin.php?message=Please fill in all fields.");
            exit;
        }

        // Prepare the SQL statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if user exists
        if ($user) {
            // Verify the hashed password
            if (password_verify($password, $user["password"])) {
                session_start();
                $_SESSION["user_id"] = $user["id"]; // Store the user's ID in the session
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                // Redirect to the index page
                header("Location: ../etc/index.php");
                exit;
            } else {
                header("Location: signin.php?message=Incorrect password. Please try again.");
                exit;
            }
        } else {
            header("Location: signin.php?message=Username does not exist.");
            exit;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        header("Location: signin.php?message=Please fill in all fields.");
        exit;
    }
}
?>

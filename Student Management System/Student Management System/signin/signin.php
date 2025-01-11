<?php
include("../database.php");
session_start(); // Start the session to track the logged-in user
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <link rel="stylesheet" href="signin.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

<style>
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
            color: white;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>

  <main>
    <section>

      <div class="signin_header">
      <h1> <span>Sign in</span>  your Account</h1>
      <p>Sign in to access your account</p>
    </div>


          <!-- Message Display Section -->
          <?php if (isset($_GET['message'])): ?>
            <?php
                $message = htmlspecialchars($_GET['message']);
                $messageClass = strpos($message, 'successful') !== false ? 'success' : 'error';
            ?>
            <div class="message <?php echo $messageClass; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

      <form action="signin_process.php" method="post">
      <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Password" required>
    <br>
    <input type="submit" name="login" value="Sign In">

        <div class="signin_group">
        <p>Dont have an account? <a href="../signup/signup.php">Sign up</a></p>
      </div>

      </form>
  
    </section>

    <section></section>

  </main>

  <footer></footer>
</body>
</html>
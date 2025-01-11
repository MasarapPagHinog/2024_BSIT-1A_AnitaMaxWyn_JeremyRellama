<?php
 include ("../database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <link rel="stylesheet" href="signup.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

<style>
    .hidden {
      display: none;
    }

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
    <!-- <h1>Welcome</h1> -->
  </section>

  <section>

<div class="signup_header">
  <h1><span>Sign up</span> for an Account</h1>
  <p>Register your Account</p>
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

<form action="signup_process.php" method="post">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username" required><br>

  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required><br>

  <label for="password">Password:</label>
  <input type="password" id="password" name="password" required><br>

  <div class="radio_group">
    <p>You are?</p>
    <div class="radio_button">
        <input type="radio" id="student" name="role" value="student" required>
        <label class="icon_student" for="student">Student</label>

        <input type="radio" id="teacher" name="role" value="teacher" required>
        <label class="icon_teachers" for="teacher">Teacher</label>
    </div>
  </div>
  
  <!-- Course Field -->
  <div id="course-field" class="hidden">
    <label for="course">Course:</label>
    <input type="text" id="course" name="course"><br>
  </div>

  <!-- Department Field -->
  <div id="department-field" class="hidden">
    <label for="department">Department:</label>
    <input type="text" id="department" name="department"><br>
  </div>

  <input type="submit" value="Sign Up">
  <div class="login_group">
    <p>Already have an Account?<a href="../signin/signin.php">Signin</a></p>
  </div>

</form>

</section>

</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
const studentRadio = document.getElementById("student");
const teacherRadio = document.getElementById("teacher");
const courseField = document.getElementById("course-field");
const departmentField = document.getElementById("department-field");

// Event listeners for radio buttons
studentRadio.addEventListener("change", () => {
  if (studentRadio.checked) {
    courseField.classList.remove("hidden");
    departmentField.classList.add("hidden");
  }
});

teacherRadio.addEventListener("change", () => {
  if (teacherRadio.checked) {
    departmentField.classList.remove("hidden");
    courseField.classList.add("hidden");
  }
});
});
</script>

</body>
</html>
<?php
require('../database.php'); // Ensure this initializes $conn using mysqli
require("../signin/session_check.php");
// Initialize an array for courses
$courses = [];

// Fetch all courses
$course_query = "SELECT * FROM courses";
$course_result = mysqli_query($conn, $course_query);
if ($course_result) {
    $courses = mysqli_fetch_all($course_result, MYSQLI_ASSOC);
} else {
    echo "Error fetching courses: " . mysqli_error($conn);
}
?>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const burgerMenu = document.querySelector(".burger-menu");
    const aside = document.querySelector("aside");

    burgerMenu.addEventListener("click", () => {
      aside.classList.toggle("open");
    });
  });
</script>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/students.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <main>
    <aside>
  <div class="burger-menu">
    <div class="line"></div>
    <div class="line"></div>
    <div class="line"></div>
  </div>

  <div class="nav_group">
    <div class="header">
      <h2>Student Management System</h2>
      <p>Simplify student records, track academic progress, and manage tasks efficiently—all in one place.</p>
    </div>

    <div class="nav_list">
  <ul>
    <li class="icon_dashboard"><a href="index.php" >Dashboard</a></li>
    <li class="icon_students"><a href="students.php" class="active" >Courses</a></li>
    <li class="icon_teachers"><a href="teacher.php">Departments</a></li>
    <li class="all_students"><a href="all_students.php">Students</a></li>
    <li class="all_teachers"><a href="all_teachers.php">Teachers</a></li>
  </ul>
</div>

  </div>

  <div class="user_profile_container">
    <div class="user-profile">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRUuR6lY1HPFS4Q_R2A5r70ECdchXmR_n1b8g&s" alt="Profile">
      <div class="name">
        <h3><?= htmlspecialchars($user['username']); ?></h3>
        <p><?= htmlspecialchars(ucfirst($user['role'])); ?></p>
        <a href="edit_profile.php" class="edit-profile-btn">Edit Profile</a>
      </div>
    </div>
    <div class="logout">
      <form method="POST" action="../signin/logout.php">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>
</aside>

        <section>
            <div class="flex">
                <div class="group_header">
                    <h1>Courses</h1>
                    <p>Select a course to view its students.</p>
                </div>

                <div class="add_course_btn">
                <?php if ($user['role'] ==='admin') : ?>
                <button type="button" onclick="window.location.href='../etc/add_course.php'">
                <svg xmlns="http://www.w3.org/2000/svg" width="3.2em" height="3.2em" viewBox="0 0 24 24"><path fill="" fill-rule="evenodd" d="M2.07 5.008C2 5.376 2 5.818 2 6.7v7.05c0 3.771 0 5.657 1.172 6.828S6.229 21.75 10 21.75h4c3.771 0 5.657 0 6.828-1.172S22 17.521 22 13.75v-2.202c0-2.632 0-3.949-.77-4.804a3 3 0 0 0-.224-.225c-.855-.769-2.172-.769-4.804-.769h-.374c-1.153 0-1.73 0-2.268-.153a4 4 0 0 1-.848-.352c-.488-.271-.896-.68-1.712-1.495l-.55-.55c-.274-.274-.41-.41-.554-.53a4 4 0 0 0-2.18-.903c-.186-.017-.38-.017-.766-.017c-.883 0-1.324 0-1.692.07A4 4 0 0 0 2.07 5.007M12 11a.75.75 0 0 1 .75.75V13H14a.75.75 0 0 1 0 1.5h-1.25v1.25a.75.75 0 0 1-1.5 0V14.5H10a.75.75 0 0 1 0-1.5h1.25v-1.25A.75.75 0 0 1 12 11" clip-rule="evenodd"/></svg>
                  </button>
                  <?php endif; ?>
                </div>





                


            </div>

            <div class="student_container">
          <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>

              <div class="folder">
              
              <?php if ($user['role'] ==='admin') : ?>
              <!-- Delete Course Icon -->
              <div class="remove_course">
                <form action="delete_course.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?')">
                <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['course_id']); ?>">
                <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="" height="" viewBox="0 0 24 24">
                  <path fill="" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2zM18 4h-2.5l-.71-.71c-.18-.18-.44-.29-.7-.29H9.91c-.26 0-.52.11-.7.29L8.5 4H6c-.55 0-1 .45-1 1s.45 1 1 1h12c.55 0 1-.45 1-1s-.45-1-1-1"/>
                  </svg>
                </button>
                </form>
              </div>
              <?php endif; ?>


                <!-- Course Details -->
                <svg xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 24 24">
                    <path fill="#202020" d="M16 8c0 2.21-1.79 4-4 4s-4-1.79-4-4l.11-.94L5 5.5L12 2l7 3.5v5h-1V6l-2.11 1.06zm-4 6c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4"/>
                </svg>

                <h2><?= htmlspecialchars($course['course']); ?></h2>
                <p>(<?= htmlspecialchars($course['description']); ?>)</p>

              <div class="open_btn">
                <button class="add-student-btn" onclick="window.location.href='view_students.php?course=<?= urlencode($course['course']); ?>'">Open Folder</button>
              </div>
                
            </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No courses available.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>

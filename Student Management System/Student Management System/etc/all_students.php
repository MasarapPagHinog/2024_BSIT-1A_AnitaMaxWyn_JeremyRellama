<?php
require('../database.php'); // Ensure this initializes $conn using mysqli
require("../signin/session_check.php");

$total_students = 0;

// Calculate total students while grouping them by year
$students_by_year = [];
$student_query = "SELECT * FROM students ORDER BY year ASC";
$student_result = mysqli_query($conn, $student_query);

if ($student_result) {
    while ($student = mysqli_fetch_assoc($student_result)) {
        $students_by_year[$student['year']][] = $student;
        $total_students++; // Increment the total count
    }
} else {
    echo "Error fetching students: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="/Student Management System/etc/css/all_students.css">
    <link rel="stylesheet" href="/Student Management System/etc/css/aside.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
      @media print {
        body * {
            visibility: hidden; /* Hide everything initially */
        }
        section, section * {
            visibility: visible; /* Show only the student list section */
        }
        section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        /* Hide unnecessary buttons like Print and Edit in print view */
        .print-button, .add-student, .action_btn_container {
            display: none;
        }
    }
    </style>

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
    <li class="icon_students"><a href="students.php" >Courses</a></li>
    <li class="icon_teachers"><a href="teacher.php">Departments</a></li>
    <li class="all_students"><a href="all_students.php" class="active">Students</a></li>
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
                    <h1>Students</h1>
                    <p>Select a course to view its students.</p>
                </div>
            </div>

<div class="total-students">
    <h2>Total Students: <?= htmlspecialchars($total_students); ?></h2>
</div>

      

            <div class="student-list">
    <?php if (!empty($students_by_year)) : ?>
        <?php foreach ($students_by_year as $year => $students) : ?>
            <div class="year-group">
                <h2>Year: <?= htmlspecialchars($year); ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Middle Name</th>
                            <th>Age</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>GWA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student) : ?>
                            <tr>
                                <td><?= htmlspecialchars($student['first_name']); ?></td>
                                <td><?= htmlspecialchars($student['last_name']); ?></td>
                                <td><?= htmlspecialchars($student['middle_name']); ?></td>
                                <td><?= htmlspecialchars($student['age']); ?></td>
                                <td><?= htmlspecialchars($student['phone_number']); ?></td>
                                <td><?= htmlspecialchars($student['address']); ?></td>
                                <td><?= htmlspecialchars($student['course']); ?></td>
                                <td><?= htmlspecialchars($student['year']); ?></td>
                                <td><?= htmlspecialchars($student['GWA']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="no-students">
            <p>No students found.</p>
        </div>
    <?php endif; ?>
</div>

<div class="flex2" style="background: none;">
    <div class="back-button">
        <button onclick="window.location.href='index.php'">Back to Dashboard</button>
    </div>
        <!-- Print Button -->
        <?php if ($user['role'] == 'admin' || $user['role'] == 'teacher'): ?>   
    <div class="print-button-container">
        <button type="button" onclick="window.print()" class="print-button" style="background-color: #28a745;">
            Print List
        </button>
    </div>
<?php endif; ?>
</div>


            
        </section>
    </main>
</body>
</html>

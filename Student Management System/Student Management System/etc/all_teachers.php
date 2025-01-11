<?php
require('../database.php'); 
require('../signin/session_check.php');

// Fetch all teachers grouped by department
$query = "SELECT teacher_id, name, last_name, middle_name, email, phone_number, department, hire_date 
          FROM teachers 
          ORDER BY department, last_name";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching teachers: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Grouped by Department</title>
    <link rel="stylesheet" href="/Student Management System/etc/css/all_teachers.css">
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
                        <li class="icon_dashboard"><a href="index.php">Dashboard</a></li>
                        <li class="icon_students"><a href="students.php">Courses</a></li>
                        <li class="icon_teachers"><a href="teacher.php">Departments</a></li>
                        <li class="all_students"><a href="all_students.php">Students</a></li>
                        <li class="all_teachers"><a href="all_teachers.php" class="active">Teachers</a></li>
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
                    <h1>Teachers</h1>
                    <p>Here are the teachers grouped by department:</p>
                </div>
            </div>

            <div class="total-teachers">
              <h2>Total Teachers: <?= $result->num_rows;?></h2>
            </div>

            <?php
            $current_department = null;

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    if ($row['department'] !== $current_department):
                        if ($current_department !== null) echo '</tbody></table>'; // Close the previous table
                        $current_department = $row['department'];
                        echo "<h2>" . htmlspecialchars($current_department) . "</h2>";
                        echo "<table>
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Middle Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Hire Date</th>
                                    </tr>
                                </thead>
                                <tbody>";
                    endif;

                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['last_name']) . "</td>
                            <td>" . htmlspecialchars($row['middle_name']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['phone_number']) . "</td>
                            <td>" . htmlspecialchars($row['hire_date']) . "</td>
                          </tr>";
                endwhile;
                echo '</tbody></table>'; // Close the last table
            else:
                echo "<p>No teachers found.</p>";
            endif;
            ?>

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

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const burgerMenu = document.querySelector(".burger-menu");
            const aside = document.querySelector("aside");

            burgerMenu.addEventListener("click", () => {
                aside.classList.toggle("open");
            });
        });
    </script>
</body>
</html>

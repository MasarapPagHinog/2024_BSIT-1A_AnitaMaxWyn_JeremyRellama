<?php
require('../database.php'); 
require('../signin/session_check.php');

// Check if a department is selected
$selected_department = isset($_GET['department']) ? urldecode($_GET['department']) : null;

// Initialize teachers array
$teachers = [];

// If a department is selected, fetch teachers in that department
if ($selected_department) {
    $teacher_query = "SELECT * FROM teachers WHERE department = ?";
    $stmt = mysqli_prepare($conn, $teacher_query);
    mysqli_stmt_bind_param($stmt, "s", $selected_department);
    mysqli_stmt_execute($stmt);
    $teacher_result = mysqli_stmt_get_result($stmt);
    if ($teacher_result) {
        $teachers = mysqli_fetch_all($teacher_result, MYSQLI_ASSOC);
    } else {
        echo "Error fetching teachers: " . mysqli_error($conn);
    }
} else {
    echo "No department selected.";
    exit;
}


// Group teachers by department
$teachers_by_department = [];
foreach ($teachers as $teacher) {
    $department = $teacher['department'];
    if (!isset($teachers_by_department[$department])) {
        $teachers_by_department[$department] = [];
    }
    $teachers_by_department[$department][] = $teacher;
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

    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById("search-input");
        const searchButton = document.getElementById("search-button");

        searchInput.addEventListener("input", filterTeachers);
        searchButton.addEventListener("click", filterTeachers);

        function filterTeachers() {
            const searchTerm = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const firstName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                const lastName = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
                const middleName = row.querySelector("td:nth-child(4)").textContent.toLowerCase();

                if (firstName.includes(searchTerm) || lastName.includes(searchTerm) || middleName.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    });

</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers in <?= htmlspecialchars($selected_department); ?></title>
    <link rel="stylesheet" href="/Student Management System/etc/css/view_department.css">
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
    <li class="icon_students"><a href="students.php" >Courses</a></li>
    <li class="icon_teachers"><a href="teacher.php" class="active" >Departments</a></li>
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
            <h1>Teachers in <span><?= htmlspecialchars($selected_department); ?> </span></h1>
            <p>Here are the teachers grouped by department:</p>
        </div>

        
        </div>

    <div class="content_wrapper">
    <?php if($user['role'] == 'admin'): ?> 
        <div class="add-teacher">
        <a href="add_teacher.php?department=<?= urlencode($selected_department); ?>" title="Add Teacher">
            <svg xmlns="http://www.w3.org/2000/svg" width="4.8em" height="4.8em" viewBox="0 0 24 24">
                <path fill="" fill-rule="evenodd" d="M7.345 4.017a42.3 42.3 0 0 1 9.31 0c1.713.192 3.095 1.541 3.296 3.26a40.7 40.7 0 0 1 0 9.446c-.201 1.719-1.583 3.068-3.296 3.26a42.3 42.3 0 0 1-9.31 0c-1.713-.192-3.095-1.541-3.296-3.26a40.7 40.7 0 0 1 0-9.445a3.734 3.734 0 0 1 3.295-3.26M12 7.007a.75.75 0 0 1 .75.75v3.493h3.493a.75.75 0 1 1 0 1.5H12.75v3.493a.75.75 0 0 1-1.5 0V12.75H7.757a.75.75 0 0 1 0-1.5h3.493V7.757a.75.75 0 0 1 .75-.75" clip-rule="evenodd"/>
            </svg>
        </a>
    </div>
    <?php endif; ?>


    <div class="search-container">
    <input type="text" id="search-input" class="search-input" placeholder="Search by name...">
    <button id="search-button" class="search-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="" class="search-icon" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
        </svg>
    </button>
</div>



    </div>

    <?php if (empty($teachers_by_department)): ?>
    <div class="no-teachers">
        <p>No teachers available in this department.</p>
    </div>
<?php else: ?>
    <?php foreach ($teachers_by_department as $department => $teachers_in_department): ?>
        <div class="year-container">
            <div class="year-header">
                <h2>Department: <?= htmlspecialchars($department); ?></h2>
            </div>
            <div class="teacher-list">
                <table>
                    <thead>
                        <tr>
                            <th>Teacher ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Middle Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Department</th>
                            <th>Hire Date</th>

                            <?php if ($user['role'] == 'admin') : ?>
                            <th>Actions</th>
                            <?php endif; ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers_in_department as $teacher): ?>
                            <tr>
                                <td><?= htmlspecialchars($teacher['teacher_id']); ?></td>
                                <td><?= htmlspecialchars($teacher['name']); ?></td>
                                <td><?= htmlspecialchars($teacher['last_name']); ?></td>
                                <td><?= htmlspecialchars($teacher['middle_name']); ?></td>
                                <td><?= htmlspecialchars($teacher['email']); ?></td>
                                <td><?= htmlspecialchars($teacher['phone_number']); ?></td>
                                <td><?= htmlspecialchars($teacher['department']); ?></td>
                                <td><?= htmlspecialchars($teacher['hire_date']); ?></td>

                                <?php if ($user['role'] == 'admin'): ?>
                                <td class="actions">
                                    <a href="update_teacher.php?id=<?= urlencode($teacher['teacher_id']); ?>" class="update-btn">Update</a>
                                    <form action="delete_teacher.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher['teacher_id']); ?>">
                                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this teacher?')">Delete</button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<div class="flex2">
        <div class="back-button">
            <button onclick="window.location.href='teacher.php'">Back to Departments</button>
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

<?php
require('../database.php'); // Ensure this initializes $conn using mysqli
require('../signin/session_check.php');

// Initialize variables
$selected_course = isset($_GET['course']) ? urldecode($_GET['course']) : null;
$search_query = isset($_GET['search']) ? trim($_GET['search']) : null;
$students = [];

// Build the base query
$student_query = "SELECT * FROM students WHERE 1=1";
$params = [];
$types = "";

// Add course filtering if a course is selected
if ($selected_course) {
    $student_query .= " AND course = ?";
    $params[] = $selected_course;
    $types .= "s";
}

// Add search functionality
if ($search_query) {
    $student_query .= " AND (first_name LIKE ? OR last_name LIKE ? OR middle_name LIKE ? OR student_id LIKE ?)";
    $search_param = "%" . $search_query . "%";
    array_push($params, $search_param, $search_param, $search_param, $search_param);
    $types .= "ssss";
}

// Prepare and execute the query
$stmt = mysqli_prepare($conn, $student_query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$student_result = mysqli_stmt_get_result($stmt);

if ($student_result) {
    $students = mysqli_fetch_all($student_result, MYSQLI_ASSOC);
} else {
    echo "Error fetching students: " . mysqli_error($conn);
}

// Group students by year level
$students_by_year = [];
foreach ($students as $student) {
    $year = $student['year'];
    if (!isset($students_by_year[$year])) {
        $students_by_year[$year] = [];
    }
    $students_by_year[$year][] = $student;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students in <?= htmlspecialchars($selected_course ?? '', ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="/Student Management System/etc/css/view_students.css">
    <link rel="stylesheet" href="/Student Management System/etc/css/aside.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Quicksand:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
   

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
    <li class="icon_students"><a href="students.php" class="active">Courses</a></li>
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
            <h1>Students in <span><?= htmlspecialchars($selected_course ?? '', ENT_QUOTES, 'UTF-8'); ?> </span></h1>
            <p>Here are the students grouped by year level:</p>
        </div>

        <div class="action_btn_container">
                    <div class="add_student_oncourse">
                        <?php if ($user['role'] === 'admin'): ?>
                            <button type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3.8em" height="3.8em" viewBox="0 0 24 24"><path fill="" d="M9 14c1.381 0 2.631-.56 3.536-1.465C13.44 11.631 14 10.381 14 9s-.56-2.631-1.464-3.535C11.631 4.56 10.381 4 9 4s-2.631.56-3.536 1.465C4.56 6.369 4 7.619 4 9s.56 2.631 1.464 3.535A5 5 0 0 0 9 14m0 7c3.518 0 6-1 6-2c0-2-2.354-4-6-4c-3.75 0-6 2-6 4c0 1 2.25 2 6 2m12-9h-2v-2a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2"/></svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="content_wrapper">
                <?php if($user['role'] === 'admin'): ?>
                <div class="add-student">
                    <a href="add_student.php?course=<?= urlencode($selected_course ?? ''); ?>" title="Add Student">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4.8em" height="4.8em" viewBox="0 0 24 24">
                            <path fill="" fill-rule="evenodd" d="M7.345 4.017a42.3 42.3 0 0 1 9.31 0c1.713.192 3.095 1.541 3.296 3.26a40.7 40.7 0 0 1 0 9.446c-.201 1.719-1.583 3.068-3.296 3.26a42.3 42.3 0 0 1-9.31 0c-1.713-.192-3.095-1.541-3.296-3.26a40.7 40.7 0 0 1 0-9.445a3.734 3.734 0 0 1 3.295-3.26M12 7.007a.75.75 0 0 1 .75.75v3.493h3.493a.75.75 0 1 1 0 1.5H12.75v3.493a.75.75 0 0 1-1.5 0V12.75H7.757a.75.75 0 0 1 0-1.5h3.493V7.757a.75.75 0 0 1 .75-.75" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

<div class="search-container">
    <form method="GET" action="view_students.php">
        <input type="hidden" name="course" value="<?= htmlspecialchars($selected_course ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input 
            type="text" 
            name="search" 
            class="search-input" 
            placeholder="Search by name or student ID" 
            value="<?= htmlspecialchars($search_query ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="search-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="search-icon" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </form>
</div>

</div>

    <?php if (!empty($students_by_year)): ?>
    <?php foreach ($students_by_year as $year => $students_in_year): ?>
        <div class="year-container">
            <div class="year-header"><h2>Year Level: <?= htmlspecialchars($year ?? '', ENT_QUOTES, 'UTF-8'); ?></h2></div>
            <div class="student-list">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Middle Name</th>
                            <th>Age</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Year</th>
                            <th>GWA</th>
                            <?php if($user['role'] === 'admin'): ?>
                            <th>Actions</th>
                            <?php endif; ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students_in_year as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['middle_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['age'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['year'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($student['GWA'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                                <?php if($user['role'] === 'admin'): ?>    
                                <td class="actions">
                                <!-- Update Button -->
                                <a href="update_student.php?id=<?= urlencode($student['student_id'] ?? ''); ?>" class="update-btn">Update</a>

                                <!-- Remove Button -->
                                <form action="remove_student.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to remove this student from the course?')">Remove</button>
                                </form>

                                <!-- Drop Button -->
                                <form action="delete_student.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this student?')">Drop</button>
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
<?php else: ?>
    <div class="no-students">
        <h3>No students available in this course.</h3>
    </div>
<?php endif; ?>

<div class="flex2" style="background: none;">
    <div class="back-button">
        <button onclick="window.location.href='students.php'">Back to Courses</button>
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

    <!-- Add Student Modal -->
    <div id="add-student-modal" style="display: none;">
        <div class="modal-content">
            <h2>Add Student to <?= htmlspecialchars($selected_course); ?></h2>
            <form id="add-student-form">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first_name" required>
                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last_name" required>
                <button type="submit">Add Student</button>
                <button type="button" id="close-modal">Cancel</button>
            </form>
            <p id="add-student-error" style="color: red; display: none;"></p>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addStudentBtn = document.querySelector('.add_student_oncourse button');
            const modal = document.getElementById('add-student-modal');
            const closeModalBtn = document.getElementById('close-modal');
            const addStudentForm = document.getElementById('add-student-form');
            const errorDisplay = document.getElementById('add-student-error');

            // Open modal
            addStudentBtn.addEventListener('click', () => {
                modal.style.display = 'flex';
            });

            // Close modal
            closeModalBtn.addEventListener('click', () => {
                modal.style.display = 'none';
                errorDisplay.style.display = 'none';
                addStudentForm.reset();
            });

            // Handle form submission
            addStudentForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const firstName = document.getElementById('first-name').value;
                const lastName = document.getElementById('last-name').value;

                fetch('check_and_add_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ first_name: firstName, last_name: lastName, course: '<?= $selected_course; ?>' }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student added successfully!');
                        location.reload();
                    } else {
                        errorDisplay.textContent = data.error;
                        errorDisplay.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorDisplay.textContent = 'An error occurred. Please try again.';
                    errorDisplay.style.display = 'block';
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('edit-students-btn');
    const removeBtnContainers = document.querySelectorAll('.remove-btn-container');

    let isEditMode = false;

    // Toggle edit mode
    editBtn.addEventListener('click', function () {
        isEditMode = !isEditMode;

        // Show or hide the "Remove" buttons
        removeBtnContainers.forEach(container => {
            container.style.display = isEditMode ? 'table-cell' : 'none';
        });

        // Update button text
        editBtn.textContent = isEditMode ? 'Finish Editing' : 'Edit Students';
    });

    // Attach click event to dynamically created "Remove" buttons
    document.querySelectorAll('.remove-student-btn').forEach(button => {
        button.addEventListener('click', function () {
            const studentId = this.getAttribute('data-student-id');

            if (confirm('Are you sure you want to remove this student?')) {
                fetch('remove_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ student_id: studentId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student removed successfully!');
                        location.reload(); // Reload the page after removal
                    } else {
                        alert('Failed to remove student: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request. Please try again.');
                });
            }
        });
    });
});
        </script>

</body>
</html>

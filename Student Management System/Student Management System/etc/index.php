<!-- /// -->
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

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// if ($user) {
//     echo "<h1>Welcome, " . htmlspecialchars($user["username"]) . "!</h1>";
//     echo "<p>Your role: " . htmlspecialchars($user["role"]) . "</p>";
// } else {
//     echo "Error fetching user details.";
// }

// Fetch total students
$query_students = "SELECT COUNT(*) AS total_students FROM students";
$result_students = $conn->query($query_students);
$total_students = $result_students->fetch_assoc()['total_students'];

// Fetch total teachers
$query_teachers = "SELECT COUNT(*) AS total_teachers FROM teachers";
$result_teachers = $conn->query($query_teachers);
$total_teachers = $result_teachers->fetch_assoc()['total_teachers'];

// Fetch courses offered
$query_courses = "SELECT COUNT(DISTINCT course) AS total_courses FROM courses";
$result_courses = $conn->query($query_courses);
$total_courses = $result_courses->fetch_assoc()['total_courses'];


// Fetch recent activities
$recentActivityQuery = "SELECT activity_description, user_name, timestamp FROM recent_activities ORDER BY timestamp DESC LIMIT 5";
$recentActivityResult = $conn->query($recentActivityQuery);
$recentActivities = [];
while ($row = $recentActivityResult->fetch_assoc()) {
    $recentActivities[] = $row;
}



$graphQuery = "
    SELECT c.course AS course_name, COUNT(s.student_id) AS student_count
    FROM students s
    JOIN courses c ON s.course = c.course_id
    GROUP BY c.course_id
    ORDER BY student_count DESC";
$graphResult = $conn->query($graphQuery);

$graphLabels = [];
$graphData = [];
while ($row = $graphResult->fetch_assoc()) {
    $graphLabels[] = $row['course_name'];
    $graphData[] = $row['student_count'];
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
  <title>Document</title>
  <link rel="stylesheet" href="/Student Management System/etc/css/style.css">
  <link rel="stylesheet" href="/Student Management System/etc/css/aside.css">

  <!-- fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

</head>
<body>
  <nav></nav>

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
    <li class="icon_dashboard"><a href="index.php" class="active">Dashboard</a></li>
    <li class="icon_students"><a href="students.php" >Courses</a></li>
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




    <!-- BODY -->
    <section>
      
    <div class="flex">
    <div class="group_header">
    <h1>Dashboard</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam, consectetur.</p>
    </div>
    </div>
    
    <h1>Information</h1>

    <div class="container">
    <div class="container_box">
    <h2><?= htmlspecialchars($total_students); ?></h2>   
    <h3>Total Students</h3>
</div>

<div class="container_box">
    <h2><?= htmlspecialchars($total_teachers); ?></h2>
    <h3>Total Teachers</h3>
  </div>

  <div class="container_box">
    <h2><?= htmlspecialchars($total_courses); ?></h2>
    <h3>Courses Offered</h3>
  </div>
</div>


    <div class="activity_wrapper">
      <div class="activivty_log_container">
        <h1>Recent Activity</h1>
        <ul>
    <?php foreach ($recentActivities as $activity): ?>
        <li>
            <div class="activity_icon">
                <h2><?= htmlspecialchars($activity['user_name']); ?></h2>
                <p><?= htmlspecialchars($activity['activity_description']); ?></p>
                <small><?= htmlspecialchars(date("F j, Y, g:i a", strtotime($activity['timestamp']))); ?></small>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
</div>


      <div class="graph_container">
        <h1>Graph</h1>
        <div class="graph_wrapper">
          <canvas id="myChart"></canvas>
        </div>
      </div>
    </div>

    </section>   

    
  </main>


  <footer></footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Data for the graph
    const labels = <?= json_encode($graphLabels); ?>;
    const data = <?= json_encode($graphData); ?>;

    // Create the chart
    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Students by Course',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                },
                title: {
                    display: true,
                    text: 'Number of Students by Course'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Students'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Courses'
                    }
                }
            }
        }
    });
  });

</script>

</html>
<?php
function logActivity($conn, $userName, $activityDescription) {
    $query = "INSERT INTO recent_activities (user_name, activity_description, timestamp) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $userName, $activityDescription);
    $stmt->execute();
    $stmt->close();
}
?>

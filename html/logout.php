<?php
session_start();

// Create database connection
$conn = mysqli_connect("localhost", "root", "", "book_store");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Log the logout action if user is logged in
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $log_query = "INSERT INTO `logs` (user_id, ip_address, log_type, created_at) VALUES (?, ?, 'LOGOUT', CURRENT_TIMESTAMP)";
    $stmt = mysqli_prepare($conn, $log_query);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $ip_address);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error logging logout: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);
}

// Close database connection
mysqli_close($conn);

// Clear session data
$_SESSION = array();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
?>

<?php
// Include your database connection code here
include('dbcon.php');

if (isset($_GET['id_session'])) {
    $id_session = $_GET['id_session']; // Get the session ID from the URL

    // Delete the session from the 'sessions' table
    $delete_query = "DELETE FROM sessions WHERE id_sessions = $id_session";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        echo "Session deleted successfully.";
    } else {
        echo "Error deleting session: " . mysqli_error($con);
    }
} else {
    echo "Invalid session request.";
}

// Close the database connection
mysqli_close($con);
?>

<?php
// Include your database connection code here
include('dbcon.php');

if (isset($_POST['update'])) {
    $id_package = $_POST['id_package'];
    $id_session = $_POST['id_session']; // Get the session ID to update
    $session_start_date = $_POST['session_start_date'];
    $session_end_date = $_POST['session_end_date'];
    $available_spots_adult = $_POST['available_spots_adult'];
    $available_spots_child = $_POST['available_spots_child'];
    $price_package_adult = $_POST['price_package_adult'];
    $price_package_child = $_POST['price_package_child'];

    // Update session information
    $update_query = "UPDATE sessions SET session_start_date = ?, session_end_date = ?, available_spots_adult = ?, available_spots_child = ?, price_package_adult = ?, price_package_child = ? WHERE id_sessions = ?";
    $stmt = mysqli_prepare($con, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssiiiii', $session_start_date, $session_end_date, $available_spots_adult, $available_spots_child, $price_package_adult, $price_package_child, $id_session);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: details.php?id_package=$id_package");
            exit;
        } else {
            echo "Error updating session: " . mysqli_error($con);
        }
    } else {
        echo "Error preparing the session update statement: " . mysqli_error($con);
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($con);
?>

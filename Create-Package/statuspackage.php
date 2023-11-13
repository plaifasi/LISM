<?php
include('dbcon.php');

// Check if 'id_package' parameter is present in the URL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_package = $_POST['id_package']; // Access 'id_package' from $_POST
    $newStatus = $_POST['status_package']; // Access 'status_package' from $_POST

    // Check if there are existing bookings for sessions associated with this package
    $existingBookingsQuery = "SELECT COUNT(*) as bookingCount FROM bookings b
                              JOIN sessions s ON b.id_session = s.id_sessions
                              WHERE s.id_package = ?";
    $stmt = mysqli_prepare($con, $existingBookingsQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_package);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            $bookingCount = $row['bookingCount'];

            if ($bookingCount > 0) {
                // Bookings exist, prevent status change
                echo "Cannot change status. There are existing bookings for this package.";
            } else {
                // No existing bookings, allow status change
                // Update the 'status_package' in the 'package' table
                $updateStatusQuery = "UPDATE package SET status_package = ? WHERE id_package = ?";
                $stmt = mysqli_prepare($con, $updateStatusQuery);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $newStatus, $id_package);

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script>';
                        echo 'alert("status update sucsess fully");';
                        echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
                        echo '</script>';
                    } else {
                        echo "Error updating status: " . mysqli_error($con);
                    }

                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}
?>

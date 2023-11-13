<?php
date_default_timezone_set('Asia/Bangkok');
include('./authentication.php');
include('dbcon.php');

if (isset($_SESSION['id_users'])) {
    if (isset($_POST['id_package'])) {
        $id_package = $_POST['id_package']; // Retrieve the package ID
        $id_users = $_SESSION['id_users'];

        // Check if the form has been submitted for booking
        if (isset($_POST['session'], $_POST['seats_for_adult'], $_POST['seats_for_child'])) {
            // Retrieve user inputs
            $id_session = $_POST['session']; // Session ID
            $seats_for_adult = $_POST['seats_for_adult'];
            $seats_for_child = $_POST['seats_for_child'];

            // Get the current date for the booking
            $booking_date = date("Y-m-d H:i:s");

            // Calculate the expiration date (booking_date + 3 hours)
            $expiration_date = date("Y-m-d H:i:s", strtotime($booking_date . ' +3 hours'));

            // Check if there are available seats for adults and children
            $availability_query = "SELECT available_spots_adult, available_spots_child FROM sessions WHERE id_sessions = ?";
            $stmt_availability = mysqli_prepare($con, $availability_query);

            if ($stmt_availability) {
                mysqli_stmt_bind_param($stmt_availability, "i", $id_session);

                if (mysqli_stmt_execute($stmt_availability)) {
                    $result = mysqli_stmt_get_result($stmt_availability);
                    $row = mysqli_fetch_assoc($result);
                    $available_spots_adult = $row['available_spots_adult'];
                    $available_spots_child = $row['available_spots_child'];

                    // Check if there are enough available seats
                    if ($available_spots_adult >= $seats_for_adult && $available_spots_child >= $seats_for_child) {
                        // Update the available seats for adults and children
                        $new_available_spots_adult = $available_spots_adult - $seats_for_adult;
                        $new_available_spots_child = $available_spots_child - $seats_for_child;

                        $update_availability_query = "UPDATE sessions SET available_spots_adult = ?, available_spots_child = ? WHERE id_sessions = ?";
                        $stmt_update_availability = mysqli_prepare($con, $update_availability_query);

                        if ($stmt_update_availability) {
                            mysqli_stmt_bind_param($stmt_update_availability, "iii", $new_available_spots_adult, $new_available_spots_child, $id_session);

                            if (mysqli_stmt_execute($stmt_update_availability)) {
                                // The available seats have been updated
                                // Now, insert the booking details into the 'bookings' table
                                $insert_booking_query = "INSERT INTO bookings (id_session, id_users, booking_date, expiration_date, seats_for_adult, seats_for_child) VALUES (?, ?, ?, ?, ?, ?)";
                                $stmt_booking = mysqli_prepare($con, $insert_booking_query);

                                if ($stmt_booking) {
                                    mysqli_stmt_bind_param($stmt_booking, "issssi", $id_session, $id_users, $booking_date, $expiration_date, $seats_for_adult, $seats_for_child);

                                    if (mysqli_stmt_execute($stmt_booking)) {
                                        // The booking has been successfully inserted into the 'bookings' table
                                        $id_booking = mysqli_insert_id($con); // Retrieve the last inserted ID

                                        // You can add any additional logic or redirection as needed
                                        $_SESSION['session'] = $id_session;
                                        $_SESSION['seats_for_adult'] = $seats_for_adult;
                                        $_SESSION['seats_for_child'] = $seats_for_child;
                                        $_SESSION['id_booking'] = $id_booking; // Store id_booking in the session

                                        header("Location: confirmationinfo.php"); // Redirect to the confirmation page
                                        exit;
                                    } else {
                                        echo "Error inserting booking: " . mysqli_error($con);
                                    }

                                    mysqli_stmt_close($stmt_booking);
                                } else {
                                    echo "Error preparing statement for booking: " . mysqli_error($con);
                                }
                            } else {
                                echo "Error updating available seats: " . mysqli_error($con);
                            }

                            mysqli_stmt_close($stmt_update_availability);
                        } else {
                            echo "Error preparing statement for updating available seats: " . mysqli_error($con);
                        }
                    } else {
                        echo '<script>';
                        echo 'alert("Not enough available seats for this session.");';
                        echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
                        echo '</script>';
                        
                    }
                } else {
                    echo "Error retrieving available seats: " . mysqli_error($con);
                }

                mysqli_stmt_close($stmt_availability);
            } else {
                echo "Error preparing statement for checking available seats: " . mysqli_error($con);
            }
        }
    }

    // Close the database connection if not done already
    mysqli_close($con);
} else {
    header('Location: login.php');
    exit(0);
}

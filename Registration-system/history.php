<?php
session_start();
$main_css_file = "views/history.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');

include('dbcon.php');

// Query to retrieve booking history for the authenticated user, including package details
$booking_history_query = "SELECT b.id_booking, b.seats_for_adult, b.seats_for_child, b.booking_date,
                          s.id_sessions, s.id_package, s.session_start_date, s.session_end_date,
                          p.title_package, p.destination_package, p.description_package,
                          i.image_data,
                          pa.payment_status
                          FROM bookings b
                          INNER JOIN sessions s ON b.id_session = s.id_sessions
                          INNER JOIN package p ON s.id_package = p.id_package
                          LEFT JOIN images i ON p.image_id = i.image_id
                          LEFT JOIN payment pa ON s.id_sessions = pa.id_sessions
                          WHERE b.id_users = ?";

$stmt_booking_history = mysqli_prepare($con, $booking_history_query);

if ($stmt_booking_history) {
    mysqli_stmt_bind_param($stmt_booking_history, "i", $_SESSION['id_users']); // corrected session variable

    if (mysqli_stmt_execute($stmt_booking_history)) {
        $result = mysqli_stmt_get_result($stmt_booking_history);

        if (mysqli_num_rows($result) > 0) {
            echo '<section class="content">';
            echo '<div class="content-box">';
            echo '<div class="content-box-container">';
            echo '<div class="content-box-row">';

            while ($row = mysqli_fetch_assoc($result)) {
                $id_booking = $row['id_booking'];
                $id_package = $row['id_package'];
                $booking_date = $row['booking_date'];
                $seats_for_adult = $row['seats_for_adult'];
                $seats_for_child = $row['seats_for_child'];
                $session_start_date = $row['session_start_date'];
                $session_end_date = $row['session_end_date'];
                $title_package = $row['title_package'];
                $destination_package = $row['destination_package'];
                $description_package = $row['description_package'];
                $image_data = $row['image_data'];
                $payment_status = $row['payment_status'];

                echo '<div class="package">';
                echo '<div class="package-box">';
                echo '<p>Payment Status: ' . $payment_status . '</p>';
                echo '<p>ID Booking: ' . $id_booking . '</p>';
                echo '<p>Booking Date: ' . $booking_date . '</p>';
                echo '<p>Seats for Adults: ' . $seats_for_adult . '</p>';
                echo '<p>Seats for Children: ' . $seats_for_child . '</p>';
                echo '<p>Session Start Date: ' . $session_start_date . '</p>';
                echo '<p>Session End Date: ' . $session_end_date . '</p>';


                if (!empty($image_data)) {
                    // Display the image if available
                    $base64Image = base64_encode($image_data);
                    echo '<div class="img-content">';
                    echo '<div class="card-img">';
                    echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Package Image" style="max-width: 350px; max-height: 350px;">';
                    echo '</div>';
                    echo '</div>';
                }

                echo '<div class="card-content">';
                echo '<h4>' . $title_package . '</h4>';
                echo '<p>' . $description_package . '</p>';
                echo '<div class="btn-box">';
                echo "<a href='detailsinuser.php?id_package=$id_package' class=\"create-btn\">More detail</a>";
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
            echo '</section>';
        } else {
            echo "No booking history found for the authenticated user.";
        }
    } else {
        echo "Error executing the booking history query: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt_booking_history);
} else {
    echo "Error preparing statement for booking history: " . mysqli_error($con);
}
?>


<?php include('./includes/footer.php');
?>
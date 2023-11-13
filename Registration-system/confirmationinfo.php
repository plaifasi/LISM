<?php
date_default_timezone_set('Asia/Bangkok');
include('./authentication.php');

$main_css_file = "views/confirmationinfo.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');


?>

<div class="content-box">

<?php

if (isset($_SESSION['id_users'])) {
if (isset($_SESSION['session'], $_SESSION['seats_for_adult'], $_SESSION['seats_for_child'])) {
    $session = $_SESSION['session'];
    $seats_for_adult = $_SESSION['seats_for_adult'];
    $seats_for_child = $_SESSION['seats_for_child'];
    $id_booking = $_SESSION['id_booking']; // Replace with your actual session variable name

    // Include your database connection
    include('dbcon.php');

    // Query to fetch session details including start and end dates
    $session_query = "SELECT s.id_sessions, s.session_start_date, s.session_end_date, s.price_package_adult, s.price_package_child FROM sessions s
                     JOIN package p ON s.id_package = p.id_package
                     WHERE s.id_sessions = ?";
    $stmt = mysqli_prepare($con, $session_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $session);
    
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $session_data = mysqli_fetch_assoc($result);
    
            $id_sessions = $session_data['id_sessions'];
            $start_date = $session_data['session_start_date'];
            $end_date = $session_data['session_end_date'];
            $priceAdult = $session_data['price_package_adult'];
            $priceChild = $session_data['price_package_child'];
    
            $total_price = ($seats_for_adult * $priceAdult) + ($seats_for_child * $priceChild);
    
            // Display the user's inputs and total price
            echo '<div class="booking-summary">';
            echo '<h2>Booking Summary</h2>';
            
            echo '<p>Start Date: ' . $start_date . '</p>';
            echo '<p>End Date: ' . $end_date . '</p>';
            echo '<p>Seats for Adults: ' . $seats_for_adult . '</p>';
            echo '<p>Seats for Children: ' . $seats_for_child . '</p>';
            echo '<p class="total-price">Total Price: $' . number_format($total_price, 2) . '</p>';
            echo '</div>';
            // You can add additional formatting and styling as needed

            mysqli_stmt_close($stmt);
        } else {
            echo "Error executing session query: " . mysqli_error($con);
        }
    } else {
        echo "Error preparing session query: " . mysqli_error($con);
    }

    mysqli_close($con); // Close the database connection
} else {
    echo "No booking information found in the session.";
}
}else {
    echo 'id_user is not set in the session';
}
?>



<form action="process_booking.php" method="POST">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" value="<?php echo isset($first_name) ? $first_name : ''; ?>">

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" value="<?php echo isset($last_name) ? $last_name : ''; ?>">

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>">

    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" id="phone_number" value="<?php echo isset($phone_number) ? $phone_number : ''; ?>">
    <input type="hidden" name="id_sessions" value="<?php echo $id_sessions; ?>">

    <input type="hidden" name="id_booking" value="<?php echo $id_booking; ?>">

    <input type="hidden" name="id_users" value="<?php echo $id_users; ?>">

    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">


    <button type="submit">Confirm Booking</button>
</form>

</div>

<?php include('./includes/footer.php'); ?>


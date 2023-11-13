<?php
include('dbcon.php');

if (isset($_GET['id_payment'])) {
    $id_payment = $_GET['id_payment'];

    // Check if the payment exists and its current status is 'Pending'
    $payment_check_query = "SELECT payment_status FROM payment WHERE id_payment = $id_payment";
    $payment_check_result = mysqli_query($con, $payment_check_query);

    if ($payment_check_result && mysqli_num_rows($payment_check_result) > 0) {
        $row = mysqli_fetch_assoc($payment_check_result);
        $currentStatus = $row['payment_status'];

        if ($currentStatus === 'Pending' || $currentStatus === 'Approved') {
            // Update the payment status to 'Declined'
            $update_status_query = "UPDATE payment SET payment_status = 'Declined' WHERE id_payment = $id_payment";
            $update_status_result = mysqli_query($con, $update_status_query);

            if ($update_status_result) {
                echo '<script>';
                echo 'alert("Payment status is update to declined successfully");';
                echo 'history.go(-1);';
                echo '</script>';
            } else {
                echo "Error updating payment status: " . mysqli_error($con);
            }
        } else {
            echo '<script>';
                echo 'alert("Payment status is is alredy edit");';
                echo 'history.go(-1);';
                echo '</script>';
        }
    } else {
        echo "Payment not found.";
    }
} else {
    echo "Invalid request.";
}
?>

<?php
session_start();

$main_css_file = "views/paymentdetailstyles.css";
$navbar_css_file = "views/navbar.css";

include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');

if (isset($_GET['id_session'])) {
    $id_session = $_GET['id_session'];

    // Retrieve payment details for the selected session
    $payment_query = "SELECT p.id_payment, p.payment_amount, p.payment_status, p.payment_date, p.image_id, p.expiration_date
                  FROM payment p
                  LEFT JOIN images i ON p.image_id = i.image_id
                  WHERE p.id_sessions = $id_session";

    $payment_result = mysqli_query($con, $payment_query);

    echo "<div class='payment-container'>"; // Adding a container div for layout

    if ($payment_result && mysqli_num_rows($payment_result) > 0) {
        echo "<h3 style='margin: 2rem;'>Payment Information for this Session</h3>";
        echo "<div class='table-container'>"; // Adding a container div for the table
        echo "<table border='1'>";
        echo "<tr><th>ID Payment</th><th>Payment Amount</th><th>Payment Status</th><th>Payment Date</th><th>Expiration Date</th><th>Payment Proof</th><th></th><th></th></tr>";

        while ($payment_row = mysqli_fetch_assoc($payment_result)) {
            echo "<tr>";
            echo "<td>" . $payment_row['id_payment'] . "</td>";
            echo "<td>" . $payment_row['payment_amount'] . "</td>";
            echo "<td>" . $payment_row['payment_status'] . "</td>";
            echo "<td>" . $payment_row['payment_date'] . "</td>";
            echo "<td>" . $payment_row['expiration_date'] . "</td>";
            echo "<td><a style='color: #AE15EE;' href='view_image.php?id_payment=" . $payment_row['id_payment'] . "'>View Payment Proof</a></td>";
            echo "<td><a class='approved-btn' href='approved.php?id_payment=" . $payment_row['id_payment'] . "'>Approved</a></td>";
            echo "<td><a class='declined-btn' href='declined.php?id_payment=" . $payment_row['id_payment'] . "'>Declined</a></td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>"; // Closing the table container div
    } else {
        echo "No payment information found for this session.";
    }

    echo "</div>"; // Closing the payment container div
} else {
    echo "Invalid request. Session ID not provided.";
}

// Close the database connection
mysqli_close($con);

include('./includes/footer.php');
?>
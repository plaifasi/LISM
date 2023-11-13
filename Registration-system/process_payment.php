<?php
date_default_timezone_set('Asia/Bangkok');
include('./authentication.php');
$main_css_file = "views/payment.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_SESSION['id_users'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;

        if ($image && is_uploaded_file($image['tmp_name'])) {
            $image_data = file_get_contents($image['tmp_name']);

            // Simulate payment confirmation (you should replace this with your payment verification logic)
            $payment_confirmed = true;

            if ($payment_confirmed) {
                $id_users = isset($_POST['id_users']) ? $_POST['id_users'] : '';
                $id_sessions = $_POST['id_sessions'];
                $id_booking = $_POST['id_booking'];
                $total_price = $_POST['total_price'];
                $payment_status = 'Pending';

                // Retrieve the booking date, seats_for_adult, and seats_for_child from the bookings table
                $booking_data_query = "SELECT booking_date, seats_for_adult, seats_for_child FROM bookings WHERE id_booking = ?";
                $stmt_booking_data = mysqli_prepare($con, $booking_data_query);
                mysqli_stmt_bind_param($stmt_booking_data, "i", $id_booking);

                if (mysqli_stmt_execute($stmt_booking_data)) {
                    $result_booking_data = mysqli_stmt_get_result($stmt_booking_data);

                    if (mysqli_num_rows($result_booking_data) > 0) {
                        $booking_data = mysqli_fetch_assoc($result_booking_data);
                        $issue_date = $booking_data['booking_date'];
                        $seats_for_adult = $booking_data['seats_for_adult'];
                        $seats_for_child = $booking_data['seats_for_child'];

                        $payment_date = date('Y-m-d H:i:s');
                        // Calculate the expiration date (3 hours from the booking date)
                        $expiration_date = date('Y-m-d H:i:s', strtotime($issue_date . ' + 3 hours'));

    

                        if ($image && is_uploaded_file($image['tmp_name'])) {
                            $imageData = file_get_contents($image['tmp_name']);
                        
                            $insert_image_query = "INSERT INTO images (image_data) VALUES (?)";
                            $stmt_image = mysqli_prepare($con, $insert_image_query);
                        
                            if ($stmt_image) {
                                mysqli_stmt_bind_param($stmt_image, "s", $imageData);
                                if (mysqli_stmt_execute($stmt_image)) {
                                    $image_id = mysqli_insert_id($con);
                        
                                    // SQL INSERT statement with payment_proof as LONGBLOB
                                    $insert_query = "INSERT INTO payment (id_sessions, id_users, payment_amount, payment_status, issue_date, expiration_date, image_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                    $stmt_insert = mysqli_prepare($con, $insert_query);
                        
                                    if ($stmt_insert) {
                                        mysqli_stmt_bind_param($stmt_insert, "iiisssi", $id_sessions, $id_users, $total_price, $payment_status, $issue_date, $expiration_date, $image_id);
                        
                                        if (mysqli_stmt_execute($stmt_insert)) {
                                            // Close the statement before calling the email function
                                            mysqli_stmt_close($stmt_insert);
                        
                                            // Store data in the booking_history table
                                            $insert_booking_history_query = "INSERT INTO booking_history (id_users, id_sessions, booking_date, seats_for_adult, seats_for_child) VALUES (?, ?, ?, ?, ?)";
                                            $stmt_insert_booking_history = mysqli_prepare($con, $insert_booking_history_query);
                        
                                            if ($stmt_insert_booking_history) {
                                                mysqli_stmt_bind_param($stmt_insert_booking_history, "iisii", $id_users, $id_sessions, $issue_date, $seats_for_adult, $seats_for_child);
                        
                                                if (mysqli_stmt_execute($stmt_insert_booking_history)) {
                                                    // Call the email function here
                                                    sendemail_verify($email, $id_booking, $total_price, $issue_date, $expiration_date);
                                                    header('location: compleate.php');
                                                    exit;
                                                } else {
                                                    echo 'Error inserting data into booking_history: ' . mysqli_error($con);
                                                }
                        
                                                mysqli_stmt_close($stmt_insert_booking_history);
                                            } else {
                                                echo 'Error preparing statement for booking_history data insertion: ' . mysqli_error($con);
                                            }
                                        } else {
                                            echo 'Error inserting payment data: ' . mysqli_error($con);
                                        }
                                    } else {
                                        echo 'Error preparing statement for payment data insertion: ' . mysqli_error($con);
                                    }
                                } else {
                                    echo 'Error inserting image data: ' . mysqli_error($con);
                                }
                            } else {
                                echo 'Error preparing statement for image data insertion: ' . mysqli_error($con);
                            }
                        } else {
                            echo 'Invalid image file upload.';
                        }
                    } else {
                        echo 'Error retrieving booking date, seats_for_adult, and seats_for_child.';
                    }

                    mysqli_stmt_close($stmt_booking_data);
                } else {
                    echo 'Error retrieving booking date, seats_for_adult, and seats_for_child: ' . mysqli_error($con);
                }
            } else {
                echo 'Payment could not be confirmed. Please try again later.';
            }
        } else {
            echo 'Invalid file upload.';
        }
    } else {
        echo 'Invalid request method.';
    }
} else {
    echo 'id_user is not set in the session';
}

function sendemail_verify($email, $id_booking, $total_price, $seats_for_adult,$seats_for_child) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'superpray.ps@gmail.com'; // SMTP username
        $mail->Password = 'xssp fhuq zeqo rfcw'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable implicit TLS encryption
        $mail->Port = 465; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // Recipients
        $mail->setFrom('superpray.ps@gmail.com', 'LISM');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Thank you for booking with us';

        $email_template = "
            <h2>You have registered with LISM.</h2>
            <h5>Here is your booking information:</h5>
            <ul>
                <li>Booking ID: $id_booking</li>
                <li>Total Price: $total_price</li>
                <li>Issue Date: $seats_for_adult</li>
                <li>Expiration Date: $seats_for_child</li>
            </ul>
            <p>Thank you for booking with us.</p>
        ";

        $mail->Body = $email_template;
        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Close the database connection
mysqli_close($con);
?>
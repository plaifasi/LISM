<?php
session_start();
include('dbcon.php');


//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($email,$verify_token)
{
    $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'superpray.ps@gmail.com';                     //SMTP username
    $mail->Password   = 'xssp fhuq zeqo rfcw';                  //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('superpray.ps@gmail.com', 'LISM');
    $mail->addAddress($email);     //Add a recipient
 
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Welcome to LISM';

    $email_template = <<<HTML
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Email 📩</title>
        <link rel="stylesheet" href="views/email.css" />
    </head>
    <body>
        <div class="logo">
            
        </div>
        <div class="page" >
            <h1 class="topic">WELCOME TO LISM.</h1>
            <div class="box">
                
            </div>
            <div class="box2">
                <p>Thanks for registering an account with LISM! You're our special person.<br>
                Before we get started, we'll need to verify your email.</p><br>
                <a href='http://localhost/Registration-system/verify-email.php?token=$verify_token'> Click me </a>
            </div>
        </div>
    </body>
    </html>
HTML;

$mail->Body = $email_template;
$mail->send();

    $mail->Body = $email_template;
    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}

if (isset($_POST['register_btn'])) {
    $email = $_POST['email'];
    $verify_token = md5(rand());
    $status = "";

    // Check if the email already exists in the 'users' table
    $check_email_query_users = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $check_email_query_run_users = mysqli_query($con, $check_email_query_users);

    // Check if the email already exists in the 'companyusers' table
    $check_email_query_companyusers = "SELECT email_company FROM companyusers WHERE email_company = '$email' LIMIT 1";
    $check_email_query_run_companyusers = mysqli_query($con, $check_email_query_companyusers);

    if (mysqli_num_rows($check_email_query_run_users) > 0) {
        $status = "Email ID already exists in the 'users' table";
    } elseif (mysqli_num_rows($check_email_query_run_companyusers) > 0) {
        $status = "Email ID already exists in the 'companyusers' table";
    }

    if (!empty($status)) {
        $_SESSION['status'] = $status;
        header("Location: register.php");
        exit; // Exit to prevent further execution
    }

    // Set the role to "general"
    $role = "general";

    // Insert User/Register User Data with role
    $query = "INSERT INTO users (email, verify_token, role) VALUES ('$email', '$verify_token', '$role')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        sendemail_verify($email, $verify_token);
        $_SESSION['status'] = "Registration Successful. Please verify your email";
        header("Location: register.php");
    } else {
        $_SESSION['status'] = "Registration Failed";
        header("Location: register.php");
    }
}



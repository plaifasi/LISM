<?php
session_start();
include('dbcon.php');

if (isset($_POST['becomepartner_now_btn'])) {
    $companyName = $_POST['company_name'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $licenseNumber = $_POST['license_number'];
    $birthdate = $_POST['date_of_issue'];
    $phoneNumber = $_POST['phone_number'];
    $email = $_POST['email_company'];
    $website = $_POST['website_company'];
    $status = 'Pending';  // Set the status to 'Pending' for new registrations
    $role = 'company';  // Set to 'company'

    // Check if the email exists in the companyusers table
    $checkEmailQuery = "SELECT email_company FROM companyusers WHERE email_company = '$email'";
    $checkEmailResult = mysqli_query($con, $checkEmailQuery);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        // The email already exists in the companyusers table, display an error message
        echo '<script>';
            echo 'alert("Registration failed: This email is already registered in the companyusers table.");';
            echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
            echo '</script>';
    } else {
        // Perform company user registration
        $insertUserQuery = "INSERT INTO companyusers (company_name, first_name, last_name, license_number, date_of_issue, phone_number, email_company, website_company, status, role) VALUES ('$companyName', '$firstName', '$lastName', '$licenseNumber', '$birthdate', '$phoneNumber', '$email', '$website', '$status', '$role')";
        $result = mysqli_query($con, $insertUserQuery);

        if ($result) {
            echo 'Registration was successful.Please login';
            header("Location: login.php");
            exit();
        } else {
            // Registration failed for some reason
            echo "Registration failed: " . mysqli_error($con);
        }
    }
}
?>

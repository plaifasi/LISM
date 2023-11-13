<?php
session_start();
include('dbcon.php');

if (isset($_POST['login_now_btn'])) {
    if (!empty(trim($_POST['email']))) {
        $email = mysqli_real_escape_string($con, $_POST['email']);

        // Check if the user is registered in the users table
        $users_login_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $users_login_query_run = mysqli_query($con, $users_login_query);

        // Check if the user is registered in the companyusers table
        $company_users_login_query = "SELECT * FROM companyusers WHERE email_company = '$email' LIMIT 1";
        $company_users_login_query_run = mysqli_query($con, $company_users_login_query);

        if (mysqli_num_rows($users_login_query_run) > 0) {
            $row = mysqli_fetch_array($users_login_query_run);
            if ($row['verify_status'] == "1") {
                // Regular user is logged in
                $_SESSION['id_users'] = $row['id_users']; // Store the user ID
                $_SESSION['role'] = $row['role']; // Store the user role
                $_SESSION['authenticated'] = true;
            
                if ($row['role'] === 'general') {
                    // Redirect the regular user to their dashboard
                    $_SESSION['status'] = "You are logged in successfully as a regular user.";
                    header("Location: index.php");
                    exit(0);
                } else {
                    // Handle other roles or show an error message
                    $_SESSION['status'] = "Invalid role or user type.";
                    header("Location: login.php");
                    exit(0);
                }
            } else {
                // If verify_status is not 1, check if the user is registered
                if (mysqli_num_rows($users_login_query_run) > 0) {
                    // User is registered but not verified
                    $_SESSION['status'] = "Please verify your email.";
                    header("Location: login.php");
                    exit(0);
                } else {
                    // User is not registered, redirect to register.php
                    header("Location: register.php");
                    exit(0);
                }
            }

        } elseif (mysqli_num_rows($company_users_login_query_run) > 0) {
            $row = mysqli_fetch_array($company_users_login_query_run);
            $status = $row['status'];
            if ($row['role'] === 'company') {
                if ($status === "Active") {
                    // Company user is logged in
                    $_SESSION['id_company'] = $row['id_company']; // Store the user ID
                    $_SESSION['role'] = $row['role']; // Store the user role
                    $_SESSION['authenticated'] = TRUE;
                    $_SESSION['auth_user'] = [
                        'email_company' => $row['email_company']
                    ];
                    $_SESSION['status'] = "You are logged in successfully";
                    header("Location: ../Create-Package/main.php");
                    exit(0);
                } elseif ($status === "Pending") {
                    $_SESSION['status'] = "Your account is pending admin approval";
                    header("Location: login.php");
                    exit(0);
                } elseif ($status === "Inactive") {
                    $_SESSION['status'] = "Your account is currently inactive";
                    header("Location: login.php");
                    exit(0);
                } else {
                    // Handle unexpected or unhandled status
                    $_SESSION['status'] = "Unexpected status: $status"; // You can customize this message
                    header("Location: login.php");
                    exit(0);
                }
            } else {
                // Handle unexpected or unhandled role
                $_SESSION['status'] = "Unexpected role: " . $row['role']; // You can customize this message
                header("Location: login.php");
                exit(0);
            }
        }
    } else {
        $_SESSION['status'] = "Invalid email or you are not a registered user.";
        header("Location: login.php");
        exit(0);
    }
}
?>

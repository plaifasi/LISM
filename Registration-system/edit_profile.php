<?php
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('authentication.php');
include('dbcon.php');

// Correct the table name and column names
$select_user_query = "SELECT email, phone_number FROM users WHERE id_users = " . $_SESSION['id_users'];
$result = mysqli_query($con, $select_user_query);

if (isset($_SESSION['id_users'])) {
    $id_users = $_SESSION['id_users'];
    var_dump($id_users);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        $phone_number = $row['phone_number'];
    }

    // Handle form submission to update phone number
    if (isset($_POST['submit'])) {
        $newPhone = $_POST['phone_number'];
        $id_users = $_POST['id_users'];
    
        echo "New Phone: $newPhone<br>";
        echo "User ID: $id_users<br>";
    
        $update_phone_query = "UPDATE users SET phone_number = ? WHERE id_users = ?";
        $stmt = mysqli_prepare($con, $update_phone_query);
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $newPhone, $id_users);
    
            if (mysqli_stmt_execute($stmt)) {
                echo "Phone number updated successfully<br>";
                header("Location: myprofile.php");
                exit();
            } else {
                echo "Error updating phone number: " . mysqli_error($con) . "<br>";
            }
    
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($con) . "<br>";
        }
    }
}

mysqli_close($con);
?>

<?php include('./includes/footer.php'); ?>

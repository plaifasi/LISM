<?php
session_start();
$main_css_file = "views/myprofile.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');

include('dbcon.php');

// Correct the table name and column names
$select_user_query = "SELECT email, phone_number FROM users";
$result = mysqli_query($con, $select_user_query);
?>
<div class="myprofile-container">
    <div class="myprofile-box">
        <?php
        if (isset($_SESSION['id_users'])) {
            $id_users = $_SESSION['id_users'];

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $email = $row['email'];
                    $phone_number = $row['phone_number'];

                    echo '<div class="detail-user-box">';
                    echo '<h1>Your Email:</h1>';
                    echo '<p>' . $email . '</p>';
                    echo '<h1>Your phone number:</h1>';
                    echo '<p>' . $phone_number . '</p>';
                    echo '</div>';
                }
            }
        }
        ?>
        <div class="edit-phone-form">
            <h3>Edit Phone Number</h3>
            <form class="edit_profile" action='edit_profile.php' method="post">
                <input type="hidden" name="id_users" value="<?php echo $id_users; ?>">
                <label for="phone_number">New Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo $phone_number; ?>">
                <input type="submit" name="submit" value="Update">
            </form>
        </div>
    </div>
</div>
<?php mysqli_close($con); ?>

<?php include('./includes/footer.php'); ?>


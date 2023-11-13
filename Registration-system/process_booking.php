<?php
include('./authentication.php');
date_default_timezone_set('Asia/Bangkok');

$main_css_file = "views/payment.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');
require_once("lib/PromptPayQR.php");
?>

<div class="payment-container">

<?php
$PromptPayQR = new PromptPayQR(); // new object
$PromptPayQR->size = 8; // Set QR code size to 8
$PromptPayQR->id = '0642808196'; // PromptPay ID

if (isset($_POST['total_price']) && isset($_POST['id_booking'])) {
    $total_price = $_POST['total_price'];
    $id_booking = $_SESSION['id_booking'];
    $PromptPayQR->amount = $total_price;
} else {
    $total_price = 0; // Initialize to a default value or any appropriate value
    echo "Handle the case where total_price is not set.";
}
echo '<img src="' . $PromptPayQR->generate() . '" />';
?>


<?php
$main_css_file = "views/sighinstyles.css";
include('./includes/header.php');

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
}
if (isset($_SESSION['id_users'])) {
// Check if the session variables exist and display them
if (isset($_SESSION['session'], $_SESSION['seats_for_adult'], $_SESSION['seats_for_child'])) {
    $session = $_SESSION['session'];
    $seats_for_adult = $_SESSION['seats_for_adult'];
    $seats_for_child = $_SESSION['seats_for_child'];

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
            echo "<div class='summery'>";
            
            echo "<br> Start Date: " . $start_date . "<br>";
            echo "End Date: " . $end_date . "<br>";
            echo "Seats for Adults: " . $seats_for_adult . "<br>";
            echo "Seats for Children: " . $seats_for_child . "<br>";
            echo "<h3 style='color:#AE15EE;'>Total Price: " . number_format($total_price, 2) . "฿</h3><br>";
            echo "</div>";

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

<div class="form-payment">
<form action="process_payment.php" method="post" enctype="multipart/form-data">
    <label for="image">Upload Payment Photo:</label>
    <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png"><br>
    <input type="hidden" name="first_name" value="<?php echo $first_name; ?>">
    <input type="hidden" name="last_name" value="<?php echo $last_name; ?>">
    <input type="hidden" name="email" value="<?php echo $email; ?>">
    <input type="hidden" name="phone_number" value="<?php echo $phone_number; ?>">
    <input type="hidden" name="id_sessions" value="<?php echo $id_sessions; ?>">
    <input type="hidden" name="id_users" value="<?php echo $_SESSION['id_users']; ?>">
    <input type="hidden" name="id_booking" value="<?php echo $id_booking; ?>">
    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
    
    <button type="submit">Upload Payment Proof</button>
</form>
</div>
</div>
<?php include('./includes/footer.php'); ?>
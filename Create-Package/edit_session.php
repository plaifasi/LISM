<?php
// Include your database connection code here
session_start();
include('dbcon.php');
$main_css_file = "views/editsessionstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');

if (isset($_GET['id_session']) && isset($_GET['id_package'])){
    $id_package = $_GET['id_package'];
    $id_session = $_GET['id_session']; // Get the session ID from the URL

    // Check if the session exists
    $session_query = "SELECT * FROM sessions WHERE id_sessions = $id_session";
    $session_result = mysqli_query($con, $session_query);

    if ($session_result && mysqli_num_rows($session_result) > 0) {
        $session_data = mysqli_fetch_assoc($session_result);

        // Display a form to edit the session
        echo '<form method="post" action="update_session.php" class="form-container">';
        echo '<input type="hidden" name="id_package" value="' . $id_package . '">';
        echo '<input type="hidden" name="id_session" value="' . $id_session . '">';
        echo '<label for="session_start_date">Start Date:</label>';
        echo '<input type="date" name="session_start_date" value="' . $session_data['session_start_date'] . '"><br>';
        echo '<label for="session_end_date">End Date:</label>';
        echo '<input type="date" name="session_end_date" value="' . $session_data['session_end_date'] . '"><br>';
        echo '<label for="available_spots_adult">Available Spots (Adult):</label>';
        echo '<input type="number" name="available_spots_adult" value="' . $session_data['available_spots_adult'] . '"><br>';
        echo '<label for="available_spots_child">Available Spots (Child):</label>';
        echo '<input type="number" name="available_spots_child" value="' . $session_data['available_spots_child'] . '"><br>';
        echo '<label for="price_package_adult">Price (Adult):</label>';
        echo '<input type="number" name="price_package_adult" value="' . $session_data['price_package_adult'] . '"><br>';
        echo '<label for="price_package_child">Price (Child):</label>';
        echo '<input type="number" name="price_package_child" value="' . $session_data['price_package_child'] . '"><br>';
        echo '<input type="submit" name="update" value="Update Session">';
        echo '</form>';
        
    } else {
        echo "Session not found.";
    }
} else {
    echo "Invalid session request.";
}

// Close the database connection
mysqli_close($con);
?>
<?php
include('./includes/footer.php'); ?>
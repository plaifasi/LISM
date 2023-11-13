<?php
session_start();
$main_css_file = "views/addsessionstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');

include('dbcon.php');

if (isset($_GET['id_package'])) {
    $id_package = $_GET['id_package']; // Get the id_package from the URL

    if (isset($_POST['add_session'])) {
        $session_start_date = $_POST['session_start_date'];
        $session_end_date = $_POST['session_end_date'];
        $available_spots_adult = $_POST['available_spots_adult'];
        $available_spots_child = $_POST['available_spots_child'];
        $price_package_adult = $_POST['price_package_adult'];
        $price_package_child = $_POST['price_package_child'];

        // Insert the new session into the 'sessions' table
        $insert_session_query = "INSERT INTO sessions (id_package, session_start_date, session_end_date, available_spots_adult, available_spots_child, price_package_adult, price_package_child) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_session = mysqli_prepare($con, $insert_session_query);

        if ($stmt_session) {
            mysqli_stmt_bind_param($stmt_session, 'isssiii', $id_package, $session_start_date, $session_end_date, $available_spots_adult, $available_spots_child, $price_package_adult, $price_package_child);
            if (mysqli_stmt_execute($stmt_session)) {
                header("Location: edit.php?id_package=$id_package");
                exit;
            } else {
                echo "Error adding session: " . mysqli_error($con);
            }
        } else {
            echo "Error preparing the session insert statement: " . mysqli_error($con);
        }
    }
?>


    
    <form method="post" action="" class="form-container">
        <input type="hidden" name="id_package" value="<?php echo $id_package; ?>">
        <label for="session_start_date">Start Date:</label>
        <input type="date" name="session_start_date" required><br>
        <label for="session_end_date">End Date:</label>
        <input type="date" name="session_end_date" required><br>
        <label for="available_spots_adult">Available Spots (Adult):</label>
        <input type="number" name="available_spots_adult" required><br>
        <label for="available_spots_child">Available Spots (Child):</label>
        <input type="number" name="available_spots_child" required><br>
        <label for="price_package_adult">Price (Adult):</label>
        <input type="number" name="price_package_adult" required><br>
        <label for="price_package_child">Price (Child):</label>
        <input type="number" name="price_package_child" required><br>
        <input type="submit" name="add_session" value="Add Session">
    </form>


<?php
} else {
    echo "Invalid package request.";
}

// Close the database connection
mysqli_close($con);
?>
<?php
include('./includes/footer.php'); ?>
<?php
include('./authentication.php');

$main_css_file = "views/booking.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');
if (isset($_SESSION['id_users'])) {
    $id_users = $_SESSION['id_users'];

    if (isset($_GET['id_package'])) {
        $id_package = $_GET['id_package'];

        // Include your database connection
        include('dbcon.php');

        // Query to fetch package details with image data
        $details_query = "SELECT p.*, i.image_data FROM package p
            LEFT JOIN images i ON p.image_id = i.image_id
            WHERE id_package = $id_package";
        $details_result = mysqli_query($con, $details_query);

        if ($details_result && mysqli_num_rows($details_result) > 0) {
            $row = mysqli_fetch_assoc($details_result);

            

            // Display the package details, including image and file data
            
            echo '<div class="package-container">';
            if (!empty($row['image_data'])) {
                            $base64Image = base64_encode($row['image_data']);
                            echo "<div class='detail-img'>";
                            echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Package Image">';
                            echo "</div>";
                        } else {
                            echo 'No image available.<br>';
                        }

            echo '<div class="package">';
            echo '<div class="package-detail"><h2>' . $row['title_package'] . '</h2></div>';
            echo '<div class="package-detail"><i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Destination: ' . $row['destination_package'] . '</div>';
            echo '<div class="package-detail"><i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Hightlight: ' . $row['description_package'] . '</div>';
            echo '</div>';
            
            
            echo '</div>';
            // Booking form
            

        // Query to fetch sessions for the specified package
        $session_query = "SELECT id_sessions, session_start_date, session_end_date, price_package_adult, price_package_child FROM sessions s
                         JOIN package p ON s.id_package = p.id_package
                         WHERE s.id_package = $id_package";

        $session_result = mysqli_query($con, $session_query);

        if ($session_result && mysqli_num_rows($session_result) > 0) {
            echo '<form action="handle_booking.php" method="POST">';
            echo '<input type="hidden" name="id_users" value="' . $id_users . '">';
            echo '<input type="hidden" name="id_package" value="' . $id_package . '">';
            echo '<label for="session">Choose a Session:</label>';
            echo '<select name="session" id="session" required>';

            while ($session_row = mysqli_fetch_assoc($session_result)) {
                $id_sessions = $session_row['id_sessions'];
                $start_date = $session_row['session_start_date'];
                $end_date = $session_row['session_end_date'];
                $priceAdult = $session_row['price_package_adult'];
                $priceChild = $session_row['price_package_child'];

                // Display the session with start and end dates
                echo "<option value='$id_sessions' data-price-adult='$priceAdult' data-price-child='$priceChild'>Session: Start Date: $start_date, End Date: $end_date</option>";
            }

            echo '</select>';
            echo '<label for="seats_for_adult">Number of Seats for Adults to Book:</label>';
            echo '<input type="number" name="seats_for_adult" id="seats_for_adult" value="0" required min="0">';
            echo '<label for "seats_for_child">Number of Seats for Children to Book:</label>';
            echo '<input type="number" name="seats_for_child" id="seats_for_child" value="0" required min="0">';
            echo '<p id="total-price-adult">Total Price for Adults: 0.00฿</p>';
            echo '<p id="total-price-child">Total Price for Children: 0.00฿</p>';
            echo '<button type="submit">Book</button>';
            echo '</form>';
        } else {
            echo 'No sessions available for this package.';
        }

        mysqli_close($con); // Close the database connection
    } else {
        echo 'Invalid package request.';
    }
} else {
    header('Location: login.php');
    exit(0);
}
}
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sessionSelect = document.getElementById("session");
    const seatsForAdultInput = document.getElementById("seats_for_adult");
    const seatsForChildInput = document.getElementById("seats_for_child");
    const totalAdultPrice = document.getElementById("total-price-adult");
    const totalChildPrice = document.getElementById("total-price-child");

    // Update total prices when inputs change
    sessionSelect.addEventListener("change", updateTotals);
    seatsForAdultInput.addEventListener("input", updateTotals);
    seatsForChildInput.addEventListener("input", updateTotals);

    function updateTotals() {
        const selectedOption = sessionSelect.options[sessionSelect.selectedIndex];
        const priceAdult = parseFloat(selectedOption.getAttribute("data-price-adult"));
        const priceChild = parseFloat(selectedOption.getAttribute("data-price-child"));
        const seatsForAdult = parseInt(seatsForAdultInput.value);
        const seatsForChild = parseInt(seatsForChildInput.value);

        const totalAdult = priceAdult * seatsForAdult;
        const totalChild = priceChild * seatsForChild;

        totalAdultPrice.textContent = `Total Price for Adults: ฿${totalAdult.toFixed(2)}`;
        totalChildPrice.textContent = `Total Price for Children: ฿${totalChild.toFixed(2)}`;
    }
});
</script>
</body>
</html>

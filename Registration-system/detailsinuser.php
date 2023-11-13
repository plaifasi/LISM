
<?php
session_start();
$main_css_file = "views/detailstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');
 // Make sure to start the session

if (isset($_SESSION['id_users'])) {
    $id_users = $_SESSION['id_users'];

    if (isset($_GET['id_package'])) {
        $id_package = $_GET['id_package']; // Get the id_package of the selected item

        // Check if the user is authenticated

        // Retrieve the full data of the selected package from the 'package' table and join with 'images' table
        $details_query = "SELECT p.*, i.image_data, f.file_data FROM package p
        LEFT JOIN images i ON p.image_id = i.image_id
        LEFT JOIN files f ON p.file_id = f.file_id
        WHERE id_package = $id_package";

        $details_result = mysqli_query($con, $details_query);
?>

<section class="detail">
        <div class="detail-container">
            <div class="detail-box">
<?php
        if ($details_result && mysqli_num_rows($details_result) > 0) {
            $row = mysqli_fetch_assoc($details_result);

            

            // Display the full data, including the image
            echo "<div class='detail-title'>";
            echo '<div class="bookmark-box">';
            echo '<form action="bookmark.php" method="POST">';
            echo '<input type="hidden" name="id_users" value="' . $_SESSION['id_users'] . '">';
            echo '<input type="hidden" name="id_package" value="' . $id_package . '">';
            echo '<div class="icon-bookmark">';
            echo '<a href="bookmark.php?id_package=' . $id_package . '&id_users=' . $_SESSION['id_users'] . '"><i class="fa-regular fa-bookmark" style="color: #ae15ee;"></i></a>';
            echo '</div>';
            echo '</form>';
            echo '</div>';
            echo "<h2>" . $row['title_package'] . "</h2>";
            echo '</div>';
            echo "</div>";

            
    
            ;

            // Display the prices for adults and children
            

            // Display the image if available
            if (!empty($row['image_data'])) {
                $base64Image = base64_encode($row['image_data']);
                echo "<div class='detail-img'>";
                echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Package Image">';
                echo "</div>";
            } else {
                echo 'No image available.<br>';
            }

            echo '<div class="detail-destination">';
            echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Destination: ' . $row['destination_package'] . '<br>';
            echo '</div>';

            echo '<div class="detail-description">';
            echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Highlight: ' . $row['description_package'] . '<br>';
            echo '</div>';

            echo '<div class="detail-vehicle">';
            echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Vehicle: ' . $row['vehicle_package'] . '<br>';
            echo '</div>';

            echo '<div class="detail-hotel">';
            echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Hotel: ' . $row['hotel_package'] . '<br>';
            echo '</div>';
            // Display sessions associated with this package
            echo "<h2>Sessions</h2>";
            
            $sessions_query = "SELECT * FROM sessions WHERE id_package = $id_package";
            $sessions_result = mysqli_query($con, $sessions_query);

            if ($sessions_result && mysqli_num_rows($sessions_result) > 0) {
                echo '<div class="detail-scrollbar">';
                echo '<table class="custom-table">';
                echo '<tr><th>Start Date-End Date</th><th>Price for Adults</th><th>Price for Children</th></tr>';

                while ($session_row = mysqli_fetch_assoc($sessions_result)) {
                    echo '<tr>';
                    echo '<td>' . $session_row['session_start_date'] . ' - ' . $session_row['session_end_date'] . '</td>';

                    if (isset($session_row['price_package_adult']) && isset($session_row['price_package_child'])) {
                        echo '<td>$' . $session_row['price_package_adult'] . '</td>';
                        echo '<td>$' . $session_row['price_package_child'] . '</td>';
                        
                    } else {
                        echo '<td>No price information available.</td>';
                        echo '<td>No price information available.</td>';
                    }

                    echo '</tr>';
                }

                echo '</table>';
                echo '</div>';
            } else {
                echo "No sessions available for this package.";
            }

            echo "<h2>File package and condition</h2>";

            if (!empty($row['file_data'])) {
                $base64 = base64_encode($row['file_data']);
                echo '<div class="detail-file-package">';
                echo '<object data="data:application/pdf;base64,' . $base64 . '" type="application/pdf" width="100%" height="700"></object>';
                echo '</div>';
            } else {
                echo 'No file package available.';
            }

            
            // Add the 'id_package' as a hidden input in the booking form
            echo '<form class="form-bookig"action="booking.php" method="POST">';
            echo '<input type="hidden" name="id_users" value="' . $_SESSION['id_users'] . '">';
            echo '<input type="hidden" name="id_package" value="' . $id_package . '">';
            echo '<a class="btn-book" href="booking.php?id_package=' . $id_package . '">Book Now</a>';
            echo '</form>';

            // Add more fields to display other data
            

        } else {
            echo "Package not found.";
        }
    } else {
        echo "Invalid package request.";
    }
} else {
    echo '<script>';
            echo 'alert("User not authenticated.Please login");';
            echo 'history.go(-1);'; // Go back to the previous page if the user clicks OK
            echo '</script>';
    
}
?>
            </div>
        </div>
</section>

<?php include('./includes/footer.php'); ?>
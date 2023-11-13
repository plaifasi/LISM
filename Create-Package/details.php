<?php
session_start();
$main_css_file = "views/detailstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');

if (isset($_GET['id_package'])) {
    $id_package = $_GET['id_package']; // Get the id_package of the selected item

    // Retrieve the full data of the selected package from the 'package' table and join with 'images' table
    $details_query = "SELECT p.*, i.image_data, f.file_data FROM package p
    LEFT JOIN images i ON p.image_id = i.image_id
    LEFT JOIN files f ON p.file_id = f.file_id
    WHERE id_package = $id_package";

    $details_result = mysqli_query($con, $details_query);


        echo "<div class='bookmark-box'>";
        echo "<form action='statuspackage.php' method='POST'>";
        echo "<input type='hidden' name='id_package' value='$id_package'>";
        echo "<label for='status_package'>Choose status package:</label>";
        echo "<select name='status_package' id='status_package'>";
        echo "<option value='Pause'>Pause</option>";
        echo "<option value='Publish'>Publish</option>";
        echo "</select>";
        echo "<button type='submit' value='Submit'>Change</button>";
        echo "</form>";
        echo "</div>";
?>

    <section class="detail">
        <div class="detail-container">
            <div class="detail-box">
<?php
    if ($details_result && mysqli_num_rows($details_result) > 0) {
        $row = mysqli_fetch_assoc($details_result);

        // Display the full data, including the image
        
        

        echo "<div class='detail-title'>";
        echo "<h4>" . $row['title_package'] . "</h4>";
        echo '</div>';
        if (!empty($row['image_data'])) {
                    $base64Image = base64_encode($row['image_data']);
                    echo "<div class='detail-img'>";
                    echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Package Image"  ">';
                    echo "</div>";
                } else {
                    echo 'No image available.<br>';
                }

        echo '<div class="detail-destination">';
        echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Destination: ' . $row['destination_package'] . '<br>';
        echo '</div>';
        echo '<div class="detail-description">';
        echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Hightlight: ' . $row['description_package'] . '<br>';
        echo '</div>';
        echo '<div class="detail-vehicle">';
        echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Vehicle: ' . $row['vehicle_package'] . '<br>';
        echo '</div>';
        echo '<div class="detail-hotel">';
        echo '<i class="fa-solid fa-location-dot" style="color: #ae15ee;"></i>Hotrl: ' . $row['hotel_package'] . '<br>';
        echo '</div>';
        // Display the image if available
        
        
        echo "<h2>File package and condition</h2>";

        if (!empty($row['file_data'])) {
            $base64 = base64_encode($row['file_data']);
            echo '<div class="detail-file-package">';
            echo '<object data="data:application/pdf;base64,' . $base64 . '" type="application/pdf" width="100%" height="700"></object>';
            echo '</div>';
        } else {
            echo 'No file package available.';
        }

        // Display sessions associated with this package
        
        $sessions_query = "SELECT * FROM sessions WHERE id_package = $id_package";
        $sessions_result = mysqli_query($con, $sessions_query);

        if ($sessions_result && mysqli_num_rows($sessions_result) > 0) {
            echo '<h2>Sessions</h2>';
            echo '<a class="add-session-link" href="add_session.php?id_package=' . $id_package . '">Add Session</a>';
            echo '<div class="detail-scrollbar">';
            echo '<table border="1" class="custom-table">';
            echo '<tr><th>Start Date-End Date</th><th>Price Package Adult</th><th>Price Package Child</th><th>Available Spots Adult</th><th>Available Spots Child</th><th> </th></tr>';
        
            while ($session_row = mysqli_fetch_assoc($sessions_result)) {
                echo '<tr>';
                echo '<td>' . $session_row['session_start_date'] ."-". $session_row['session_end_date'] . '</td>';
                echo '<td>' . $session_row['price_package_adult'] . '</td>';
                echo '<td>' . $session_row['price_package_child'] . '</td>';
                echo '<td>' . $session_row['available_spots_adult'] . '</td>';
                echo '<td>' . $session_row['available_spots_child'] . '</td>';
                echo '<td>';
                echo '<a class="edit-session-link" href="edit_session.php?id_session=' . $session_row['id_sessions'] . '&id_package=' . $id_package . '">Edit</a>';
                echo '<a class="delete-session-link" href="payment_details.php?id_session=' . $session_row['id_sessions'] . '&id_package=' . $id_package . '">Payment</a>';
                echo '</td>';
                echo '</tr>';
            }
        
            echo '</table>';
            echo '</div>';
        } else {
            echo 'No sessions available for this package.';
        }
        

        
    
        echo '<div class="btn">';
        echo '<a class="edit-link" href="edit.php?id_package=' . $id_package . '">Edit</a>';
        echo '<a class="delete-link" href="delete.php?id_package=' . $id_package . '">Delete</a>';
        echo '</div>';
    } else {
        echo "Package not found.";
    }
} else {
    echo "Invalid package request.";
}

mysqli_close($con); // Close the database connection
?>

                </div>
            </div>
        </div>
    </section>
<?php
include('./includes/footer.php'); ?>
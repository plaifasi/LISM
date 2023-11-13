<?php
session_start();
$main_css_file = "views/accountsellerstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');

include('dbcon.php');

if (isset($_SESSION['id_users'])) {
    $userId = $_SESSION['id_users'];

    // Query to retrieve packages from user bookmarks
    $bookmark_query = "SELECT p.id_package, p.title_package, p.destination_package, p.description_package, i.image_data, pm.payment_status
FROM user_bookmarks AS ub
JOIN package AS p ON ub.id_package = p.id_package
LEFT JOIN images AS i ON p.image_id = i.image_id
LEFT JOIN payment AS pm ON pm.id_sessions = p.id_package AND pm.id_users = ?
WHERE ub.id_users = ?";
    $stmt = mysqli_prepare($con, $bookmark_query);
    ?>
    <section class="content">
    <div class="content-box">
        <div class="content-box-container">
            <div class="content-box-row">
    <?php                
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $userId, $userId);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            // Check if there are bookmarked packages
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_package = $row['id_package'];
                    $title = $row['title_package'];
                    $description = $row['description_package'];
                    $destination = $row['destination_package'];
                    $payment_status = $row['payment_status']; // Fetch payment_status
                    
                    
                    // Fetch the image data for this package
                    $image_data = $row['image_data'];
                            if (!empty($image_id)) {
                                $image_query = "SELECT image_data FROM images WHERE image_id = $image_id";
                                $image_result = mysqli_query($con, $image_query);
                
                                if ($image_result && mysqli_num_rows($image_result) > 0) {
                                    $image_row = mysqli_fetch_assoc($image_result);
                                    $image_data = $image_row['image_data'];
                                }
                            }
                    echo '<div class="package">';
                    echo '<div class="package-box">';
                    echo "<a href='detailsinuser.php?id_package=$id_package'>$title</a>";
        
                    if (!empty($image_data)) {
                        // Display the image if available
                        $base64Image = base64_encode($image_data);
                        echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Package Image" style="max-width: 100px; max-height: 100px;">';
                    }
        
                    echo "<p>$description</p>";
                    echo "<p>Destination: $destination</p>";
                    
                    
                    
                                        
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "This user doesn't bookmarked any package yet";
            }
        } else {
            echo "Error executing the bookmarked packages query: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement for bookmarked packages: " . mysqli_error($con);
    }
} else {
    echo 'id_users is not set in the session';
}

// Close the database connection
mysqli_close($con);
?>

</div>
</div>
</div>
</section>

<?php include('./includes/footer.php'); ?>

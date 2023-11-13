
<?php
$main_css_file = "views/displayinuserstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
session_start();
include('dbcon.php');
$select_packages_query = "SELECT p.id_package, p.title_package, p.image_id,p.destination_package, p.description_package, p.status_package, s.price_package_adult, s.price_package_child
FROM package p
INNER JOIN sessions s ON p.id_package = s.id_package";
$result = mysqli_query($con, $select_packages_query);

?>

<section class="content">
<div class="box-booking">
                <div class="container-box-booking" >
                <form class="form-box" method="post" action="search.php">
    <div class="icon-container">
        
        <div class="destination-box">
            <input type="text" class="destination" name="search_destination" placeholder="Enter your destination">
        </div>
    </div>
    <div class="checkinout-box">
        <input type="date" placeholder="Date start" name="session_start_date" class="checkin">
        <input type="date" placeholder="Date end" name="session_end_date" class="checkout">
    </div>
    <button type="submit" id="search-button">Search</button>
</form>
    <div class="content-box">
        <div class="content-box-container">
            <div class="content-box-row">
            
   
                </div>

                <?php
                if (isset($_GET['search_results'])) {
                    $search_results = unserialize(urldecode($_GET['search_results']));

                    if (!empty($search_results)) {
                        foreach ($search_results as $row) {
                            $id_package = $row['id_package'];
                            $title = $row['title_package'];
                            $destination = $row['destination_package'];
                            $description = $row['description_package'];
                            $image_id = $row['image_id'];
                            
                            // Fetch the image data for this package
                            $image_data = "";
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

                            if (!empty($image_data)) {
                                // Display the image if available
                                $base64Image = base64_encode($image_data);
                                echo '<div class="img-content">';
                                echo '<div class="card-img">';
                                echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Package Image" style="max-width: 350px; max-height: 350px;">';
                                echo '</div>';
                                echo '</div>';
                            }
                            

                            echo '<div class="card-content">';
                            echo '<h4>' . $title . '</h4>';
                            echo '<h5>destination to : ' . $destination . '</h5>';
                            echo '<p>' . $description . '</p>';

                            $sessions_query = "SELECT price_package_child FROM sessions WHERE id_package = $id_package";
                            $sessions_result = mysqli_query($con, $sessions_query);
                            
                            if ($sessions_result && mysqli_num_rows($sessions_result) > 0) {
                                $prices = array();
                                
                                while ($session_row = mysqli_fetch_assoc($sessions_result)) {
                                    $prices[] = $session_row['price_package_child'];
                                }
                            
                                if (!empty($prices)) {
                                    $min_price = min($prices);
                                    echo '<h3>Start Price at</h3>';
                                    echo '<p>' . $min_price . ' ฿</p>';
                                } else {
                                    echo '<p>No price information available.</p>';
                                }
                            } else {
                                echo '<p>No price information available.</p>';
                            }
                            
                            echo "<a href='detailsinuser.php?id_package=$id_package' class=\"create-btn\">More detail</a>";
                            echo '</div>';
                            echo '</div>';

                            // Display the status
                            echo '</div>';
                            echo '</div>';
                            }
                    } else {
                        echo "No search results to display.";
                    }
                    
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php include('./includes/footer.php');
?>

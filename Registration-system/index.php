<?php 
session_start();
$main_css_file = "views/styles.css";
$navbar_css_file= "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php') ;
include('dbcon.php');
$select_packages_query = "SELECT p.id_package, p.title_package, p.image_id,p.destination_package, p.description_package, p.status_package, s.price_package_adult, s.price_package_child
FROM package p
INNER JOIN sessions s ON p.id_package = s.id_package";

$result = mysqli_query($con, $select_packages_query);
?>
<section class="hero">
        <div class="container-hero">
            <div class="cover-hero">
                <div class="info">
                    <h1>ENJOY YOUR DREAM VACATION</h1>
                    <p>Explore more with our help to easy your plan.Your dream trip<br> will come too.Take a seat and relax</p>
                </div>
            </div>
            <div class="box-booking">
                <div class="container-box-booking" >
                <form class="form-box" method="post" action="search.php">
    <div class="icon-container">
        <span class="icon"><i class="fa-sharp fa-solid fa-location-dot" style="color: #ae15ee;"></i></span>
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
   
                </div>             
            </div> 
            
        </div>
    </section>

    <section class="Popular">
    <div class="card-container">
        <div class="card-head">
            <h2></h2>
        </div>
        <div class="wrapper">
            <i id="left" class="fa-solid fa-angle-left"></i>
            <ul class="carousel">
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_package = $row['id_package'];
                        $title = $row['title_package'];
                        $description = $row['description_package'];
                        $image_id = $row['image_id'];
                        $destination = $row['destination_package'];

                        $image_data = "";
                        if (!empty($image_id)) {
                            $image_query = "SELECT image_data FROM images WHERE image_id = $image_id";
                            $image_result = mysqli_query($con, $image_query);

                            if ($image_result && mysqli_num_rows($image_result) > 0) {
                                $image_row = mysqli_fetch_assoc($image_result);
                                $image_data = $image_row['image_data'];
                            }
                        }

                        echo '<li class="card">';
                        if (!empty($image_data)) {
                            // Display the image if available
                            $base64Image = base64_encode($image_data);
                            echo '<div class="img-content">';
                            echo '<div class="card-img">';
                            echo '<img src="data:image/jpeg;base64,' . $base64Image . '" class="card-img">';
                            echo '</div>';
                        }
                        echo '<div class="card-content">';
                        echo '<h2>' . $title . '</h2>';
                        echo '<p>' . $description . '</p>';
                        echo '<h5>destination to : ' . $destination . '</h5>';
                        $sessions_query = "SELECT MIN(price_package_adult) AS min_price FROM sessions WHERE id_package = $id_package AND price_package_adult > 0";
                        $sessions_result = mysqli_query($con, $sessions_query);

                        if ($sessions_result && mysqli_num_rows($sessions_result) > 0) {
                            $session_row = mysqli_fetch_assoc($sessions_result);
                            $min_price = $session_row['min_price'];
                            echo '<h3>Start price at</h3>';
                            echo '<p>' . $min_price . ' ฿</p>';
                        } else {
                            echo '<p>No price information available.</p>';
                        }
                        echo '</div>';
                        echo '<div class="btn-card">';
                        echo "<a href='detailsinuser.php?id_package=$id_package' class=\"btn-card\">More detail</a>";
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo "No search results to display.";
                }
                ?>
            </ul>
            <i id="right" class="fa-solid fa-angle-right"></i>
        </div>
    </div>
</section>

    <!--Footer-->
    <section class="footer">
            <div class="footer-container">
                <div class="info-footer">
                    <div class="info-footer-row">
                        <div class="info-footer-column-f">
                            <div class="logo"> <a href="">LI</a><a id="second"href="">SM.</a></div>
                            <div class="footer-des"><P>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </P></div>
                            <div class="footer-slogan"><h4>“ENJOY YOUR DREAM VACATION”</h4></div>
                        </div>
                        <div class="info-footer-column-b">
                            <div class="footer-head-contact">
                                <h3>Contact Us</h3>
                            </div>
                            <div class="footer-contact-info">
                                <P><b>E-mail:</b>packsgeseeking@gamil.com</P>
                                <P><b>Phone number:</b>+66 99 173 6399</P>
                            </div>
                            <div class="footer-contact-address">
                                <P><b>Address:</b>No. 1 Moo 6 kamphaeng saen Kasetsart University Nakhon pathom 73140</P>
                            </div>
                        </div>
                        <div class="info-footer-column-l">
                            <div class="footer-head-aboutus">
                                <h3>About Us</h3>
                            </div>
                            <div class="footer-aboutus-link">
                                <a href="">BECOME PRATNER</a><br>
                                <a href="">HOME</a>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="copyright-footer">
                        <h5>Copyright 2023 @ packageseeking.CO.Ltd</h5>
                </div>
            </div>
        </section>
        <!--End Footer-->
<?php 
$main_scripts_file ="script/scripts.js";
include('./includes/footer.php'); ?>

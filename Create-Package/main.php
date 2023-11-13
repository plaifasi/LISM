<?php

$main_css_file = "views/accountsellerstyles.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');
include('authenticationn.php');

$select_packages_query = "SELECT id_package, title_package, image_id, description_package, status_package FROM package";
$result = mysqli_query($con, $select_packages_query);
?>



<section class="content">
    <div class="content-box">
        <div class="content-box-container">
            
                <div class="content-box-split" >
                    <div class="package">
                        <div class="package-box">
                            <div class="icon-head">
                                <i class="fa-solid fa-shop" style="color: #C5C5C5;"></i>
                            </div>
                            <div class="create-btn"><a class="create_new_btn" href="create.php">Create Package</a></div>
                        </div>
                    </div>
                </div>
                <div class="content-box-row">
                <?php
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id_package = $row['id_package'];
        $title = $row['title_package'];
        $description = $row['description_package'];
        $image_id = $row['image_id'];
        $status_package = $row['status_package']; // Get the status

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
        echo '<div class="status-label">' . $status_package . '</div>';

        if (!empty($image_data)) {
            // Display the image if available
            $base64Image = base64_encode($image_data);
            echo '<div class="img-content">';
            echo '<div class="card-img">';
            echo '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Package Image" class="card-img">';
            echo '</div>';
            echo '</div>';
        }

        echo '<div class="card-content">';
        echo '<h4>'. $title . '</h4>';

        echo '<p>'. $description . '</p>';
        echo '</div>';
        echo '<div class="btn-card">';
        echo "<a href='details.php?id_package=$id_package' class='btn-card'>more detail</a>";
        echo '</div>';

        echo '</div>';
        
    }
}
?>
            </div>
        </div>
    </div>       
</section>
<!--Footer-->
<section class="footer">
    <div class="footer-container">
        <div class="info-footer">
            <div class="info-footer-row">
                <div class="info-footer-column-f">
                    <div class="logo"> <a href="">LI</a><a id="second" href="">SM.</a></div>
                    <div class="footer-des">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
                    </div>
                    <div class="footer-slogan"><h4>“ENJOY YOUR DREAM VACATION”</h4></div>
                </div>
                <div class="info-footer-column-b">
                    <div class "footer-head-contact">
                        <h3>Contact Us</h3>
                    </div>
                    <div class="footer-contact-info">
                        <p><b>E-mail:</b>packsgeseeking@gamil.com</p>
                        <p><b>Phone number:</b>+66 99 173 6399</p>
                    </div>
                    <div class="footer-contact-address">
                        <p><b>Address:</b>No. 1 Moo 6 kamphaeng saen Kasetsart University Nakhon pathom 73140</p>
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

<?php include('./includes/footer.php'); ?>

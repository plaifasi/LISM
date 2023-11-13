<?php
include('./authentication.php');

$main_css_file = "views/compleate.css";
$navbar_css_file = "views/navbar.css";
include('./includes/header.php');
include('./includes/navbar.php');
include('dbcon.php');
?>

<section class="complete">
    <div class="container">
        <span><i class="fa-solid fa-house" style="color: #ae15ee;"></i></span>
        <h1>You’re all set, please check your email to confirm the booking details.</h1>
        <div class="btn">
            <a href="index.php">HOME</a>
        </div>
        
    </div>
</section>
<?php include('./includes/footer.php'); ?>
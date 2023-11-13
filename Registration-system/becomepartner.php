<?php

$main_css_file = "views/becomepartnerstyles.css";
include('./includes/header.php');
?>

<section class="become">
        <div class="become-container">
            <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
            <div class="become-form">
            
            <div class="head-become">
                <a href="">LI</a><a id="second"href="">SM.</a>
            </div> 
            <h2>Become a partner</h2>
            <form class="form-box-become" method="post" action="becomepartnercode.php">
                <label for="Long-box">Company</label><br>
                <div class="Long-box"><input type="text" name="company_name" class="Long-box" placeholder="Enter company name"></div><br>
                <div class="container-row-small-box">    
                    <div class="form-small-box-first">
                        <label for="small-box">Full name</label><br>
                        <div class="small-box"><input type="text" class="small-box" name="first_name" placeholder="First name"></div><br>
                    </div>
                    <div class="form-small-box-second">
                        
                        <div class="small-box"><input type="text" class="small-box" name="last_name" placeholder="Last name"></div><br>
                    </div>
            </div>
                <label for="Long-box">License number</label><br>
                <div class="Long-box"><input type="text" class="Long-box" name="license_number" placeholder="Enter your company license number"></div><br>
                <div class="container-row-small-box">    
                        <div class="form-small-box-first">
                            <label for="small-box">Date of issue</label><br>
                            <div class="small-box"><input type="date" name="date_of_issue" class="small-box" placeholder="First name"></div><br>
                        </div>
                        <div class="form-small-box-second">
                            <label for="small-box">Phone number</label><br>
                            <div class="small-box"><input type="text" name="phone_number" class="small-box" placeholder="Phone number"></div><br>
                        </div>
                </div>
                <label for="Long-box">Company</label><br>
                <div class="Long-box"><input type="text" class="Long-box" name="email_company" placeholder="Enter your company Email address"></div><br>
                <label for="Long-box">Company website</label><br>
                <div class="Long-box"><input type="text" class="Long-box" name="website_company" placeholder="Enter link URL"></div><br>
                <button type="submit" name="becomepartner_now_btn"  class="becomepartner_btn">continue with email</button>
            </form>
        </div>
            <div class="last"><p>By signing in or creating an account, you agree with our Terms & Conditions and Privacy Statement</p></div>  
        </div>
    </section>
<?php include('./includes/footer.php'); ?>
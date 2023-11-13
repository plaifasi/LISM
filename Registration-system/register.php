<?php
session_start();
$main_css_file = "views/sighinstyles.css";
include('./includes/header.php');
?>
<section class="sigh-in">
        <div class="sigh-in-container">
 
            <div class="sigh-in-form">
            
            <div class="head-sigh-in">
                <a href="">LI</a><a id="second"href="">SM.</a>
            </div> 
            <h2>Sign in or create an account</h2>
            <div class="alert">
                <?php 
                    if(isset($_SESSION['status']))
                    {
                        echo "<h1>".$_SESSION['status']."</h1>";
                        unset($_SESSION['status']);
                    }
                ?>
            </div>
            <form action="code.php" class="form-box-sigh-in" method ="POST">
                <label for="email-box">Email address</label><br>
                <div class="email-box"><input type="text" name="email" class="email-address" placeholder="Enter your Email address"></div><br>
                <button type="submit" name="register_btn" href=""class="sigh-in-button">continue with email</button>
            </form>
            
        </div>
            <div class="last"><p>By signing in or creating an account, you agree with our Terms & Conditions and Privacy Statement</p></div>  
        </div>
    </section>

<?php include('./includes/footer.php'); ?>
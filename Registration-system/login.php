<?php
session_start();
if(isset($_SESSION['authenticated']))
{
    $_SESSION['status'] = "You alredy login";
    header('Location: index.php');
    exit(0);
}

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
                        ?>
                        <div class="altert altert-success">
                            <h5><?php echo $_SESSION['status']; ?> </h5>
                        </div>
                        <?php
                        unset($_SESSION['status']);
                    }
                ?>
            </div>
            <form action="logincode.php" class="form-box-sigh-in" method ="POST">
                <label for="email-box">Email address</label><br>
                <div class="email-box"><input type="text" name="email" class="email-address" placeholder="Enter your Email address"></div><br>
                <button type="submit" name="login_now_btn" href=""class="sigh-in-button">continue with email</button>
            </form>
            
        </div>
            <div class="last"><p>By signing in or creating an account, you agree with our Terms & Conditions and Privacy Statement</p></div>  
        </div>
    </section>

<?php include('./includes/footer.php'); ?>
<nav>
        <div class="container">
            <div class="nav-con">
                <div class="logo">
                    <a href="index.php">LI</a><a id="second"href="index.php">SM.</a>
                </div>
                <ul class="menu">
                    <li><a href="becomepartner.php">BECOME PARTNER</a></li>
                    <?php if(!isset($_SESSION['authenticated'])) : ?>
                    <li><a href="register.php">CREATE ACCOUNT</a></li>
                    <li><a href="login.php">SIGN IN</a></li>
                    <?php endif ?>
                    <?php if(isset($_SESSION['authenticated'])) : ?>
                    <li><a href="myprofile.php">MY PROFILE</a></li>
                    <li><a href="bookmarkdisplay.php">SAVED</a></li>
                    <li><a href="history.php">MY BOOKING</a></li>
                    <li><a href="logout.php">LOGOUT</a></li>
                    <?php endif ?>
                </ul>
            </div>
        </civ>
</nav>
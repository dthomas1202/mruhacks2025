<header>
        <div class="leftHeader">
            <a href="header.html" class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 34 34"
                    fill="none">
                    <path d="M34 17C34 26.3888 26.3888 34 17 34C7.61116 34 -8.20798e-07 26.3888 0 17C8.20799e-07 7.61116 7.61116 -8.20799e-07 17 0C26.3888 8.20799e-07 34 7.61116 34 17Z"
                        fill="#80A861" />
                </svg>
            </a>
            <a id="sessions" href="map.php">Sessions</a>
            <a id="groups" href="groups.php">Groups</a>
            <a id="profile" href="profile.php">Profile</a>
        </div>
        <?php if (!isset($_SESSION['userName'])){
            echo "<div class='rightHeader'>";
            echo "<a id='login' href='login.php'>Log in</a>";
            echo "<a id='signup' href='createUser.php'>Sign up</a>";
            echo "<a id='logout' href='logout.php'>Log out</a>";
        echo "</div>";
        } else{
            echo "<div class='rightHeader'>";
            echo "<a id='logout' href='logout.php'>Log out</a>";
        }
        ?>
    </header>
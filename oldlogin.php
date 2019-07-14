<?php
session_start();

if(isset($_SESSION['isLogged']))
    if($_SESSION['isLogged'])
        header('location: home.php');

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Enigma Text</title>
        <link rel="stylesheet" type="text/css" href="stylesheets/indexstyle.css">
        <link rel="stylesheet" type="text/css" href="stylesheets/loginstyle.css">

    </head>
    <body>
        <header>
            <div class="title-bar">
                Enigma Text

            </div>

        </header>

        <section class="main-section">

            <h1>Please enter your username and password:</h1>

            <form class="register-form" method="POST" onsubmit="return Validate()"
            name="login-form" action="login2.php">
                <div>
                    <label>Username</label>
                    <input type="text" name="username">
                 </div>
                 <div id="usernameError" class="errors"></div>

                 <div>
                    <label>Password</label>
                    <input type="password" name="password">
                </div>
                <div id="passwordError" class="errors"></div>
                <div>
                    <input type="submit" name="login" value="Submit" class="submit-button">
                </div>
        </form>
        </section>

        <script src="scripts/loginErrors.js"></script>
    </body>
</html>
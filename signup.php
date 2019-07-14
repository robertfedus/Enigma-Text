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
    <?php
    if(isset($_GET['error']))
        echo '<script>alert("Error: ' . $_GET['error'] . ' already used");</script>';
    ?>

        <header class="main-header">
            <div class="title-bar">
                Enigma Text
             </div>
        </header>

        <section class="main-section">
            <h1>Please enter all your personal data in the form below:</h1>

            <form class="register-form" method="post" onsubmit="return Validate()"
            name="register-form" action="signup2.php">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" id="username">
                </div>
                <div id="usernameError" class="errors"></div>

                <div>
                    <label>Birthday</label>
                    <input type="date" name="birthday">
                </div>
                <div id="birthdayError" class="errors"></div>
                <div>
                    <label>Gender</label>
                    <select name="gender">

                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>

                    </select>
                    <div id="genderError" class="errors"></div>
                </div>

                <div>
                    <label>Email</label>
                    <input type="text" name="email">
                 </div>
                 <div id="emailError" class="errors"></div>

                 <div>
                    <label>Password</label>
                    <input type="password" name="password" id="passLength">
                </div>
                <div id="passwordError" class="errors"></div>
                <div>
                    <label>Confirm password</label>
                    <input type="password" name="passwordConfirmation">
                </div>
                <div id="passwordConfirmationError" class="errors"></div>
                <div>
                    <input type="submit" name="register" value="Register" class="submit-button">
                </div>
            </form>
        </section>

        <script src="scripts/signupErrors.js"></script>
        

    </body>
</html>

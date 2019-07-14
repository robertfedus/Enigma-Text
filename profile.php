<?php
session_start();

if(!isset($_SESSION['isLogged'])){
    header('location: login.php');
    exit;
}

if(!isset($_GET['user']))
    header('location: profile.php?user=' . $_SESSION['username']);


?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/indexstyle.css">
        <style>
            input{
                width: 300px;
                margin-top: 0;
                border-bottom: 2px solid red;
            }

            .submit-button, .req-button{
                margin-top: 20px;
                border: none;
                border-top: 2px solid #696666;
                border-bottom: 2px solid #696666;
            }
            .submit-button:hover, .req-button:hover{
                border-top: 2px solid red;
            }

            .column, .column2{
                float: left;
                width: 50%;
                padding-top: 20%;
            }

            .column{
                padding-top: 5%;
            }

            form{
                margin: 0;
                margin-top: 2em;
            }

            h2{
                text-align: left;
                margin-left: 2em;
                text-decoration: underline;
            }

            .friend, .req-buttons{
                text-align: left;
                margin-left: 6em;
            }

            
            p{
                font-size: 22px;
                margin-top: 2em;
                color: red;

            }

            .footer p{
                color: white;
                font-size: 12px;
            }

            .error{
                color: red;
                font-size: 14px;
            }

            button:hover{
                border-bottom: 2px solid red;
            }

            .edit-button, .log-button{
                border-top: 1px solid red; 
                border-bottom: 2px solid #696666; 
                vertical-align: top;
            }

            .container{
                height: auto;
            }
            select{
                -webkit-appearance: button;
                -moz-appearance: button;
                -webkit-user-select: none;
                -moz-user-select: none;
                -webkit-padding-end: 20px;
                -moz-padding-end: 20px;
                -webkit-padding-start: 2px;
                -moz-padding-start: 2px;
                background-color: black;
                color: white;
                padding-left: 30px;
                height: 50px;
                border-top: 2px solid #696666;
                border-bottom: 2px solid #696666;
                border-right: none;
                border-left: none;
                border-radius: 3px;
                font-weight: bold;
            }

            select:hover{
                border-top: 2px solid red;
                border-bottom: 2px solid red;
                color: #696666;
            }
        </style>
        <title>Enigma Text</title>
    </head>

    <body>
        
        <div class="title-bar">
            <h1>Enigma Text</h1>
        </div>

        <div class="buttons">
            <a href="home.php"><button>Home</button></a>
            <a href="friend.php">
            <button style="font-size: 15px; vertical-align: top;">
                <?php


                $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
                $user = $_SESSION['username'];
                if(!$conn){
                    die('Error connecting to database: ' . mysql_error());
                }
                $result = mysqli_query($conn, "SELECT * FROM requests WHERE requested = '$user'");

                if(mysqli_num_rows($result) > 0)
                    echo "Friends (" . mysqli_num_rows($result) . ")";
                    else
                        echo "Friends";
                ?>
            </button></a>
            <?php echo '<a href="profile.php?user=' . $_SESSION['username'] . '"><button>Profile</button></a>';?>
        </div>

        <div class="container">
            <div class="page-title">
                <?php 
                if(!isset($_GET['user']))
                    echo $_SESSION['username'] . "'s profile";
                else
                    echo $_GET['user'] . "'s profile";
                
                ?>
            </div>

            <?php
            if(isset($_GET['user']) && $_GET['user'] == $_SESSION['username']){
                echo '<div style="text-align: left; font-size: 0;">';
                echo '<a href="profile.php?user=' . $_SESSION['username'] . '&edit=1"><button class="edit-button">Edit profile</button></a>';
                echo '<a href="logout.php"><button class="log-button">Log out</button></a>';
                echo '</div>';
                $user = $_SESSION['username'];
            } 
            
            if($_GET['user'] != $_SESSION['username']){
                $user = $_GET['user'];
                $username = $_SESSION['username'];
                $result = mysqli_query($conn, "SELECT * FROM friends WHERE requested = '$user' AND accepted = '$username' OR accepted = '$user' AND requested = '$username'");

                if(mysqli_num_rows($result) != 0){
                    $user = $_GET['user'];
                    $_SESSION['removeUser'] = $_GET['user'];
                    echo '<div style="text-align: left; font-size: 0;">';
                    echo '<a href="remove.php"><button class="edit-button" onclick="remove(); return false;">Remove friend</button></a>';
                    echo '</div>';
                }
                    else
                        $user = $_GET['user'];
            }

        $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
            
        if(!$conn) {
            die('Error connecting to database: ' . mysql_error());
         }
        $result = mysqli_query($conn, "SELECT * FROM accounts WHERE username='$user'");
    
        echo '<div style="margin-top: 3em; margin-bottom: 2em;">';
        echo '<div class="page-title">';
        echo "Username:";
        echo "</div>";
        while($row = mysqli_fetch_array($result))
            echo '<p>' . $row['username'] . "</p>";
        echo "<br>";
        

        $result = mysqli_query($conn, "SELECT * FROM accounts WHERE username='$user'");
        echo '<div class="page-title" style="margin-top: 2em;">';
        echo "Email:";
        echo "</div>";
        while($row = mysqli_fetch_array($result))
            echo "<p>" . $row['email'] . "</p>"; 
        echo "<br>";
        if(isset($_GET['edit']) && $_GET['user'] == $_SESSION['username']){
            if($_GET['edit'] == 1){
                echo '<p style="margin: 0;">Enter your new email</p>';
                echo '<form class="edit-form" method="POST" name="edit-email" action="edit.php">';
                echo '<input type="text" name="edit-email">';
                echo "<br>";
                echo '<input type="submit" name="submit-button" value="Submit" class="submit-button">';
                echo "</form>";
            }
        }


        $result = mysqli_query($conn, "SELECT * FROM accounts WHERE username='$user'");
        echo '<div class="page-title" style="margin-top: 2em;">';
        echo "Gender:"; 
        echo "</div>";
        while($row = mysqli_fetch_array($result))
            echo "<p>" . $row['gender'] . "</p>"; 
        echo "<br>";

        if(isset($_GET['edit']) && $_GET['user'] == $_SESSION['username']){
            if($_GET['edit'] == 1){
                echo '<p style="margin: 0;">Select your new gender</p>';
                echo '<form class="edit-form" method="POST" name="edit-form" action="edit.php">';
                echo '<select name="edit-gender">';

                echo '<option value="Male">Male</option>';
                echo '<option value="Female">Female</option>';
                echo '<option value="Other">Other</option>';

                echo '</select>';
                echo "<br>";
                echo '<input type="submit" name="submit-button" value="Submit" class="submit-button">';
                echo "</form>";
            }
        }


        $result = mysqli_query($conn, "SELECT * FROM accounts WHERE username='$user'");
        echo '<div class="page-title" style="margin-top: 2em;">';
        echo "Birthday:"; 
        echo "</div>";
        while($row = mysqli_fetch_array($result))
            echo "<p>" . $row['birthday'] . "</p>"; 
        echo "</div>";

        if(isset($_GET['edit']) && $_GET['user'] == $_SESSION['username']){
            if($_GET['edit'] == 1){
                echo '<p style="margin: 0;">Enter your birthday:</p>';
                echo '<form class="edit-form" method="POST" name="edit-form" action="edit.php">';
                echo '<input type="date" name="edit-birthday">';
                echo "<br>";
                echo '<input type="submit" name="submit-button" value="Submit" class="submit-button" style="margin-bottom: 15px;">';
                echo "</form>";
            }
        }
        ?>

        </div>

        <div class="footer">
            <p>Copyright &copy; 2019 by Fedus Robert </p>
        </div>
        <script src="scripts/alert.js"></script>
    </body>
</html>
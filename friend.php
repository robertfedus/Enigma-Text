<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/indexstyle.css">
        <style>
            div.message-div{
                background-color: #696666;
                height: 38em;
                width: 28%;
                margin: 0 auto;
                border-radius: 3px;
                overflow: auto;
                text-align: center;
                
                margin-left: 2em;
                
            }
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

            .column, .column2, .column3{
                float: left;
                padding-top: 20%;
                width: 50%;
            }

            .column{
                padding-top: 0%;
                width:50%;
            }

            .column2{
                padding-top: 0%;
            }

            form{
                margin: 0;
                margin-top: 2em;
            }

            h2{
                text-align: left; 
                margin-left: 2em;
            }

            .friend, .req-buttons{
                text-align: left;
                margin-left: 6em;
            }

            
            p{
                font-size: 22px;
            }

            .footer p{
                font-size: 12px;
            }
            .error{
                color: red;
                font-size: 14px;
            }

            a{
                color: white;
                font-size: 18px;
                text-decoration: none;
            }

            a:hover{
                color: red;
            }

            .accept-a:hover{
                color: green;
            }

            .profile-a:hover{
                color: #696666;
            }

            .friend-div{
            background-color: black;
            border-bottom: 2px solid red;
            font-size: 11px;
            
            }

            .h2-right{
                text-align: right;
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
                session_start();

                $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
                $user = $_SESSION['username'];
                if(!$conn){
                    die('Error connecting to database: ' . mysql_error());
                }
                $friendNotif = mysqli_query($conn, "SELECT * FROM requests WHERE requested = '$user'");

                if(mysqli_num_rows($friendNotif) > 0)
                    echo "Friends (" . mysqli_num_rows($friendNotif) . ")";
                    else
                        echo "Friends";
                ?>
            </button></a>
            <?php echo '<a href="profile.php?user=' . $_SESSION['username'] . '"><button>Profile</button></a>';?>
        </div>

        <div class="container">
            <div class="page-title">
               <?php echo $_SESSION['username'] . "'s friends"?>
            </div>
            <div class="column" style="position: relative;">


            <?php
            $user = $_SESSION['username'];

            $result = mysqli_query($conn, "SELECT * FROM friends WHERE accepted = '$user' OR requested = '$user'");     ?>
            
            <div class="message-div" style="float: left; overflow: auto;  scrollbar-width: none; margin-bottom:2em; padding-bottom: 0.5em;" id="friend-list">
            <?php

            $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
            
            if(!$conn) {
                die('Error connecting to database: ' . mysql_error());
             }
            

             echo '<div class="friend-div" style="font-size: 18px; color: #696666;border-bottom: 2px solid red; height: 40px;">Friend list:</div>';
            while($row = mysqli_fetch_array($result)){
                if($row['accepted'] == $user){
                    //echo '<div style="text-align: left; margin-left: 8em;">';
                    echo '<div class="friend-div"><a href="profile.php?user=' . $row['requested'] . '">' . $row['requested'] . '</a></div>';
                    //echo "</div>";

                }

                if($row['requested'] == $user){
                    //echo '<div style="text-align: left; margin-left: 8em;">';
                    echo '<div class="friend-div"><a href="profile.php?user=' . $row['accepted'] . '">' . $row['accepted'] . '</a></div>';
                    //echo "</div>";

                }
            }
            ?>
            </div>

            
        <div class="message-div" style="float: center; overflow: auto;  scrollbar-width: none; margin-bottom:2em; padding-bottom: 0.5em; height: 20em;" id="friend-list">   
        <?php
            $user = $_SESSION['username'];
            $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
            $result = mysqli_query($conn, "SELECT * FROM requests WHERE requested = '$user'");
            ?>
            <?php

            if($_SESSION['isLogged'] == false){
                header('location: login.php');
                exit;
            }
            
            

            
            if(!$conn) {
                die('Error connecting to database: ' . mysql_error());
             }
            $user = $_SESSION['username'];
            
            $result = mysqli_query($conn, "SELECT * FROM requests WHERE requested = '$user'");

                echo '<div class="friend-div" style="font-size: 18px; color: #696666;border-bottom: 2px solid red; height: 40px;">Requests:</div>';
             while($row = mysqli_fetch_array($result)){
                echo '<div class="friend-div" style="font-size: 18px;">' . $row['requester'] . "</div>";

                 //$_SESSION['requester'] = $row['requester'];
                 $requester = $row['requester'];
                 echo '<div class="friend-div" style="font-size: 15px; border-bottom: 3px solid green;">';
                 echo '<a class="accept-a" href="acceptRequest.php?req=' . $requester . '&delReq=0">Accept </a>';
                 echo '<a class="delete-a" href="acceptRequest.php?req=' . $requester .'&delReq=1">Delete </a>';
                 echo '<a class="profile-a" href="profile.php?user=' . $requester . '">Profile </a>';
                 echo "<br>";
                 echo "</div>";
             }
             
            ?>
            </div>
            </div>

            <div class="column2">

        </div>
        <p>Add a new friend by typing in his username:</p>
            <form class="add-friend" method="POST" name="add-friend" action="request.php">
                <input type="text" name="friend-username">
                <br>
                <input type="submit" name="submit-button" value="Submit" class="submit-button">
            </form>

            <?php
            if(isset($_GET['sent'])){
                $sent = $_GET['sent'];
                switch($sent){
                    case 1:
                        echo '<p class="error" style="color:green;">Request sent successfully</p>';
                        break;
                
                    case 0:
                        echo '<p class="error">Error: username does not exist</p>';
                        break;
                    case 2:
                        echo '<p class="error">Error: request already sent to that user</p>';
                        break;
                    case 3:
                        echo '<p class="error">Error: user is already in your friend list</p>';
                        break;
                    case 4:
                        echo '<p class="error">Error: can' . "'" . 't send a friend request to yourself</p>';
                        break;
                    case 5:
                        echo '<p class="error">Error: user already sent you a friend request</p>';
                        break;
                }
                
            }

            ?>
            </div>

        <div class="footer">
            <p>Copyright &copy; 2019 by Fedus Robert </p>
        </div>

    </body>
</html>
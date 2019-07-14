<?php
session_start();

if($_SESSION['isLogged'] == false){
    header('location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/indexstyle.css">
        <link rel="stylesheet" type="text/css" href="stylesheets/chat.css">

        <title>Enigma Text</title>
        <style>
        ::-webkit-scrollbar{
        display: none;
        }

        table div{
            text-align: left;
        }

        .real-time{
            text-align: right;
        }

        .column, .column2{
            float: left;
        }
        .column2{
            width:20%;
        }
        .column{
            width: 80%;
        }

        a{
            color: white;
            font-size: 18px;
            horizontal-align: left;
            text-decoration: none;
            
        }

        a:hover{
            /*color: #696666;*/
            color: red;
        }

        .footer{
            margin: 0.7em auto;
            width: 60%;
            font-size: 12px;
        }

        #friend-list ::-webkit-scrollbar{
            display: inline;
            
        }

        #friend-list{
            height: 38em;
        }



        .friend-div{
            background-color: black;
            border-bottom: 2px solid red;
            font-size: 11px;
        }
        </style>
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
                Home
            </div>

            <div class="column2">
                <h2 style="color: red;">Chats:</h2>
                <div class="message-div" style="overflow: auto;  scrollbar-width: none; margin-bottom:2em; padding-bottom: 0.5em;" id="friend-list">
            <?php

            $conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
            
            if(!$conn) {
                die('Error connecting to database: ' . mysql_error());
             }
            $user = $_SESSION['username'];

            $result = mysqli_query($conn, "SELECT * FROM friends WHERE accepted = '$user' OR requested = '$user'");


            while($row = mysqli_fetch_array($result)){
                if($row['accepted'] == $user){
                    echo '<div class="friend-div"><a href="chatroom.php?chat=' . $row['requested'] . '">' . $row['requested'] . '</a></div>';
                    echo '<div style="text-align: left; margin-left: 8em;">';
                    echo "</div>";
                }

                if($row['requested'] == $user){
                    echo '<div class="friend-div"><a href="chatroom.php?chat=' . $row['accepted'] . '">' . $row['accepted'] . '</a></div>';
                    echo '<div style="text-align: left; margin-left: 8em;">';

                    echo "</div>";

                }
            }
            ?>
                </div>
            </div>

            <div class="column">
                <div class="message-div" style="overflow: auto; display: flex; flex-direction: column-reverse; scrollbar-width: none; margin-top:2em; padding-bottom: 0.5em;" id="message-div">
                    <table id="chat">
                    <tbody>
                    <?php

                    $messageConn = mysqli_connect("localhost", "robertfedus", "r4997740", "messages");

                    if(!$messageConn){
                        die('Error connecting to database: ' . mysql_error());
                    }
                    if(isset($_GET['chat'])){
                        $case1 = strtolower($user . "_" . $_GET['chat']);
                        $case2 = strtolower($_GET['chat'] . "_" . $user);
                    
                        $checkTable1 = mysqli_query($messageConn, "SELECT 1 FROM $case1 LIMIT 1");
                        $checkTable2 = mysqli_query($messageConn, "SELECT 1 FROM $case2 LIMIT 1");

                        if($checkTable1){

                            $result = mysqli_query($messageConn, "SELECT * FROM $case1");
                        } else if($checkTable2){

                            $result = mysqli_query($messageConn, "SELECT * FROM $case2");
                        }


                        while($row = mysqli_fetch_array($result)){
                            if($row['senter'] == $_SESSION['username']){
                                $row['senter'] = 'You';
                                echo '<tr><td align="right"><div style="width: 50%; text-align: right;"><div style="padding: 3px; font-family: Verdana; border-bottom: 3px solid green; background-color: black; display: inline-block; font-size: 18px; border-radius: 1em; text-align: center; margin-right: 0.2em;"><p style="margin-left: 1em; margin-right: 0.35em;">' . $row['message'] . '</p></div></div>';
                                //echo '<tr><td align="right"><div style="background-color: black; text-align: right;"><strong>' . $row['senter'] . ': </strong></div><div style="text-align: right;">' . $row['message'];
                            }
                            else    
                                //echo '<tr><td><div align="left" style="background-color: black;"><strong>' . $row['senter'] . ': </strong></div><div>' . $row['message'];
                                echo '<tr><td align="left"><div style="width: 50%; text-align: left;"><div style="padding: 3px; font-family: Verdana; border-bottom: 3px solid red; background-color: black; display: inline-block; font-size: 18px; border-radius: 1em; text-align: center; margin-left: 0.2em;"><p style="margin-right: 1em; margin-left: 0.35em;">' . $row['message'] . '</p></div></div>';
                        }
                    } else{
                        echo '<tr><td><div>Please start a new conversation with one of your friends.</div></td>';
                    }
                    ?>
                    </tbody>
                    </table>
                </div>

            <form name="chat-form" method="post" action="" style="margin: 0;">

            <?php echo '<input type="hidden" name="username" id="username" value="' . $_SESSION['username'] . '">';
            if(isset($_GET['chat'])){
                echo '<input type="hidden" name="chat" id="chat" value="' . $_GET['chat'] . '">';
                echo '<input type="textarea" placeholder="Enter a message" name="msg" class="msg" id="msg">';
                echo '<input type="button" value="Send" name="send" class="submit-button" id="send">';
            }
            ?>

            </form>
            </div>





        </div>

        <div class="footer">
            <p>Copyright &copy; 2019 by Fedus Robert </p>
        </div>

        <script>
        window.onload=function () {
            let objDiv = document.getElementById('message-div');
            objDiv.scrollTop = objDiv.scrollHeight;
           
        }
        </script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="scripts/connect.js"></script>
    </body>
    
</html>
<?php
session_start();

if(isset($_SESSION['isLogged']))
    if($_SESSION['isLogged'])
        header('location: home.php');

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/indexstyle.css">

        <title>Enigma Text</title>
    </head>
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
    <body>
        
        <div class="title-bar">
            <h1>Enigma Text</h1>
        </div>

        <div class="buttons">
            <a href="signup.php"><button>Register</button></a>
            <a href="login.php"><button>Log in</button></a>
        </div>

        <div class="container">
            <div class="page-title">
                PAGE NAME
            </div>

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



        </div>

        <div class="footer">
            <p>Copyright &copy; 2019 by Fedus Robert </p>
        </div>

    </body>
</html>
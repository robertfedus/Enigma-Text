<?php
session_start();

if(!$_SESSION['isLogged']){
    header('location: login.php');
    exit;
}

$conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");
$user = $_SESSION['username'];
if(!$conn){
    die('Error connecting to database: ' . mysql_error());
}

$chat = $_GET['chat'];
$_SESSION['chat'] = $chat;

mysqli_query($conn, "UPDATE accounts SET current_chat = '$chat' WHERE username = '$user'");

$user = strtolower($_SESSION['username']);
$chat = strtolower($_GET['chat']);
$case1 = $user . "_" . $chat;
$case2 = $chat . "_" . $user;

$conn = mysqli_connect("localhost", "robertfedus", "r4997740", "messages");

$checkTable1 = mysqli_query($conn, "SELECT 1 FROM $case1 LIMIT 1");
$checkTable2 = mysqli_query($conn, "SELECT 1 FROM $case2 LIMIT 1");

if($checkTable1 || $checkTable2){
   echo "Table exists";
} else{
    echo "Table doesn't exist";
    mysqli_query($conn, "CREATE TABLE messages. $case1 ( `id` INT NOT NULL AUTO_INCREMENT , `senter` VARCHAR(15) NOT NULL , `message` VARCHAR(300) NOT NULL , `encrypted` VARCHAR(300) NOT NULL, `time` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
}
header('location: home.php?chat=' . $chat);

?>
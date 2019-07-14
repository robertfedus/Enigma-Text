<?php
session_start();

if($_SESSION['isLogged'] == false){
    header('location: login.html');
    exit;
}


$conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");

if(!$conn) {
    die('Error connecting to database: ' . mysql_error());
 }

$requester = $_SESSION['username'];
$user = $_SESSION['username'];
$requested = $_POST['friend-username'];
$result = mysqli_query($conn, "SELECT * FROM accounts WHERE username = '$requested'");
$result2 = mysqli_query($conn, "SELECT * FROM requests WHERE requested = '$requested' AND requester = '$requester'");
$result3 = mysqli_query($conn, "SELECT * FROM friends WHERE requested = '$user' AND accepted = '$requested' OR requested = '$requested' AND accepted = '$user'");
$result4 = mysqli_query($conn, "SELECT * FROM requests WHERE requester = '$requested'");

if(mysqli_num_rows($result) == 1 && mysqli_num_rows($result2) == 0 && mysqli_num_rows($result3) == 0 && $requester != $requested && mysqli_num_rows($result4) == 0){
    mysqli_query($conn, "INSERT INTO requests (requester, requested) VALUES ('$requester', '$requested')");
    $sent = 1;
}
    //header('location: friend.php?sent=1');
if(mysqli_num_rows($result) == 0)
    $sent = 0;
    //header('location: friend.php?sent=0');
if(mysqli_num_rows($result2) != 0)
    $sent = 2;

if(mysqli_num_rows($result3) != 0)
    $sent = 3;

if($requester == $requested)
    $sent = 4;

if(mysqli_num_rows($result4) != 0)
    $sent = 5;
    
header('location: friend.php?sent=' . $sent);






?>
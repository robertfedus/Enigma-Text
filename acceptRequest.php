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

$requester = $_GET['req'];
$del = $_GET['delReq'];
$user = $_SESSION['username'];

if($del == 0){
    mysqli_query($conn, "INSERT INTO friends(requested, accepted) VALUES('$requester', '$user')");
    mysqli_query($conn, "DELETE FROM requests WHERE requester = '$requester'");
}else
    mysqli_query($conn, "DELETE FROM requests WHERE requester = '$requester' AND requested = '$user'");
    

header("location: friend.php");


?>
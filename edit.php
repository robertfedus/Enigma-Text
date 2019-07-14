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

$user = $_SESSION['username'];

 if(isset($_POST['edit-email'])){
    $email = $_POST['edit-email'];
    mysqli_query($conn, "UPDATE accounts SET email = '$email' WHERE username = '$user'");
    header('location: profile.php');
 }

 if(isset($_POST['edit-gender'])){
     $gender = $_POST['edit-gender'];
     mysqli_query($conn, "UPDATE accounts SET gender = '$gender' WHERE username = '$user'");
     header('location: profile.php');
 }

 if(isset($_POST['edit-birthday'])){
     $birthday = $_POST['edit-birthday'];
     mysqli_query($conn, "UPDATE accounts SET birthday = '$birthday' WHERE username = '$user'");
     header('location: profile.php');
 }

?>
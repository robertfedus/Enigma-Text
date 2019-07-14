<?php
session_start();

$conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");


$username = $_POST['username'];
$password = md5($_POST['password']);
$email = $_POST['email'];
$gender = $_POST['gender'];
$birthday = $_POST['birthday'];

$result = mysqli_query($conn, "SELECT * FROM accounts WHERE username = '$username'");
$result2 = mysqli_query($conn, "SELECT * FROM accounts WHERE email = '$email'");

if(mysqli_num_rows($result) > 0){
    header('location: signup.php?error=username');
    $end = true;
} else
    $end = false;

if(mysqli_num_rows($result2) > 0){
    header('location: signup.php?error=email');
    $end = true;
} else
    $end = false;

if(!$end){
    $sql = "INSERT INTO accounts(username, password, email, gender, birthday) 
    VALUES('$username', '$password', '$email', '$gender', '$birthday')";

    mysqli_query($conn, $sql);


    if(isset($_POST['password'])){
        $_SESSION['message'] = "You have successfully created an account!";
        header("location: login.php");

    }
}
?>
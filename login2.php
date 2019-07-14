<?php
session_start();

$_SESSION['isLogged'] = false;

$conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");

$username = $_POST['username'];
$_SESSION['username'] = $username;
$password = md5($_POST['password']);

$sql = "SELECT * FROM accounts WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 1){ //Verificare daca username-ul si parola se afla in baza de date

    $_SESSION['message'] = "You are now logged in";
    $_SESSION['username'] = $username;
    $_SESSION['isLogged'] = true;
    header("location: home.php");
    exit;

} else{

    if(isset($_POST['password'])){
    
    $_SESSION['message'] = "Username or password is incorrect.";
    header("location: home.php");
    }
}

mysqli_query($conn, $sql);
?>


<?php
session_start();

$conn = mysqli_connect("localhost", "robertfedus", "r4997740", "users");

$user = $_SESSION['removeUser'];
$username = $_SESSION['username'];

mysqli_query($conn, "DELETE FROM friends WHERE requested = '$user' AND accepted = '$username' OR requested = '$username' AND accepted = '$user'");

header('location: profile.php?user=' . $user);


?>
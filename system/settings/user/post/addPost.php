<?php 
session_start();
if ($_COOKIE['logged']!=true) {
    header("Location: ../../../admin.php");
}
include('../../../../etc/config.php');
$conn = mysqli_connect($server, $user, $password, $db);
$password = md5($_POST['password']);
$sql = "INSERT INTO `users`(`username`, `password`, `group`) VALUES ('{$_POST['username']}','{$password}','{$_POST['group']}')";
$result = $conn->query($sql);
header("Location: ../manageUsers.php");
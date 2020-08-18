<?php 
session_start();
if ($_COOKIE['logged']!=true) {
    header("Location: ../../../admin.php");
}
include('../../../../etc/config.php');
$conn = mysqli_connect($server, $user, $password, $db);
$password = md5($_POST['password']);
$sql = "UPDATE `users` SET `username`='{$_POST['username']}',`password`='{$password}',`group`='{$_POST['group']}' WHERE `id`='{$_POST['id']}'";
$result = $conn->query($sql);
header("Location: ../manageUsers.php");
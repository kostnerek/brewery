<?php 

session_start();
if ($_COOKIE['logged']!=true) {
    header("Location: ../../../admin.php");
}
include('../../../../etc/config.php');
$conn = mysqli_connect($server, $user, $password, $db);


$sql = "DELETE FROM `users` WHERE id='{$_POST['id']}'";
$result = $conn->query($sql);
header("Location: ../manageUsers.php");
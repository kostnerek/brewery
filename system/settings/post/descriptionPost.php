<?php 


include('../../../etc/config.php');
$conn = mysqli_connect($server, $user, $password, $db);


$header = $_POST['header'];
$body   = $_POST['body'];

$sql = "UPDATE `description` SET `header`='{$header}', `body` = '{$body}' WHERE id=1";
$result = $conn->query($sql);
echo "<meta http-equiv=\"refresh\" content=\"0;url=../action/setDescription.php\">";


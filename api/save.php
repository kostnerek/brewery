<?php 
include('../etc/config.php');
$conn = mysqli_connect($server, $user, $password, $db);

$r0 = str_replace(",","",$_GET['r0']);
$r1 = str_replace(",","",$_GET['r1']);
$r2 = str_replace(",","",$_GET['r2']);
$r3 = str_replace(",","",$_GET['r3']);
$r4 = str_replace(",","",$_GET['r4']);
$r5 = str_replace(",","",$_GET['r5']);
$r6 = str_replace(",","",$_GET['r6']);
$r7 = str_replace(",","",$_GET['r7']);

$dataAll = [$r0, $r1, $r2, $r3, $r4, $r5, $r6, $r7];
var_dump($dataAll);

$sql = "UPDATE `api` SET `r0`='{$r0}',`r1`='{$r1}',`r2`='{$r2}',`r3`='{$r3}',`r4`='{$r4}',`r5`='{$r5}',`r6`='{$r6}',`r7`='{$r7}' WHERE `id` = 1";
echo $sql;
$conn->query($sql);
echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
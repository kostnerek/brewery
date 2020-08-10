<?php
session_start();
$_COOKIE['logged'] = false;
$_COOKIE['username'] = ':)';
$_COOKIE['password'] = ':)';
echo "<meta http-equiv=\"refresh\" content=\"0;url=list/list.php\">";
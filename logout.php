<?php
session_start();
$_COOKIE['logged'] = false;
$_COOKIE['username'] = ':)';
$_COOKIE['group'] = ':)';
echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
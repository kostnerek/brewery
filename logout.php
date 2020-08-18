<?php
$_COOKIE['logged'] = false;
$_COOKIE['username'] = ':)';
$_COOKIE['group'] = ':)';
setcookie('logged', false);
setcookie('username', ':)');
setcookie('group', ':)');
echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";

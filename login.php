<?php 
setcookie('logged', true);
setcookie('username', $_GET['u']);
setcookie('group', $_GET['g']);
echo "<meta http-equiv=\"refresh\" content=\"0;url=list/list.php?1\">";
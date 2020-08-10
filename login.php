<?php 
setcookie('logged', true);
setcookie('username', md5($username));
setcookie('group', $row['group']);
echo "<meta http-equiv=\"refresh\" content=\"0;url=list/list.php\">";

<?php 
    session_start();
    if ($_SESSION['status']==true) {
        header("Location: admin/admin.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href=".\resources\css\main.css">
</head>
<body>
    
    <form action="admin.php" method="POST">
        <input type="text" name="login" style="width:200px"><br>
        <input type="password" name="password" style="width:200px"><br>
        <input type="submit" value="Login" style="width:209px">
    </form>
    <form action="<?php $_SESSION['status']=false ?>" method="POST">
        <input type="submit" value="Logout" style="width:209px">
    </form>

    <?php 
        error_reporting(0);
        $login  = $_POST['login'];//emson
        $pass   = $_POST['password'];//brewerypassword
        $status = false;
        if (md5($pass)==='6c1afb8be9068c9931bb5d5292795c7c' && md5($login)=='04417c12a95b4c498c0776701040d52a' && isset($pass)==true) {
            $status = true;
            $_SESSION["status"]   = $status;
            $_SESSION['login']    = $login;
            $_SESSION['password'] = $pass;
        }
        if ($status==true) {
            header("Location: upload.php");
        }
        else { 
            echo "please login first";
        }
    ?>
</body>
</html>
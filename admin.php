
<?php 
    session_start();
    $_SESSION["status"];
    /* if ($_SESSION['status']==true) {
        header("Location: admin/admin.php");
    } */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href=".\resources\css\main.css">
</head>
<body>
    
    <div class ="main">
        <form action="admin.php" method="POST">
            <h3>Enter login and password</h3>
            <input class="form-control"type="text" name="login">
            <input class="form-control"type="password" name="password">

            <?php 
                error_reporting(0);
                $login  = $_POST['login'];//emson
                $pass   = $_POST['password'];//brewerypassword
                $status = false;
                if (md5($pass)==='6c1afb8be9068c9931bb5d5292795c7c' && md5($login)=='04417c12a95b4c498c0776701040d52a' && isset($pass)==true) {
                    $status = true;
                    $_SESSION["status"]   = true;
                    $_SESSION['login']    = $login;
                    $_SESSION['password'] = $pass;
                }
                if ($status==true) {
                    header("Location: upload.php");
                }
                else { 
                    echo "<h4>please login first</h4>";
                }
            ?>

            <input type="submit" value="Login" style="width:209px">
        </form>
        
    
        <?php 
        
            if ($_SESSION["status"]==true) {
                echo "Logged";
            }
        ?>
    

       
    </div>
</body>
</html>
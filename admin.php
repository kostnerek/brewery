<?php 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="resources/css/upload.css">
    <link rel="stylesheet" href="resources/css/admin.css">
    <link rel="icon" type="image/ico" href="etc/favicon.ico">
    <title>Admin</title>
</head>

<body>
    <div class="center">
        <form action="admin.php" method="post" >
            <input type='text' name='username' placeholder="username" required>
            <input style='border-bottom: 1px solid black' type='password' name='password' placeholder="password" required><br>
            <?php 
                //error_reporting(0);
                include('etc/config.php');
                include('etc/backup.php');
                $conn = mysqli_connect($server, $user, $password, $db);
                $backup = new backup($conn);
            
                if (isset($_POST['username']) && isset($_POST['password'])) {
                    if($_POST['username']=='arduino' && $_POST['password']!=" ") {
                        echo "<meta http-equiv=\"refresh\" content=\"0;url=api/index.php\">";
                    }
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    if ($username != "" && $password != "") {
                        $sql = "SELECT * FROM `users` WHERE `username` = '{$username}'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                if ($username == $row['username'] && md5($password) == $row['password']) {
                                    $v1=$username;
                                    $v2=$row['group'];
                                
                                    echo "<meta http-equiv=\"refresh\" content=\"0;url=login.php?u={$v1}&g={$v2}\">";
                                }
                            }
                        } 
                        echo "Wrong login/password<br>";
                    }
                }
            ?>
            <input type='submit' value='login' style='z-index: -10; position: inherit;'>
        </form>
    </div>
</body>
</html>
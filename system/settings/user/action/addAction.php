<?php 
    session_start();
    if ($_COOKIE['logged']!=true) {
        header("Location: ../../../../admin.php");
    }
    include('../../../../etc/config.php');
    $conn = mysqli_connect($server, $user, $password, $db);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../../resources/css/upload.css">
    <link rel="stylesheet" href="../../../../resources/css/addUser.css">
    <link rel="icon" type="image/ico" href="../../../../etc/favicon.ico">
    <title>Users</title>
</head>

<body>
    <div class="center">
    <?php include('../../../../etc/navbar.php')?>

        <div class='adduser'>
            <form method="post" action="../post/addPost.php">
                <input type='text' name='username' placeholder="username" required>
                <input type='password' name='password' id='pass1' placeholder="password" required>
                <input type='password' name='password2' id='pass2' oninput='checkMatch()' placeholder="repeat password" required>
                
                <select name='group'>
                    <option value='admin'>admin</option>
                    <option value='user'>user</option>
                </select>
                <input type='submit' id='submit' value="Add user">
            </form>
            <p id='error' style='color:black; opacity:0'>Password doesn't match</p>
        </div>
        <script>

            function checkMatch()
            {
                var pass1 = document.getElementById('pass1').value
                var pass2 = document.getElementById('pass2').value

                if (pass1 != pass2) {
                    document.getElementById('error').style='opacity: 1';
                    document.getElementById('submit').style='opacity: 0';
                }
                else {
                    document.getElementById('error').style='opacity: 0';
                    document.getElementById('submit').style='opacity: 1';
                }
            }
        </script>

            

    </div>

    

</body>
</html>
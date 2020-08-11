<?php 
session_start();
if ($_COOKIE['logged']!=true) {
    header("Location: ../../admin.php");
}
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
    <link rel="stylesheet" href="../../resources/css/upload.css">
    <link rel="stylesheet" href="../../resources/css/settings.css">
    <link rel="icon" type="image/ico" href="../../etc/favicon.ico">
    <title>Settings</title>
</head>

<body>
    <div class="center">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn" onclick="window.location.href='../system.php'">System</button>
            <button type="button" class="btn" onclick="window.location.href='../../list/list.php'">List</button>
            <button type="button" class="btn"
                onclick="window.location.href='../../brewery/brewery.php'">Brewery</button>
            <button type="button" class="btn"
                onclick="window.location.href='../../stats/stats.php?select=beers'">Stats</button>  
            <button type='button' class="btn fa fa-sign-out" style="color: black; font-size:25px; width:1%" onclick="window.location.href='../../logout.php'"></button>
            
        </div>

        <div class='options'>
            <button type="button" class="btn1 btn" onclick="window.location.href='action/fileIntegrity.php'">Check file system integrity</button>
            <button type="button" class="btn1 btn" style="border-bottom:0;" onclick="window.location.href='action/dbIntegrity.php'">Check database integrity</button>
        </div>

    </div>

    

</body>
</html>
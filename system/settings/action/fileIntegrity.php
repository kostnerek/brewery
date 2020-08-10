<?php 
    session_start();
    if ($_SESSION['logged']!=true) {
        header("Location: ../../../admin.php");
    }
    include('../../../etc/config.php');
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
    <link rel="stylesheet" href="../../../resources/css/upload.css">
    <link rel="stylesheet" href="../../../resources/css/settings.css">
    <link rel="icon" type="image/ico" href="../../../etc/favicon.ico">
    <title>Settings</title>
</head>

<body>
    <div class="center">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn" onclick="window.location.href='../../system.php'">System</button>
            <button type="button" class="btn" onclick="window.location.href='../../import/import.php'">Import</button>
            <button type="button" class="btn" onclick="window.location.href='../../export/export.php'">Export</button>
            <button type="button" class="btn" onclick="window.location.href='../../settings/settings.php'">Settings</button>
            <button type="button" class="btn" onclick="window.location.href='../../../list/list.php'">List</button>
            <button type='button' class="btn fa fa-sign-out" style="color: black; font-size:25px; width:1%" onclick="window.location.href='../../../logout.php'"></button>
        </div>
    <table>
        <tr>
            <td><b>BEER NAME</b></td>
            <td><b>BREWERY</b></td>
            <td><b>IMAGE ERROR</b></td>
            <td><b class='fa fa-photo' style="font-size: 25px;"></b></td>
        </tr>
        <?php 
            $sql = "SELECT * FROM beers";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
               while($row = $result->fetch_assoc()) {
                   echo "<tr>";
                   if(!file_exists('../../../'.$row['img_src'])) {
                       echo "<td>{$row['beer_name']}</td>";
                       echo "<td>{$row['brewery']}</td>";
                       echo "<td>error</td>";
                       echo "<td>";
                       echo "<form action='../../../list/action/editAction.php' method='post'/>";
                       echo "<button style='font-size: 36px; color:black' class='action fa' value='{$row['id']}' type='submit' name='id'>
                                 &#xf044;
                            </button>";
                       echo "</form>";
                       echo "</td>";
                   }
                   echo "</tr>";
               }
            } 
        ?>
    </table>

    </div>

    

</body>
</html>
<?php 
session_start();
if ($_COOKIE['logged']!=true || $_COOKIE['group'] != 'admin') {
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
    <link rel="icon" type="image/ico" href="../../etc/favicon.ico">
    <title>List brewery</title>
</head>

<body>
    <?php 
        include('../../etc/config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
    ?>

    <div class="center">
    <?php include('../../etc/navbar.php')?>
        <table>
            <tr>
                <th>ID</th>
                <th>NAME</th>
            </tr>
            <?php 
            $sql = "SELECT * FROM `breweries` WHERE id='{$_POST['id']}'";
            $result = $conn->query($sql);
            $breweryName;
            if ($result->num_rows > 0) {//brewery array
                while($row = $result->fetch_assoc()) {
                    $breweryName = $row['name'];
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "</tr>";
                }
            }
        ?>
        </table>
        <form action="../post/editPost.php" method="post">
            <input type='text' style="opacity: 0" name='id' value="<?php echo $_POST['id'] ?>">
            <input class="form-control" type="text" name="brewery" value="<?php echo $breweryName ?>">
            <input type='submit' value='Change'>
        </form>




    </div>
</body>

</html>
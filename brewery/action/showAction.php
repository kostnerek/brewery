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
    <link rel="icon" type="image/ico" href="../../etc/favicon.ico">
    <title>Show beers</title>
</head>

<body>
    <?php 
        include('../../etc/config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
    ?>

    <div class="center">
    <?php include('../../etc/navbar.php')?>
        <table>
            <?php
                if (isset($_POST['id'])) {
                    $breweryName = ucfirst(str_replace("_"," ",$_POST['id']));
                    echo "<h3>Beers from {$breweryName}</h3>";
                }
                else {
                    $breweryName = ucfirst(str_replace("_"," ",$_GET['id']));
                    echo "<h3>Beers from {$breweryName}</h3>";
                }
            ?>
            
            <tr>
                <th>ID</th>
                <th>BEER NAME</th>
                <th>BREWERY NAME</th>
                <th>COUNTRY OF ORIGIN</th>
                <th>PRODUCTION DATE</th>
                <th>IMG SRC</th>
            </tr>
            <?php 
           
            if (isset($_POST['id'])) {
                $sql = "SELECT * FROM `beers` WHERE brewery = '{$_POST['id']}'";
            }
            else {
                $sql = "SELECT * FROM `beers` WHERE brewery = '{$_GET['id']}'";
            }
           
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<form method='post' action='../../list/action/imgShowAction.php'>";
                    echo "  <td><button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$row['id']}</td>";
                    echo "  <td class='beer_name'><button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$row['beer_name']}</td>";
                    echo "  <td><button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$row['brewery']}</td>";
                    echo "  <td><button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$row['country']}</td>";
                    echo "  <td><button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$row['production_date']}</td>";
                    $stSlice = substr($row['img_src'],0,14);
                    $ndSlice = substr($row['img_src'],14,strlen($row['img_src']));
                    echo    "<td class='smallerSrc'>
                                <button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$stSlice}<br>{$ndSlice}</button>
                            </td>";

                    echo "</form>";
                    echo "</tr>";
                }
            }
            
        ?>
        </table>
        
    </div>
    <script>
        var length = document.getElementsByClassName('beer_name').length
        for (let i=0; i < length; i++) {
            var beer = document.getElementsByClassName('beer_name')[i]
            var beername = beer.textContent;

            if (beername.length >= 22) {
                console.log(beer)
                beer.style.fontSize = '10px';
            }
        }
    </script>
</body>

</html>
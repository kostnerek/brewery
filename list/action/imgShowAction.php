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
    <link rel="stylesheet" href="../../resources/css/upload.css">
    <link rel="icon" type="image/ico" href="../../resources/img/favicon.ico">
    <title>Image</title>
</head>

<body>
    <div class="center">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn" onclick="window.location.href='../../system/system.php'">System</button>
                <button type="button" class="btn" onclick="window.location.href='../list.php'">List</button>
                <button type="button" class="btn" onclick="window.location.href='../../brewery/brewery.php'">Brewery</button>
                <button type="button" class="btn" onclick="window.location.href='../stats/stats.php?select=beers'">Stats</button>
            </div>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>BEER NAME</th>
                        <th>BREWERY NAME</th>
                        <th>COUNTRY OF ORIGIN</th>
                        <th>PRODUCTION DATE</th>
                        <th>IMG SRC</th>
                    </tr>

                    <?php
                        include('../../etc/config.php');
                        $conn = mysqli_connect($server, $user, $password, $db);

                        $sql = "SELECT * FROM `beers` WHERE img_src = '{$_POST['img_src']}'";

                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                    echo "<td name='{$row['id']}'>{$row['id']}</td>";
                                    echo "<td>{$row['beer_name']}</td>";
                                    echo "<td>
                                            <form action='../../brewery/action/showAction.php' method='post'>
                                                <button class='action' value='{$row['brewery']}' type='submit' name='id'>{$row['brewery']}</button>
                                            </form>
                                        </td>";
                                    echo "<td>{$row['country']}</td>";
                                    echo "<td>{$row['production_date']}</td>";
                                    $stSlice = substr($row['img_src'],0,14);
                                    $ndSlice = substr($row['img_src'],14,strlen($row['img_src']));
                                    echo "<td>$stSlice<br>$ndSlice</td>";
                                echo "</tr>";
                            }
                        } 
                        echo "</table>";
                        if (isset($_POST['img_src'])) {
                            echo "<img width='640' height='360' src='../../{$_POST['img_src']}'>";
                        }
                    ?>
                </table>
    </div>

</body>

</html>
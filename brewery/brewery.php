<?php 
session_start();
if ($_COOKIE['logged']!=true) {
    header("Location: ../admin.php");
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
    <link rel="stylesheet" href="../resources/css/upload.css">
    <link rel="icon" type="image/ico" href="../etc/favicon.ico">
    <title>List</title>
</head>

<body>
    <?php 
        include('../etc/config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
    ?>

    <div class="center">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn" onclick="window.location.href='../system/system.php'">System</button>
                <button type="button" class="btn" onclick="window.location.href='../list/list.php'">List</button>
                <button type="button" class="btn" onclick="window.location.href='brewery.php'">Brewery</button>
                <button type="button" class="btn" onclick="window.location.href='../stats/stats.php?select=beers'">Stats</button>
                <button type='button' class="btn fa fa-sign-out" style="color: black; font-size:25px; width:1%" onclick="window.location.href='../logout.php'"></button>
            </div>
    <h3>List of all breweries</h3>
    <table id='main'>
        <tr>
            <?php 
                echo "<form method='post' action='brewery.php'>";

                echo "<th>           
                        <button value='id_up'   type='submit' name='sort'class='fa sort fa-arrow-up'></button>
                        <button value='id_down' type='submit' name='sort'class='fa sort fa-arrow-down'></button></th>";
                echo "<th>NAME       
                        <button value='name_up'   type='submit' name='sort'class='fa sort fa-arrow-up'></button>
                        <button value='name_down' type='submit' name='sort'class='fa sort fa-arrow-down'></button></th>";
                echo "<th>NUMBER OF BEERS      
                        <button value='number_up'   type='submit' name='sort'class='fa sort fa-arrow-up'></button>
                        <button value='number_down' type='submit' name='sort'class='fa sort fa-arrow-down'></button></th>";
                echo "</form>";
            ?>
            <th colspan='2' style='border:0;'>ACTION</th>
        </tr>
        <?php
            
            $sql = "SELECT * FROM `breweries`";
            
            $result = $conn->query($sql);
            $resultArray = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($resultArray, array(
                                'id'=>$row['id'],
                                'name'=>$row['name'],
                                'number'=>countBeers($row['name'], $conn)));
                }
            } 
            function countBeers($name, $conn) {
                $sql = "SELECT * FROM `beers` WHERE brewery='{$name}'";
                $result = $conn->query($sql);
                return $result->num_rows;
            }

            if (isset($_POST['sort'])) {
                switch($_POST['sort']){
                    case 'id_up':
                        {
                            usort($resultArray, function ($item1, $item2) {
                                return $item1['id'] <=> $item2['id'];
                            });
                            break;
                        }
                    case 'id_down':
                        {
                            usort($resultArray, function ($item1, $item2) {
                                return $item2['id'] <=> $item1['id'];
                            });
                            break;
                        }
                    case 'name_up':
                        {
                            usort($resultArray, function ($item1, $item2) {
                                return $item1['name'] <=> $item2['name'];
                            });
                            break;
                        }
                    case 'name_down':
                        {
                            usort($resultArray, function ($item1, $item2) {
                                return $item2['name'] <=> $item1['name'];
                            });
                            break;
                        }
                    case 'number_up':
                        {
                            usort($resultArray, function ($item1, $item2) {
                                return $item1['number'] <=> $item2['number'];
                            });
                            break;
                        }
                    case 'number_down':
                        {
                            usort($resultArray, function ($item1, $item2) {
                                return $item2['number'] <=> $item1['number'];
                            });
                            break;
                        }
                }
            }
            
            /* echo "<pre>";
            var_dump($resultArray[0]['id']);
            die(); */

            for ($i=0; $i<count($resultArray); $i++) {
                    echo "<tr>";
                    echo "<td name='{$resultArray[$i]['id']}'>{$resultArray[$i]['id']}</td>";
                    echo "<td>
                            <form action='action/showAction.php' method='post'>
                                <button class='action' value='{$resultArray[$i]['name']}' type='submit' name='id'>{$resultArray[$i]['name']}</button>
                            </form>
                          </td>";

                    echo "<td>";
                    echo countBeers($resultArray[$i]['name'], $conn);
                    echo "</td>";

                    echo "<td>
                            <form action='action/editAction.php' method='post'>
                                <button style='color: black; font-size: 36px;' class='action fa' value='{$resultArray[$i]['id']}' type='submit' name='id'>&#xf044;</button>
                            </form>
                        </td>";
                    echo "<td>
                            <form action='action/deleteAction.php' method='post'>
                                <button style='color: black; font-size: 36px;' class='action fa'  value='{$resultArray[$i]['id']}' type='submit' name='id'>&#xf00d;</button>
                            </form>
                        </td>";
                    echo "</tr>";
            }

        ?>
    </table>
        <?php 
            if (isset($_GET['error'])) {
                echo "<h4>Can't list because brewery already exist</h4>";
            }
        ?>
    </form>
    </div>
</body>

</html>
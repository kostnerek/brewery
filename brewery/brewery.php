<?php 
session_start();
if ($_SESSION['logged']!=true) {
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

        $sql = "SELECT * FROM `beers`";
        $result = $conn->query($sql);

        $sql = "SELECT * FROM `breweries`";
        $breweries = $conn->query($sql);

        $beerArray=[];
        $breweriesArray=[];
        
        if ($breweries->num_rows > 0) {//brewery array
            while($row = $breweries->fetch_assoc()) {
                array_push($breweriesArray, $row['name']);
            }
        }
        if ($result->num_rows > 0) {//beer array
            while($row = $result->fetch_assoc()) {
                if (in_array($row['brewery'], $breweriesArray)) {
                    unset($breweriesArray[array_search($row['brewery'], $breweriesArray)]);
                }
            }
        }

        $breweriesArray = array_merge($breweriesArray);

        if (!empty($breweriesArray)) {
            for ( $i = 0; $i < count($breweriesArray); $i++) {
                if (is_dir('../resources/img/'.$breweriesArray[$i])) {
                    if (is_dir_empty('../resources/img/'.$breweriesArray[$i])) {
                        rmdir('../resources/img/'.$breweriesArray[$i]);
                    }
                    else {
                        deleteDir('resources/img/'.$breweriesArray[$i]);
                    }
                }
                $sql = "DELETE FROM `breweries` WHERE name='{$breweriesArray[$i]}'";
                $conn->query($sql);
            }
        }

        function deleteDir($dirPath) {
            if (! is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }

        function is_dir_empty($dir) {
            if (!is_readable($dir)) {
                return NULL;
            }
            else {
                return (count(scandir($dir)) == 2);
            }
        }
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
            <td>ID</td>
            <td>NAME</td>
            <td>NUMBER OF BEERS</td>
            <td colspan='2'>ACTION</td>
        </tr>
        <?php
            if (!isset($_POST['sort'])) {
                $sql = "SELECT * FROM `breweries`";
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td name='{$row['id']}'>{$row['id']}</td>";
                    echo "<td>
                            <form action='action/showAction.php' method='post'>
                                <button class='action' value='{$row['name']}' type='submit' name='id'>{$row['name']}</button>
                            </form>
                          </td>";

                    echo "<td>";
                    echo countBeers($row['name'], $conn);
                    echo "</td>";

                    echo "<td>
                            <form action='action/editAction.php' method='post'>
                                <button style='color: black; font-size: 36px;' class='action fa' value='{$row['id']}' type='submit' name='id'>&#xf044;</button>
                            </form>
                        </td>";
                    echo "<td>
                            <form action='action/deleteAction.php' method='post'>
                                <button style='color: black; font-size: 36px;' class='action fa'  value='{$row['id']}' type='submit' name='id'>&#xf00d;</button>
                            </form>
                        </td>";
                    echo "</tr>";
                    echo "</form>";
                }
            } 

            function countBeers($name, $conn) {
                $sql = "SELECT * FROM `beers` WHERE brewery='{$name}'";
                $result = $conn->query($sql);
                return $result->num_rows;
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
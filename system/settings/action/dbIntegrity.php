<?php 
    session_start();
    if ($_COOKIE['logged']!=true) {
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
    <title>DB Integrity</title>
</head>

<body>
    <div class="center">
    <?php include('../../../etc/navbar.php')?>

        <table>
        <tr>
            <th>BREWERY</td>
            <th>ERROR</th>
        </tr>
        <?php
            $beerArray=array();
            $breweryArray=array();

            $sql = "SELECT brewery FROM beers";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                   array_push($beerArray, $row['brewery']);
                }
            } 

            $sorted = array_values(array_unique($beerArray));
            sort($sorted);

            $sql = "SELECT name FROM breweries";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                   array_push($breweryArray, $row['name']);
                }
            } 
            sort($breweryArray);

            
            if (checkForEmptyBreweries($sorted, $breweryArray) && checkForMissingBrewery($sorted, $breweryArray)) {
                echo "<tr><td colspan='2' style='font-size:25px'><B>Everything ok</B></td></tr>";
            }

            function checkForEmptyBreweries($beerArray, $breweryArray)
            {
                $counter=0;
                for ($i=0; $i<count($breweryArray); $i++) {
                    if ($breweryArray[$i] != $beerArray[$i]) {
                        $counter++;
                        if ($counter == 1) {
                            echo "<tr>";
                            echo "<td colspan='2' style='font-size:25px'><b>Empty breweries</b></td>";
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td>{$breweryArray[$i]}</td>";
                        echo "<td>is empty</td>";
                        echo "</tr>";
                    }
                }
                if ($counter!=0) {
                    return false;
                }
                return true;
            }

            function checkForMissingBrewery($beerArray, $breweryArray)
            {
                
                $counter=0;
                for ($i=0; $i<count($beerArray); $i++) {
                    if ($beerArray[$i] != $breweryArray[$i]) {
                        $counter++;
                        if ($counter == 1) {
                            echo "<tr>";
                            echo "<td colspan='2' style='font-size:25px'><b>Missing brewery</b></td>";
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td>{$beerArray[$i]}</td>";
                        echo "<td>doesn't have corresponding entry in brewery table</td>";
                        echo "</tr>";
                    }
                }
                if ($counter!=0) {
                    return false;
                }
                return true;
            }


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
                            deleteDir('../resources/img/'.$breweriesArray[$i]);
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
    </table>

    </div>
</body>
</html>
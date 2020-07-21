
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
    <link rel="stylesheet" href="resources/css/upload.css">
    <link rel="icon" type="image/ico" href="resources/img/favicon.ico">
    <title>List</title>
</head>

<body>
    <?php 
        include('config.php');
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
                if (is_dir('resources/img/'.$breweriesArray[$i])) {
                    if (is_dir_empty('resources/img/'.$breweriesArray[$i])) {
                        rmdir('resources/img/'.$breweriesArray[$i]);
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
        <form action="list.php" method="POST" id="main-form" enctype="multipart/form-data">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn" onclick="window.location.href='system.php'">System</button>
                <button type="button" class="btn" onclick="window.location.href='list.php'">List</button>
                <button type="button" class="btn" onclick="window.location.href='brewery.php'">Brewery</button>
                <button type="button" class="btn" onclick="window.location.href='stats.php?select=beers'">Stats</button>
        </form>
    </div>
    <h3>List of all beers</h3>
    <table id='main'>
        <tr>
            <?php 
                    echo "<form method='post' action='list.php'>";

                    echo "<th><button class='sort' value='id' type='submit'        name='sort'>ID</button></th>";
                    echo "<th><button class='sort' value='beer_name' type='submit' name='sort'>BEER NAME</button></th>";
                    echo "<th><button class='sort' value='brewery' type='submit'   name='sort'>BREWERY NAME</button></th>";
                    echo "<th><button class='sort' value='country' type='submit'   name='sort'>COUNTRY OF ORIGIN</button></th>";
                    echo "<th><button class='sort' value='prodDate' type='submit'  name='sort'>PRODUCTION DATE</button></th>";
                    echo "<th>IMG SRC</th>";

                    echo "</form>";
                    echo "<th colspan='2'>ACTION</th>";
                    ?>
        </tr>
        <?php
                    if (isset($_POST['sort'])) {
                        switch($_POST['sort']) {
                            case 'id':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `id` DESC";
                                    break;
                                }
                            case 'beer_name':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `beer_name` ASC";
                                    break;
                                }
                            case 'country':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `country` ASC";
                                    break;
                                }
                            case 'brewery':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `brewery` ASC";
                                    break;
                                }
                            case 'prodDate':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `production_date` DESC";
                                    break;
                                }
                            default:
                                {
                                    $sql = "SELECT * FROM `beers`";
                                    break;
                                }
                        }
                    }
                   
                    
                    if (!isset($_POST['sort'])) {
                        $sql = "SELECT * FROM `beers`";
                    }

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td name='{$row['id']}'>{$row['id']}</td>";
                            echo "<td style='font-size:12px;'>{$row['beer_name']}</td>";
                            echo "<td>{$row['brewery']}</td>";
                            echo "<td>{$row['country']}</td>";
                            echo "<td>{$row['production_date']}</td>";
                            $breweryLength = strlen($row['brewery']);
                            $stSlice = substr($row['img_src'],0,15+$breweryLength);
                            $ndSlice = substr($row['img_src'],15+$breweryLength,strlen($row['img_src']));
                            echo "<td class='smallerSrc'>
                                    <form method='post' action='actions/imgShowAction.php'>
                                     <button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$stSlice}<br>{$ndSlice}</button>
                                    </form>
                                    </td>";
                                    
                            echo "<td>
                                    <form action='actions/editAction.php' method='post'>
                                        <button  style='font-size: 36px; color:black' class='action fa' value='{$row['id']}' type='submit' name='id'>
                                            &#xf044;
                                        </button>
                                    </form>
                                </td>";

                            echo "<td>
                                    <form action='actions/deleteAction.php' method='post'>
                                        <button style='font-size: 36px; color:black' class='action fa' value='{$row['id']}' type='submit' name='id'>&#xf00d;</button>
                                    </form>
                                </td>";

                            echo "</tr>";
                            echo "</form>";
                        }
                    } 
                ?>
    </table>
    </form>
    </div>
</body>

</html>
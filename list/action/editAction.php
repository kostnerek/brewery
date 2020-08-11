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
    <title>List</title>
</head>

<body>

    <script>
        function setId(id) {
            idValue = id;
        }
    </script>

    <?php 
        include('../../etc/config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $sql = "SELECT * FROM beers WHERE id={$id}";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $beerName    = $row['beer_name'];
                    $breweryName = $row['brewery'];
                }
            } 
            echo "<script>window.onload=setId({$id})</script>";

        }
    ?>


    <div class="center">
        <form action="../post/editPost.php" method="POST" id="main-form" enctype="multipart/form-data">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn" onclick="window.location.href='../../system/system.php'">System</button>
                <button type="button" class="btn" onclick="window.location.href='../list.php'">List</button>
                <button type="button" class="btn" onclick="window.location.href='../../brewery/brewery.php'">Brewery</button>
                <button type="button" class="btn" onclick="window.location.href='../../stats/stats.php?select=beers'">Stats</button>
                <button type='button' class="btn fa fa-sign-out" style="color: black; font-size:25px; width:1%" onclick="window.location.href='../../logout.php'"></button>
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
                <tr>
                    <?php 
                        $sql = "SELECT * FROM `beers` WHERE id='{$id}'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $countryOption = $row['country'];
                                $breweryOption = $row['brewery'];
                                echo  "<td>{$row['id']}</td>";
                                echo  "<td>{$row['beer_name']}</td>";
                                echo  "<td>{$row['brewery']}</td>";
                                echo  "<td>{$row['country']}</td>";
                                echo  "<td>{$row['production_date']}</td>";
                                $stSlice = substr($row['img_src'],0,14);
                                $ndSlice = substr($row['img_src'],14,strlen($row['img_src']));
                                echo "<td>{$stSlice}<br>{$ndSlice}</td>";
                            }
                        } 

                    ?>
                </tr>
            </table>

            <input class="form-control" type="text" name="beer_name" value="<?php echo $beerName?>" onclick="this.value=''">
            <select id="country" class="custom-select" name="country">
                <?php
                    $sql = "SELECT id, name FROM countries";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if( $row['name'] = $countryOption) {
                                echo  "<option value={$row["name"]} selected='selected'>{$row["name"]}</option>";
                                continue;
                            }
                            echo  "<option value={$row["name"]}>{$row["name"]}</option>"; 
                        }
                    } 
                ?>
            </select></br>

            <label>New brewery?</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="option" onclick="brewerySet(1)" id="exampleRadios1"
                    value="1" >
                <label class="form-check-label" for="exampleRadios1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="option" onclick="brewerySet(0)" id="exampleRadios2"
                    value="0" checked>
                <label class="form-check-label" for="exampleRadios2">No</label>
            </div>
            <div class="input-group">
                <input style="display:none" type="text" class="form-control" name="breweriesOne" id="newBrewery"
                    value="Enter new brewery" onclick="this.value=''">
                <select style="display:block" id="breweries" class="custom-select" name="breweries">
                    <?php
                        $sql = "SELECT id, name FROM breweries";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $name = str_replace('_',' ',ucfirst($row['name']));
                                if ($row['name'] == $breweryOption) {
                                    echo  "<option value={$row["name"]} selected='selected'>{$name}</option>";
                                }
                                echo  "<option value={$row["name"]}>{$name}</option>";
                            }
                        } 
                    ?>
                </select>
                <select name="date" class="custom-select">
                    <?php 
                    $idCounter=1;
                    for ($i=2020; $i>=1950; $i--) {
                        echo "<option onclick=yearSet({$i}) value={$i}>{$i}</option>"; 
                    }
                ?>
                </select>
            </div>
            <!--  --><input name='id' type='text' id="idContainer" style="opacity: 0; height: 0px"><br>
            <label>Change photo?</label>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="photo" id="exampleRadios1" value="1"
                    onclick="photoSet(1)">
                <label class="form-check-label" for="exampleRadios1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="photo" id="exampleRadios2" value="0"
                    onclick="photoSet(0)" checked>
                <label class="form-check-label" for="exampleRadios2">No</label>
            </div>

            <input style='color: black; border: 2px black solid; background-color: #861821; margin-top:1%; margin-bottom:1%; border-radius:10px; display: none' id='file'  type="file" name="file"><br>
            <input style='color: black; border: 2px black solid; background-color: #861821; margin-top:1%; margin-bottom:1%; border-radius:10px' type="submit" value="Send" name="submit">
        </form>
    </div>

</body>

</html>
<script>

</script>
<script>
    idContainer = document.getElementById("idContainer");
    console.log(idContainer.getAttribute("type"));
    idContainer.setAttribute('value', idValue);


    function brewerySet(check) {
        var list = document.getElementById('breweries');
        var oneSelect = document.getElementById('newBrewery');
        if (check == 1) {
            console.log(check);
            list.setAttribute('style', 'display:none')
            oneSelect.setAttribute('style', 'display:block')
        } else {
            console.log(check);
            list.setAttribute('style', 'display:block')
            oneSelect.setAttribute('style', 'display:none')
        }
    }

    function photoSet(check) {
        var file = document.getElementById('file');
        console.log(check);
        if (check == 1) {
            file.setAttribute('style', 'display:block')
        } else {
            file.setAttribute('style', 'display:none')
        }
    }
</script>
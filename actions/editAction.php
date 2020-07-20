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
    <link rel="stylesheet" href="../resources/css/upload.css">
    <link rel="icon" type="image/ico" href="../resources/img/favicon.ico">
    <title>Edit</title>
</head>

<body>

    <script>
        function setId(id) {
            idValue = id;
        }
    </script>

    <?php 
        include('../config.php');
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
                <button type="button" class="btn" onclick="window.location.href='../upload.php'">Upload</button>
                <button type="button" class="btn" onclick="window.location.href='../edit.php'">Edit</button>
                <button type="button" class="btn" onclick="window.location.href='../brewery.php'">Brewery</button>
                <button type="button" class="btn" onclick="window.location.href='../stats.php?select=beers'">Stats</button>
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
                            echo  "<option value={$row["name"]}>{$row["name"]}</option>";
                        }
                    } 
                ?>
            </select></br>

            <label>New brewery?</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="option" onclick="brewerySet(1)" id="exampleRadios1"
                    value="1" checked>
                <label class="form-check-label" for="exampleRadios1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="option" onclick="brewerySet(0)" id="exampleRadios2"
                    value="0">
                <label class="form-check-label" for="exampleRadios2">No</label>
            </div>
            <div class="input-group">
                <input style="display:block" type="text" class="form-control" name="breweriesOne" id="newBrewery"
                    value="Enter new brewery" onclick="this.value=''">
                <select style="display:none" id="breweries" class="custom-select" name="breweries">
                    <?php
                        $sql = "SELECT id, name FROM breweries";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $name = str_replace('_',' ',ucfirst($row['name']));
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

            <input id='file' style="display: none" type="file" name="file"><br>
            <input type="submit" value="Send" name="submit">
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

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
    <link rel="stylesheet" href="resources/css/upload.css">
    <link rel="icon" type="image/ico" href="resources/img/favicon.ico">
    <title>System</title>
</head>

<body>
    <?php 
        include('config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
    ?>
    <div class="center">
        <form action="post/addPost.php" method="POST" id="main-form" enctype="multipart/form-data">
            <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn" onclick="window.location.href='system.php'">System</button>
                <button type="button" class="btn" onclick="window.location.href='import.php'">Import</button>
                <button type="button" class="btn" onclick="window.location.href='export.php'">Export</button>
                <button type="button" class="btn" onclick="window.location.href='list.php'">List</button>
                
            </div>
            <h3>Add new beer</h3>
            <?php
                if (isset($_GET['error'])) {
                    if($_GET['error']=='unset') {
                        echo "<h4>Provide all data</h4>";
                    }
                }
            ?>

            <input class="form-control" type="text" name="beer_name" placeholder="Beer name">
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
            <input type="file" name="file">
            <input type="submit" value="Send" name="submit">
        </form>
    </div>
    <script>
        window.onload = brewerySet(1);

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
    </script>

</body>

</html>
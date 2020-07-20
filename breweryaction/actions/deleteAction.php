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
    <title>Delete Brewery</title>
</head>

<body>
    <?php 
        include('../../config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
    ?>

    <div class="center">
        <form action="edit.php" method="POST" id="main-form" enctype="multipart/form-data">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn" onclick="window.location.href='../../upload.php'">Upload</button>
                <button type="button" class="btn" onclick="window.location.href='../../edit.php'">Edit</button>
                <button type="button" class="btn" onclick="window.location.href='../../brewery.php'">Brewery</button>
                <button type="button" class="btn" onclick="window.location.href='../../stats.php'">Stats</button>
            </div>
        </form>
        <?php 
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $sql = "SELECT * FROM `beers`";
            $result = $conn->query($sql);
    
            $sql = "SELECT * FROM `breweries` WHERE id='{$id}'";
            $breweries = $conn->query($sql);
            
            if ($breweries->num_rows > 0) {//brewery array
                while($row = $breweries->fetch_assoc()) {
                    $breweryCheck = $row['name'];
                }
            }
            $counter=0;
            if ($result->num_rows > 0) {//beer array
                while($row = $result->fetch_assoc()) {
                    if ($row['brewery'] == $breweryCheck) {
                       $counter++;
                    }
                }
            }

            echo "There are {$counter} beers connected to this brewery<br>Delete them and all data connected?<br>";

            echo "<div class='btn-group' id='yesno' role='group' aria-label='Basic example'>";
            echo "<form action='deleteAction.php' method='post'>";
            echo "   <button class='btn' id='yesno-btn' type='submit' value='{$id}'name='delId'>YES</button>";
            echo "</form>";
            echo "<button type=\"button\" class=\"btn\" onclick=\"window.location.href='../../brewery.php'\">NO</button>";
            echo "</div>";
        }
        if (isset($_POST['delId'])) {
            $delId = $_POST['delId'];

            $sql = "SELECT * FROM `breweries` WHERE id='{$delId}'";
            $breweries = $conn->query($sql);
            
            if ($breweries->num_rows > 0) {//brewery array
                while($row = $breweries->fetch_assoc()) {
                    $breweryName = $row['name'];
                }
            }

            delete_files("../../resources/img/{$breweryName}");
            delete_beer_entry($breweryName, $conn);

            $sql = "DELETE FROM `breweries` WHERE id='{$delId}'";
            $conn->query($sql);
            
            echo "<meta http-equiv=\"refresh\" content=\"0;url=../../brewery.php\">";
        }

        function delete_files($target) {
            if(is_dir($target)){
                $files = glob( $target . '*', GLOB_MARK );
        
                foreach( $files as $file ){
                    delete_files( $file );      
                }
        
                rmdir( $target );
            } elseif(is_file($target)) {
                unlink( $target );  
            }
        }

        function delete_beer_entry($breweryName, $conn)
        {
            $sql = "SELECT * FROM `beers` WHERE brewery='{$breweryName}'";
            $breweries = $conn->query($sql);
            
            if ($breweries->num_rows > 0) {//brewery array
                while($row = $breweries->fetch_assoc()) {
                    $sql = "DELETE FROM `beers` WHERE id='{$row['id']}'";
                    $conn->query($sql);
                }
            }
        }

        ?>

    </div>
</body>

</html>
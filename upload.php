<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="brewery\resources\css\main.css">
    <title>Admin</title>
</head>
<body>
    <?php 
        include('config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
    ?>

    <form action="post.php" method="POST" id="main-form" enctype="multipart/form-data">
        
        Beer name
        <input type="text" value="Beer name" name="beer_name" onfocus="this.value=''">
        <br>
        Country
        <select id="country" name="country">
            <?php
                $sql = "SELECT id, name FROM countries";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo  "<option value={$row["name"]}>{$row["name"]}</option>";
                    }
                } 
            ?>
        </select>
        <br>
        New brewery?
        <select id="brewery" name="option" onclick="brewerySet()">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        
        <input  style="display:block" type="text" name="breweriesOne" id="newBrewery" onclick="this.value=''">
        <select style="display:none" id="breweries" name="breweries" >
            <?php
                $sql = "SELECT id, name FROM breweries";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo  "<option value={$row["name"]}>{$row["name"]}</option>";
                    }
                } 
            ?>
        </select>
        <script>
            function brewerySet()
            {
                var check = document.getElementById("brewery").value;
                var list = document.getElementById('breweries');
                var oneSelect = document.getElementById('newBrewery'); 
                if (check==1) {
                    console.log(check);
                    list.setAttribute('style', 'display:none')
                    oneSelect.setAttribute('style', 'display:block')
                }
                else {
                    console.log(check);
                    list.setAttribute('style', 'display:block')
                    oneSelect.setAttribute('style', 'display:none')
                }
            }
        </script>
        
        Date of production
        <select name="date">
            <?php 
                $idCounter=1;
                for ($i=2020; $i>=1950; $i--) {
                    echo "<option onclick=yearSet({$i}) value={$i}>{$i}</option>"; 
                }
            ?>
        </select>
        <input type="file" name="file"><br>
        
        <input type="submit" value="send" name="submit">
    </form>
</body>
</html>
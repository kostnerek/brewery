<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resources/css/main.css">
    <link rel="stylesheet" href="../resources/css/description.css">
    <title>Description</title>
</head>
<body>
    <div class='container'>
        <div class='main'>
            <?php 
                include('config.php');
                $conn = mysqli_connect($server, $user, $password, $db);
                $sql = "SELECT * FROM description";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<h2>".$row['header']."</h2>"."<br>";
                        echo $row['body'];
                    }
                } 
            ?>
        </div>
    </div>
</body>
</html>
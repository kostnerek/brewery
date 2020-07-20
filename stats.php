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
    <title>Stats</title>
</head>

<?php
    include('config.php');
    $conn = mysqli_connect($server, $user, $password, $db);
?>

<body>
    <div class="center">
        <form action="../post/importPost.php" method="POST" id="main-form" enctype="multipart/form-data">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn" onclick="window.location.href='upload.php'">Upload</button>
                <button type="button" class="btn" onclick="window.location.href='edit.php'">Edit</button>
                <button type="button" class="btn" onclick="window.location.href='brewery.php'">Brewery</button>
                <button type="button" class="btn" onclick="window.location.href='stats.php'">Stats</button>
            </div>

        <div id="chartContainer" style="height: 370px; width: 100%; background-color:black"></div>

    </div>

    <?php

        $sql = "SELECT * FROM `beers`";
        $result = $conn->query($sql);
        $allElementCount = $result->num_rows;

        function getAllBreweries($conn)
        {
            $data = array();
            $sql = "SELECT * FROM `breweries`";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    array_push($data, $row['name']);
                }
            } 
            return $data;
        }

        $allBrewieres = getAllBreweries($conn);

        $dataPoints = array();


        $allBeerArray=array();
        for ($i=0; $i<count($allBrewieres); $i++){

            $sql = "SELECT * FROM `beers` WHERE brewery='{$allBrewieres[$i]}'";
            $result = $conn->query($sql);
            $beerCount = $result->num_rows;
            $percentageBeer = $beerCount / $allElementCount * 100; 
            $breweryName = ucfirst(str_replace("_"," ",$allBrewieres[$i]));
            array_push($dataPoints, array("label"=>"{$breweryName}", "y"=>"{$percentageBeer}"));
        }
    ?>

    <script>
        window.onload = function() {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                backgroundColor: "#DB2B39",
                title: {
                    text: "Usage Share of Desktop Browsers"
                },
                subtitles: [{
                    text: "November 2017"
                }],
                data: [{
                    type: "pie",
                    yValueFormatString: "#,##0.00\"%\"",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
        }
    </script>
    
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>
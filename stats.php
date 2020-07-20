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
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn" onclick="window.location.href='upload.php'">Upload</button>
            <button type="button" class="btn" onclick="window.location.href='edit.php'">Edit</button>
            <button type="button" class="btn" onclick="window.location.href='brewery.php'">Brewery</button>
            <button type="button" class="btn" onclick="window.location.href='stats.php?select=beers'">Stats</button>
        </div>
        
        <div class="btn-group statbar" id="stat" role="group" aria-label="Basic example">
            <button type="button" class="btn" style="border-top: 2px solid black" onclick="window.location.href='stats.php?select=beers'"><b>BEERS</b></button>
            <button type="button" class="btn" style="border-top: 2px solid black"onclick="window.location.href='stats.php?select=country'"><b>COUNTRIES</b></button>
        </div>
        <?php
        if (isset($_GET['select'])) {
            switch ($_GET['select'])
            {
                default:
                case "beers":
                    {
                        echo "<h3>Beer Chart</h3>";
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
                            array_push($dataPoints, array("label"=>"{$breweryName}", "y"=>"{$percentageBeer}", "count"=>"{$beerCount}", "type"=>"beer"));
                        }
                    break;
                    }
                case "country":
                    {    
                        echo "<h3>Country Chart</h3>";
                        $countryArray = array();
                        $dataPoints   = array();

                        $sql = "SELECT * FROM `beers`";
                        
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                array_push($countryArray, $row['country']);
                            }
                        } 
                        $dataArray = array_count_values($countryArray);

                        $allEntriesCount=0;
                        foreach ($dataArray as $key => $value) {
                            $allEntriesCount+=$value;
                        }

                        foreach ($dataArray as $key => $value) {
                            $percentageCountry = $value / $allEntriesCount * 100;
                            array_push($dataPoints, array("label"=>"{$key}", "y"=>"{$percentageCountry}", "count"=>"{$value}", "type"=>"country"));
                        }

                    break;
                    }
            }
        }

        
    ?>
        <div id="chartContainerBeer" style="height: 370px; width: 100%"></div>

    </div>

    

    <script>
        window.onload = function beer() {
            var chart = new CanvasJS.Chart("chartContainerBeer", {
                animationEnabled: true,
                backgroundColor: "#DB2B39",
                data: [{
                    type: "pie",
                    click: onClick,
                    yValueFormatString: "#,##0.00\"%\"",
                    indexLabel: "{label} - {count}",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
            });
            chart.render();
        }
        function onClick(e) {
            var data = e.dataPoint.label.toLowerCase().replace(' ', '_');
            data.toLowerCase();
            data.replace(' ', '_');
            console.log(typeof(data));
            console.log(data);

            if (e.dataPoint.type=="beer") {
                window.location.href='breweryaction/actions/showAction.php?id='+data;
            }

            
           // alert(data);
	    }

    </script>
    
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>
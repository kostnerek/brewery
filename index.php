<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/ico" href="etc/favicon.ico">
    <link rel="stylesheet" href="resources/css/main.css">
    <title>Brewery</title>
</head>

<body>

    <?php
    include('etc/config.php');
    $conn = mysqli_connect($server, $user, $password, $db);
    ?>
    <div class='container'>
        <div class='stat-container'>
            <div id="chartContainerBeer" style="height: 470px; width: 100%"></div>
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
                    array_push($dataPoints, array("label"=>"{$breweryName}", "y"=>"{$percentageBeer}", "count"=>"{$beerCount}", "type"=>"beer"));
                }
            ?>
            <script>
                window.onload = function beer() {
                var chart = new CanvasJS.Chart("chartContainerBeer", {
                    animationEnabled: true,
                    backgroundColor: "transparent",
                    title:{
                        text: "Brewery stats"
                    },
                    data: [{
                        type: "pie",
                        indexLabel: "{y}",
                        yValueFormatString: "#,##0.00\"%\"",
                        indexLabelPlacement: "inside",
                        indexLabelFontColor: "#FFFFFFFF",
                       
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

                /* if (e.dataPoint.type=="beer") {
                    window.location.href='../brewery/action/showAction.php?id='+data;
                } */
            }
            </script>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
        </div>
        <div class="main">
            
            <script>
                var breweryFilter;
                var countryFilter;
                var yearFilter;
                var search;

                function brewerySet(breweryFilter) {
                    this.breweryFilter = breweryFilter;
                }

                function countrySet(countryFilter) {
                    this.countryFilter = countryFilter;
                }

                function yearSet(yearFilter) {
                    this.yearFilter = yearFilter;
                }

                function searchFunction(search) {
                    this.search = search
                }

                function submit() {
                    window.location.href = "index.php?search=" + search + "&brewery=" + breweryFilter + "&country=" +
                        countryFilter + "&year=" + yearFilter;
                }
                search = "undefined";
            </script>

            <?php

            error_reporting(0);
            $search    = $_GET["search"];
            $breweryId = $_GET["brewery"];
            $countryId = $_GET["country"];
            $year      = $_GET["year"];

            $searchCheck  = $_GET['search-checkbox'];
            $breweryCheck = $_GET['brewery-checkbox'];
            $countryCheck = $_GET['country-checkbox'];
            $yearCheck    = $_GET['year-checkbox'];

            /* echo "<pre>";
            var_dump(array( "searchbox"  => $_GET['search-checkbox'],
                            "brewerybox" => $_GET['brewery-checkbox'], 
                            "countrybox" => $_GET['country-checkbox'],
                            "yearbox"    => $_GET['year-checkbox']));
            echo "</pre>"; */

            if ($breweryCheck != "on" && $countryCheck != "on" && $yearCheck != "on") {
                $sql = "SELECT * FROM `beers` ORDER BY RAND()";
            }

             if ($breweryCheck == "on" && $countryCheck != "on" && $yearCheck != "on") { //brewery
                $sql = "SELECT * FROM beers 
                LEFT JOIN breweries ON breweries.name = beers.brewery 
                WHERE breweries.id = {$breweryId}";
            }
            if ($breweryCheck == "on" && $countryCheck != "on" && $yearCheck == "on") { //country   
                $sql = "SELECT * FROM beers 
                LEFT JOIN countries ON countries.name = beers.country 
                WHERE countries.id = {$countryId}";
            }
            if ($breweryCheck != "on" && $countryCheck != "on" && $yearCheck == "on") { //year
               
                $sql = "SELECT * FROM beers 
                WHERE beers.production_date = {$year}";
            }
            if ($breweryCheck == "on" && $countryCheck == "on" && $yearCheck != "on") { //brewery country
               
                $sql = "SELECT * FROM beers 
                LEFT JOIN countries ON countries.name = beers.country 
                LEFT JOIN breweries ON breweries.name = beers.brewery 
                WHERE countries.id = {$countryId} AND breweries.id = {$breweryId}";
            }
            if ($breweryCheck == "on" && $countryCheck != "on" && $yearCheck == "on") { //brewery year
                
                $sql = "SELECT * FROM beers 
                LEFT JOIN breweries ON breweries.name = beers.brewery 
                WHERE beers.production_date='{$year}' AND breweries.id = {$breweryId}";
            }
            if ($breweryCheck != "on" && $countryCheck == "on" && $yearCheck == "on") { //country year
                
                $sql = "SELECT * FROM beers 
                LEFT JOIN countries ON countries.name = beers.country 
                WHERE countries.id = {$countryId} AND beers.production_date = {$year}";
            }
            if ($breweryCheck == "on" && $countryCheck == "on" && $yearCheck == "on") { //all
                
                $sql = "SELECT * FROM beers 
                LEFT JOIN countries ON countries.name = beers.country 
                LEFT JOIN breweries ON breweries.name = beers.brewery 
                WHERE countries.id = {$countryId} AND breweries.id = {$breweryId} AND beers.production_date = {$year}";
            }

            if ($searchCheck == "on") {
                
                $sql = "SELECT * FROM beers 
                WHERE beers.beer_name 
                LIKE '%{$search}%'";
            } 
            /* if (!isset($searchCheck)) {
                $sql = "SELECT * FROM `beers` ORDER BY RAND()";
            } */

            if ($breweryCheck != "on" && $countryCheck != "on" && $yearCheck != "on" && $searchCheck != "on") { //all
                
                $sql = "SELECT * FROM `beers` ORDER BY RAND() LIMIT 1";
            }
            $result = $conn->query($sql);
            

            if (!$result = $conn->query($sql)) {
                echo "Error: " . $conn->error;
            } else {
                if ($result->num_rows > 0) {
                    echo "<div id=\"carouselExampleControls\" class=\"carousel slide\" data-ride=\"carousel\">";
                    echo "  <div class=\"carousel-inner\">";
                    $counter = 0;
                    while ($row = $result->fetch_assoc()) {
                        $name = ucfirst(str_replace('_', ' ', $row['beer_name']));
                        $brewery = ucfirst(str_replace('_', ' ', $row['brewery']));
                        if ($counter == 0) {
                            echo "    <div class=\"carousel-item active\">";
                            $counter++;
                        } else {
                            echo "    <div class=\"carousel-item\">";
                        }
                        echo "    <h2>{$name}</h2>";
                        echo "    <a>Brewery: {$brewery}<br>";
                        echo "    Country of origin: {$row["country"]}<br>";
                        echo "    Date of production: {$row["production_date"]}</a><br>";
                        echo "    <img src={$row["img_src"]} width='480' height='360'onerror=\"this.onerror=null; this.src='resources/error.png'; style=' filter: brightness(0%)'\">";
                        echo "    </div>";
                    }
                   /*   */
                        echo "  </div>";
                    if ($result->num_rows > 1) {
                        echo "<a class='carousel-control-prev' href='#carouselExampleIndicators' role='button' data-slide='prev'>";
                        echo "    <span class='carousel-control-prev-icon' aria-hidden='true'></span>";
                        echo "    <span class='sr-only'>Previous</span>";
                        echo "</a>";
                        echo "<a class='carousel-control-next' href='#carouselExampleIndicators' role='button' data-slide='next'>";
                        echo "    <span class='carousel-control-next-icon' aria-hidden='true'></span>";
                        echo "    <span class='sr-only'>Next</span>";
                        echo "</a>";
                    }
                        echo "</div>";
                    /*  */
                    
                } 
            }
            
            ?>
            <footer class="stamp">Made by: Filip Kostecki contact: filip.kostecki00@gmail.com</footer>
        
        </div>
        <div class="search-container ">
            <div class='filter-bar'>
            <form action="index.php" method='get'>
                <input type='text' placeholder="Search" class='form-input search' name='search'/><br>
                <input class="check search-checkbox" type="checkbox" name="search-checkbox" />
                
                <select name='brewery' class='form-input brewery'>
                        <?php 
                            $sql = "SELECT id, name FROM breweries";
                            $result = $conn->query($sql);
                            if ($result->num_rows == 0) {
                                echo  "<option>empty</option>";
                            }
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $name = str_replace('_', ' ', ucfirst($row['name']));
                                    echo  "<option onclick=brewerySet({$row["id"]}) value={$row["id"]}>{$name}</option>";
                                }
                            }
                        ?>
                </select>
                <input class="check brewery-checkbox" type="checkbox" name="brewery-checkbox" />
                                        
                <select name='country' class='form-input country'>
                                <?php 
                                    $sql = "SELECT country FROM `beers`";
                                    $result = $conn->query($sql);
                                    $coutryArray=array();
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            array_push($coutryArray, $row['country']);
                                        }
                                    }
                                    $uniqueCountries = array_unique($coutryArray);
                                    $sql = "SELECT id, name FROM countries";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            for ($i=0; $i<count($uniqueCountries); $i++) {
                                                if ($row['name'] == $uniqueCountries[$i]) {
                                                    echo  "<option onclick=countrySet({$row["id"]}) value={$row["id"]}>{$row["name"]}</option>";
                                                }
                                            }
                                        }
                                    }
                                ?>
                </select>
                <input class="check country-checkbox" type="checkbox" name="country-checkbox" />

                <select name='year' class='form-input year'>
                        <?php
                            $idCounter = 1;
                            for ($i = 2020; $i >= 1950; $i--) {
                                echo "<option onclick=yearSet({$i}) value={$idCounter}>{$i}</option>";
                            }
                        ?>
                </select>
                <input class="check year-checkbox" type="checkbox" name="year-checkbox" />
                            
        
                
                
               
                
                <input type='submit' value='search' class='form-input submit'/>
            </form>
            </div>
        </div>
        
    </div>
    <!--    $search = $_GET["search"];
            $breweryId = $_GET["brewery"];
            $countryId = $_GET["country"];
            $year = $_GET["year"]; -->
    
</body>
</html>
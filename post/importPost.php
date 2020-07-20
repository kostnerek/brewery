<?php 
include('../config.php');
$conn = mysqli_connect($server, $user, $password, $db);


class importAction
{
    public $conn;

    public $csvData;

    public $elementCount;

    function __construct($conn)
    {
        $this->conn = $conn;
        $filename = '../data.csv';
 
        if (($h = fopen("{$filename}", "r")) !== FALSE) {
            while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                $this->csvData[] = $data;
            }
            fclose($h);
        }
        $this->checkIfCorrectFile();

        array_shift($this->csvData);

        $this->elementCount = count($this->csvData);
    }
    
    function checkIfCorrectFile()
    {
        $correctData = array('beer_name', 'brewery', 'country', 'production_date');

        for ($i = 0; $i < 3; $i++ ) {
            if ($correctData[$i] != $this->csvData[0][$i]) {
                unlink('../data.csv');
                echo "<meta http-equiv=\"refresh\" content=\"0;url=../import.php?error=badcsv\">";
                exit();
            }
            else {
                continue;
            }
        }
    }

    function parseData()
    {
        for ($i=0; $i<$this->elementCount;$i++) {
            $beerName    = strtolower(trim($this->csvData[$i][0]));
            $breweryName = strtolower(trim($this->csvData[$i][1]));
            $country     = ucfirst(strtolower(trim($this->csvData[$i][2])));
            $prodDate    = trim($this->csvData[$i][3]);
            $imgSrc      = "resources/img/".$breweryName."/".$beerName.".jpg";

            $this->insertData($beerName, $breweryName, $country, $prodDate, $imgSrc);
        }
    }

    /** 
     * 
     * Adds new items into db
     * (brewery, beers)
     *   
    */
    function insertData($beerName, $breweryName, $country, $prodDate, $imgSrc)
    {
        if (isset($_POST['doIt']))  {
           
            $sql = "SELECT * FROM `beers` 
                    WHERE beer_name = '{$beerName}' AND
                          country =  '{$country}' AND
                          brewery = '{$breweryName}' AND
                          production_date = '{$prodDate}' AND
                          img_src ='{$imgSrc}'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                return 0;
            } 
            if ($result->num_rows == 0 ) {
                $sql = "INSERT INTO `beers`(`beer_name`, `country`, `brewery`, `production_date`, `img_src`) 
                VALUES ('{$beerName}', '{$country}', '{$breweryName}', '{$prodDate}', '{$imgSrc}')";
                if (!mysqli_query($this->conn,$sql)) {
                    echo("Error description: " . mysqli_error($this->conn));
                    die();
                }
            }
            
            $sql = "SELECT * FROM `breweries`WHERE name = '{$breweryName}'";
            $result = $this->conn->query($sql);

            if ($result->num_rows > 0) {
                return 0;
            } 
            if ($result->num_rows == 0 ) {
               $sql = "INSERT INTO `breweries`(`name`) VALUES ('{$breweryName}')";
               if (!mysqli_query($this->conn,$sql)) {
                echo("Error description: " . mysqli_error($this->conn));
                die();
                }
            }
            $this->folderAction($breweryName);
        }
    }
    function folderAction($breweryName)
    {
        if (is_dir("../resources/img/{$breweryName}")==true) {
            return false;
        }
        else {
            mkdir("../resources/img/{$breweryName}");
        }
    }
}

$importer = new importAction($conn);
$importer->parseData();
unlink('../data.csv');
echo "<meta http-equiv=\"refresh\" content=\"0;url=../edit.php\">";
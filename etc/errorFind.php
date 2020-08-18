<?php
class errorFind
{
    public $conn;

    function __construct($conn)
    {
        $this->conn = $conn;
        $this->checkForBreweryError();
        $this->errorInDir();
    }
    public function checkForBreweryError()
    {
        $sql = "SELECT brewery FROM `beers`";
        $result = $this->conn->query($sql);
        $beerArray=array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($beerArray, $row['brewery']);
            }
        }
        $uniqueBeer = array_unique($beerArray);

        $breweryArray = array();
        $sql = "SELECT name FROM breweries";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($breweryArray, $row['name']);
            }
        }

        sort($uniqueBeer);
        sort($breweryArray);

        /* echo "<pre>".var_dump($uniqueBeer)."<br>".var_dump($breweryArray)."</pre>";

        if (count($uniqueBeer) == count($breweryArray)) {
            echo "MATCHING LENGTH";
        }
        else{
            if(count($uniqueBeer) > count($breweryArray)){echo "beers longer";}
            else{echo "breweries longer";}
        } */

        for ($i = 0; $i<count($uniqueBeer); $i++) {
            if ($uniqueBeer[$i] != $breweryArray[$i]) {
                $this->addBrewery($uniqueBeer[$i]);
            }
        }
    }

    public function addBrewery($brewery) 
    {
        $sql = "INSERT INTO `breweries`(`name`) 
        VALUES ('{$brewery}')";
        $this->conn->query($sql);
        if (!is_dir('../resources/img/'.$brewery)) {
            mkdir('../resources/img/'.$brewery);
        }
    }

    public function errorInDir()
    {
        $sql = "SELECT * FROM `beers`";
        $result = $this->conn->query($sql);

        $sql = "SELECT * FROM `breweries`";
        $breweries = $this->conn->query($sql);

        $beerArray=[];
        $breweriesArray=[];
        
        if ($breweries->num_rows > 0) {//brewery array
            while($row = $breweries->fetch_assoc()) {
                array_push($breweriesArray, $row['name']);
            }
        }
        if ($result->num_rows > 0) {//beer array
            while($row = $result->fetch_assoc()) {
                if (in_array($row['brewery'], $breweriesArray)) {
                    unset($breweriesArray[array_search($row['brewery'], $breweriesArray)]);
                }
            }
        }

        $breweriesArray = array_merge($breweriesArray);

        if (!empty($breweriesArray)) {
            for ( $i = 0; $i < count($breweriesArray); $i++) {
                if (is_dir('../resources/img/'.$breweriesArray[$i])) {
                    if (is_dir_empty('../resources/img/'.$breweriesArray[$i])) {
                        rmdir('../resources/img/'.$breweriesArray[$i]);
                    }
                    else {
                        deleteDir('../resources/img/'.$breweriesArray[$i]);
                    }
                }
                $sql = "DELETE FROM `breweries` WHERE name='{$breweriesArray[$i]}'";
                $this->conn->query($sql);
            }
        }

        function deleteDir($dirPath) {
            if (! is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }

        function is_dir_empty($dir) {
            if (!is_readable($dir)) {
                return NULL;
            }
            else {
                return (count(scandir($dir)) == 2);
            }
        }
    }
}

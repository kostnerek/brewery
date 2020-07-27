<?php 

class postBeer
{
    public  $beerName,  //data from post
            $breweryName,
            $country,
            $prodDate;

    public $conn;//connection with db

    public $allDataArray,
           $checkData;//data array

    public $status,//classes objects
           $creator;

    function __construct($conn)
    {
        /* var_dump($_POST);
        die(); */
        $this->conn = $conn;
        $this->beerName = $_POST['beer_name'];
        $this->country = $_POST['country'];
        $this->prodDate = $_POST['date'];
        
        if ($_POST['option']==1) {//non existing brewery option
            $this->breweryName = $_POST['breweriesOne'];
        } 
        else {
            $this->breweryName = $_POST['breweries'];
        }

        if(isset($_POST['beer_name'])==false){
            return 0;
        }

        $this->allDataArray = array($this->beerName, $this->breweryName, $this->country, $this->prodDate);

        $this->checkIfAllSet();

        $this->formatData();

        $this->status = new checkAll($this->beerName, $this->breweryName, $this->conn);
        $this->creator = new creator ($this->beerName, $this->breweryName, $this->country, $this->prodDate, $this->conn);
    }


    public function checkIfAllSet()
    {
        if ($this->allDataArray[0]== "") {
            echo "<meta http-equiv=\"refresh\" content=\"0;url=../system.php?error=unset\">";
            exit();
        }
    }

    public function formatData()
    {
        $this->beerName    = str_replace(' ','_',strtolower($this->beerName));
        $this->breweryName = str_replace(' ','_',strtolower($this->breweryName));
    }

    public function checkStatus()
    {
        $checkData = array(
            'beer'    => $this->status->beerExist, 
            'brewery' => $this->status->breweryExist,
            'folder'  => $this->status->folderExist
        );
        $this->checkData = $checkData;
    }

    public function addEntites()
    {
        if ($this->status->beerExist==false) {
            $this->creator->addBeer();
            if ($this->status->folderExist==true)
            {
                $this->creator->imgHandler();
            }
        }
        if ($this->status->breweryExist==false) {
            $this->creator->addBrewery();
        }
        if ($this->status->folderExist==false) {
            $this->creator->addFolder();
        }
    }
}
/**
 * checks if exist beer, brewery and folder with given params
 * @param beerName
 * @param breweryName
 */

class checkAll extends postBeer 
{

    public  $beerExist, 
            $breweryExist, 
            $folderExist,
            $beerName,
            $breweryName;

    public $conn;

    function __construct($beerName, $breweryName ,$conn)
    {
        $this->conn = $conn;
        $this->beerName     = $beerName;
        $this->breweryName  = $breweryName;

        $this->beerExist    = $this->checkIfBeerExist();
        $this->breweryExist = $this->checkIfBreweryExist();
        $this->folderExist  = $this->checkIfFolderExist();
        
    }


    function checkIfFolderExist()
    {
        if (is_dir("resources/img/{$this->breweryName}")==true) {
            return true;
        }
        else {
            return false;
        }
    }
    function checkIfBeerExist()
    {
        $sql = "SELECT * FROM `beers` 
                WHERE `beer_name`= '{$this->beerName}'";

        $result = $this->conn->query($sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row["beer_name"]==$this->beerName && $row['brewery']==$this->breweryName) {
                    return true;
                }
            }
        }
    }
    function checkIfBreweryExist()
    {
        $sql = "SELECT * FROM `breweries` 
                WHERE `name`= '{$this->breweryName}'";

        $result = $this->conn->query($sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row['name']==$this->breweryName) {
                    return true;
                }
            }
        }  
    }
}

class creator extends postBeer
{
    public  $beerName,
            $breweryName,
            $country,
            $prodDate,
            $imgPath;

    public $conn;

    function __construct($beerName, $breweryName, $country, $prodDate, $conn)
    {
        $this->conn = $conn;
        $this->beerName     = $beerName;
        $this->breweryName  = $breweryName;
        $this->country      = $country;
        $this->prodDate     = $prodDate;
        $this->imgPath      = "resources/img/{$breweryName}/{$beerName}.jpg";
    }

    public function addBeer()
    {
        $sql = "INSERT INTO `beers`(`beer_name`, `country`, `brewery`, `production_date`, `img_src`) 
                VALUES ('{$this->beerName}','{$this->country}','{$this->breweryName}','{$this->prodDate}','{$this->imgPath}')";
                $this->conn->query($sql);
    }

    public function addBrewery()
    {
        $sql = "INSERT INTO `breweries`(`name`) 
                VALUES ('{$this->breweryName}')";
                $this->conn->query($sql);
    }

    public function addFolder()
    {
        mkdir('../resources/img/'.$this->breweryName);
        $this->imgHandler();
    }

    public function imgHandler()
    {
        $target_dir = "";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if(isset($_POST["submit"])) {
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        rename($target_file, '../resources/img/'.$this->breweryName."/".$this->beerName.".jpg");
    }
}


include('../config.php');
$conn = mysqli_connect($server, $user, $password, $db);

$submitPostAction = new postBeer($conn);

$submitPostAction->checkStatus();
$submitPostAction->addEntites();
/* echo "<meta http-equiv=\"refresh\" content=\"0;url=../list.php\">";
exit(); */
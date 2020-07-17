<?php 

include('../config.php');
$conn = mysqli_connect($server, $user, $password, $db);

class edit 
{
    public  $beerName,  //data from post
            $breweryName,
            $country,
            $prodDate,
            $photo;

    public $id;
    
    /* public $oldBeerName,
           $oldBreweryName; */

    public $conn;//connection with db

    public $status;

    public $beerNameCheck      = false,
           $breweryNameCheck   = false,
           $countryCheck       = false,
           $prodDateCheck      = false,
           $imgCheck           = false,
           $checkArray;

    public $newDataArray,
           $oldDataArray;

    /**
     * getting variables and renaming some @param
     * executing editDb() function
     */
    function __construct($conn)
    {
        $this->conn = $conn;
        $this->beerName = str_replace(' ','_',strtolower($_POST['beer_name']));
        $this->country = $_POST['country'];
        $this->prodDate = $_POST['date'];
        $this->id = $_POST['id'];

        if ($_POST['option']==1) {//non existing brewery option
            $this->breweryName= str_replace(' ','_',strtolower($_POST['breweriesOne']));
        } 
        else {
            $this->breweryName= str_replace(' ','_',strtolower($_POST['breweries']));;
        }

        if(isset($_POST['beer_name'])==false){
            return 0;
        }

        if ($_POST['photo']==1) {//change photo
            $this->photo = $_POST['photo'];
        } 
        else {
            $this->photo = $_POST['photo'];
        }

        $this->newImgSrc = "resources/img/".$this->breweryName."/".$this->beerName.".jpg";

        $this->newDataArray = array('beer_name'       => $this->beerName, 
                                    'country'         => $this->country, 
                                    'brewery'         => $this->breweryName,  
                                    'production_date' => $this->prodDate, 
                                    'img_src'         => $this->newImgSrc);
        var_dump($this->newDataArray);

        $this->checkDb();
        $this->editDb();
        $this->postEditDb();

    }

    function checkDb()
    {
        $sql = "SELECT * FROM beers WHERE id='{$this->id}'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
               
                if ($row['beer_name'] == $this->beerName){
                    $this->beerNameCheck=true;
                }
                if ($row['country'] == $this->country){
                    $this->countryCheck=true;
                }
                if ($row['brewery'] == $this->breweryName){
                    $this->breweryNameCheck=true;
                }
                if ($row['production_date'] == $this->prodDate){
                    $this->prodDateCheck=true;
                }
                if ($this->photo == 1) {
                    $this->imgCheck = $this->checkLink($row['img_src']);
                    //$this->oldImgSrc="resources/img/".$row["brewery"]."/".$row["beer_name"].".jpg";

                    //unlink($this->oldImgSrc);
                }
                $this->oldDataArray = array('beer_name'       => $row['beer_name'], 
                                            'country'         =>$row['country'], 
                                            'brewery'         =>$row['brewery'], 
                                            'production_date' =>$row['production_date'], 
                                            'img_src'         =>$row['img_src']);
            }
        }
        echo "<pre>";
        var_dump($this->oldDataArray);
        echo "<br>";
        var_dump($this->newDataArray);
        echo "<br>";
        $this->checkArray = array('beer_name'       => $this->breweryNameCheck,
                                  'country'         => $this->countryCheck,
                                  'brewery'         => $this->breweryNameCheck,
                                  'production_date' => $this->prodDateCheck,
                                  'img_src'         => $this->imgCheck);
        var_dump($this->checkArray);
    }
       
    function editDb()
    {
        $dataToUpdate=array('beer_name'       => null,
                            'country'         => null,
                            'brewery'         => null,
                            'production_date' => null,
                            'img_src'         => null);

        if (!$this->checkArray['beer_name']) {
            $dataToUpdate['beer_name'] = $this->newDataArray['beer_name'];
        } else {
            $dataToUpdate['beer_name'] = $this->oldDataArray['beer_name'];
        }

        if (!$this->checkArray['country']) {
            $dataToUpdate['country'] = $this->newDataArray['country'];
        } else {
            $dataToUpdate['country'] = $this->oldDataArray['country'];
        }

        if (!$this->checkArray['brewery']) {
            $dataToUpdate['brewery'] = $this->newDataArray['brewery'];
        } else {
            $dataToUpdate['brewery'] = $this->oldDataArray['brewery'];
        }

        if (!$this->checkArray['production_date']) {
            $dataToUpdate['production_date'] = $this->newDataArray['production_date'];
        } else {
            $dataToUpdate['production_date'] = $this->oldDataArray['production_date'];
        }

        if (!$this->checkArray['img_src']) {
            $dataToUpdate['img_src'] = $this->newDataArray['img_src'];
        } else {
            $dataToUpdate['img_src'] = $this->oldDataArray['img_src'];
        }

        var_dump($dataToUpdate);

        $sql = "UPDATE `beers` 
                SET beer_name       = '{$dataToUpdate['beer_name']}',
                    country         = '{$dataToUpdate['country']}',
                    brewery         = '{$dataToUpdate['brewery']}',
                    production_date = '{$dataToUpdate['production_date']}',
                    img_src         = '{$dataToUpdate['img_src']}'
                WHERE id = '{$this->id}'";
        echo $sql;
        $this->conn->query($sql);
    }

    function postEditDb()
    {
        /* if ( !is_dir("resources/img/".$this->newDataArray['brewery'])) {
            mkdir("resources/img/".$this->newDataArray['brewery']);
            rename($this->oldDataArray['img_src'], $this->newDataArray['img_src']);
        } */
        if ( !$this->checkArray['beer_name']) {
            rename($this->oldDataArray['img_src'], $this->newDataArray['img_src']);
        }
        if ( !$this->checkArray['brewery']) {

            if ( !is_dir("resources/img/".$this->newDataArray['brewery'])) {
                mkdir("resources/img/".$this->newDataArray['brewery']);
            }

            rename($this->oldDataArray['img_src'], $this->newDataArray['img_src']);
        }
    }

    function checkLink($link)
    {
        //$newImgSrc= "resources/img/".$this->breweryName."/".$this->beerName.".jpg";
        if ($link == $this->newImgSrc) {
            return true;
        }
        else {
            return false;
        }
    }

    function imgEdit()
    {
        $target_dir = "";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if(isset($_POST["submit"])) {
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        }
        rename($target_file, $this->newImgSrc);
    }
}

$editor = new edit($conn);
echo "<meta http-equiv=\"refresh\" content=\"0;url=../edit.php\">";
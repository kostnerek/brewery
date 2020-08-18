<?php 



include('../../etc/config.php');
$conn = mysqli_connect($server, $user, $password, $db);



class editBrewery
{

    public $conn;

    public $id, $newBreweryName, $oldBreweryName;

    function __construct($conn)
    {
        $this->conn = $conn;
        $this->id = $_POST['id'];
        $this->newBreweryName = str_replace(' ','_',strtolower($_POST['brewery']));

        $this->getBreweryName();

        $this->checkIfBreweryAlreadyExist();

        $this->renameFolder();
        $this->updateDb();
        

        $this->updatePaths();
    }
    
    function checkIfBreweryAlreadyExist()
    {
        $sql = "SELECT * FROM `breweries`";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {//brewery array
            while($row = $result->fetch_assoc()) {
                if ($this->newBreweryName == $row['name']) {
                    echo "<meta http-equiv=\"refresh\" content=\"0;url=../brewery.php?error=exist\">";
                    exit();
                }
            }
        }
    }

    function getBreweryName()
    {
        $sql = "SELECT * FROM `breweries` WHERE id='{$this->id}'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {//brewery array
            while($row = $result->fetch_assoc()) {
                $this->oldBreweryName = $row['name'];
            }
        }
    }

    function updateDb()
    {
        $sql = "UPDATE `breweries` SET name='{$this->newBreweryName}' WHERE id='{$this->id}'";
        $this->conn->query($sql);
    }

    function renameFolder()
    { 
        $oldFolderName = "../../resources/img/".$this->oldBreweryName;
        $newFolderName = "../../resources/img/".$this->newBreweryName;
       
        rename($oldFolderName, $newFolderName);
    }

    function updatePaths()
    {
        $sql = "SELECT * FROM `beers` WHERE brewery = '{$this->oldBreweryName}'";

        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {//brewery array
            while($row = $result->fetch_assoc()) {
                $newSrc = "resources/img/".$this->newBreweryName."/".$row['beer_name'].'.jpg';
                $sql = "UPDATE `beers` SET brewery='{$this->newBreweryName}', img_src = '{$newSrc}' WHERE id='{$row['id']}'";
                //echo $sql."<br>";
                $this->conn->query($sql);
            }
        }
    }

}

$edit = new editBrewery($conn);

echo "<meta http-equiv=\"refresh\" content=\"0;url=../brewery.php\">";
<?php 

include('../config.php');
$conn = mysqli_connect($server, $user, $password, $db);

class export 
{
    public $conn;

    public $postData,
           $beerData=[];
    public $filename;

    function __construct($conn)
    {
        $this->conn = $conn;

        $this->postData = $_POST['select'];

        for ($i=0; $i<count($this->postData); $i++) {
            $this->getBeersData($this->postData [$i]);
        }

        $this->filename = 'export-'.date('Y-m-d').'.csv';

        $this->insertDataIntoCsv();
        $this->outputData();

    }

    function getBeersData($breweryName)
    {
        $sql = "SELECT * FROM `beers` WHERE brewery = '$breweryName'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($this->beerData, array("beer_name"=>"{$row['beer_name']}", "brewery"=>"{$row['brewery']}", "country"=>"{$row['country']}", "production_date"=>"{$row['production_date']}"));
            }
        } 
    }

    function insertDataIntoCsv()
    {

        $fp = fopen('../'.$this->filename, 'w');
        fputcsv($fp, array('beer_name','brewery','country','production_date'));
        for ($i=0; $i<count($this->beerData); $i++) {
            fputcsv($fp, $this->beerData[$i]);
        }
        fclose($fp);
    }

    function outputData()
    {
        $file = '../'.$this->filename;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            unlink('../'.$this->filename);
            exit;
        }
    }

}

$export = new export($conn);


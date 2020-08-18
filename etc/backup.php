<?php 

class backup 
{
    public $conn;
    public $filename;
    public $beerData=array();

    function __construct($conn)
    {
        $this->conn = $conn;
        $this->filename = date('Y-m-d-').'backup.csv';
        $this->getBeersData();
        $this->insertDataIntoCsv();
    }

    function getBeersData()
    {
        $sql = "SELECT * FROM `beers`";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($this->beerData, array("beer_name"=>"{$row['beer_name']}", "brewery"=>"{$row['brewery']}", "country"=>"{$row['country']}", "production_date"=>"{$row['production_date']}"));
            }
        } 
    }

    function insertDataIntoCsv()
    {
        if (!is_dir('etc/backup/')) {
            mkdir('etc/backup');
        }

        $fp = fopen('etc/backup/'.$this->filename, 'w');
        fputcsv($fp, array('beer_name','brewery','country','production_date'));
        for ($i=0; $i<count($this->beerData); $i++) {
            fputcsv($fp, $this->beerData[$i]);
        }
        fclose($fp);
    }
}
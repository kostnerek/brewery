<?php 

include('../config.php');
$conn = mysqli_connect($server, $user, $password, $db);

class export 
{
    public $conn;

    public $allDataArray;

    function __construct($conn)
    {
        $this->conn = $conn;

        $this->allDataArray = $_POST['select'];
        
    }
}

$export = new export($conn);


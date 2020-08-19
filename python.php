<h1>essa</h1>
<?php 
    $data = array('1');
    $x=1;
    while($x=1){
        if (isset($_GET['hp'])) {
                echo $_GET['hp'];
                //array_push($data, $_GET['hp']);
            }
        sleep(5);
        header("Location: python.php");
    }
    

?>
<?php
    header("Content-Type:application/json");

    if (isset($_GET['action']) && $_GET['action']!="") {
        
        $action = $_GET['action'];
        if ($action == 1) {
            getResponse();
        }
        
    }
        
    function getResponse()
    {
        include('../etc/config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
        $sql = "SELECT `r0`, `r1`, `r2`, `r3`, `r4`, `r5`, `r6`, `r7` FROM `api` ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $json_response = json_encode($row);
                echo $json_response;
            }
        }
    }
?>

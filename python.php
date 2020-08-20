<?php
    

    header("Content-Type:application/json");
    if (isset($_GET['data']) && $_GET['data']!="") {
        
        $server   = 'hosting2024247.online.pro';
        $db       = '00388586_brewery';
        $user     = '00388586_brewery';
        $password = '!Pastwisko37';
        $rootDir  = 'public_html';

        $conn = mysqli_connect($server, $user, $password, $db);
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            die();
        }
        

        $data = $_GET['data'];

        if ($data == 2137) {
            response($conn);
        } else {
            update($data, $conn);
        }
    }

    function update($data, $conn)
    {
        $sql = "UPDATE `api` SET `id`='1',`data`='{$data}' WHERE id=1";
        $conn->query($sql);
    }

    function response($conn){
        $sql = "SELECT `data` FROM `api` WHERE id=1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $response = $row['data'];
            }
        }
        $json_response = json_encode($response);
        echo $json_response;
    }
?>
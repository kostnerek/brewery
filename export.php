<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="resources/css/upload.css">
    <link rel="icon" type="image/ico" href="resources/img/favicon.ico">
    <title>Export</title>
</head>

<body>
    <div class="center">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn" onclick="window.location.href='system.php'">System</button>
            <button type="button" class="btn" onclick="window.location.href='list.php'">List</button>
            <button type="button" class="btn" onclick="window.location.href='brewery.php'">Brewery</button>
            <button type="button" class="btn" onclick="window.location.href='stats.php?select=beers'">Stats</button>
        </div>
        <h3>Export data</h3>
        <table class="export">
            <tr>
                <th style="width: 85%; border: 2px black solid">Brewery</th>
                <th style="border: 2px black solid">
                    <button class="btn" onclick="selectAll()">Select</button>
                    <button class="btn" onclick="UnSelectAll()">Unselect</button>
                </th>
            </tr>
            <form id="form" action="actions/exportAction.php" method='POST' enctype="multipart/form-data">
                <?php
                    include('config.php');
                    $conn = mysqli_connect($server, $user, $password, $db);
                    $sql = "SELECT * FROM `breweries`";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td style='width: 80%'>{$row['name']}</td>";
                            echo "<td>
                                    <input type='checkbox' name='select[]' value='{$row['name']}'>
                                </td>";
                            echo "</tr>";
                        }
                    } 
                ?>
                
            </form>    
        <table>
        <button style="border: 2px black solid; background-color: #861821; margin-top:1%; margin-bottom:1%; border-radius:10px" onclick="document.getElementById('form').submit()"><h4>Download backup</h4></button>
        <br>
    </div>

    <script>
        function selectAll(){
			var items=document.getElementsByName('select[]');
			for(var i=0; i<items.length; i++){
				if(items[i].type=='checkbox')
					items[i].checked=true;
			}
		}
		
		function UnSelectAll(){
			var items=document.getElementsByName('select[]');
			for(var i=0; i<items.length; i++){
				if(items[i].type=='checkbox')
					items[i].checked=false;
			}
		}	
    </script>

</body>

</html>
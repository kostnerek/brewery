<?php 
if ($_COOKIE['logged']!=true) {
    echo "<meta http-equiv=\"refresh\" content=\"0;url=../admin.php\">";
}
?>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../resources/css/upload.css">
    <link rel="stylesheet" href="../resources/css/navbar.css">
    <link rel="stylesheet" href="../resources/css/list.css">
    <link rel="icon" type="image/ico" href="../etc/favicon.ico">
    <title>List</title>
</head>

<body>
    <?php 
        include('../etc/config.php');
        $conn = mysqli_connect($server, $user, $password, $db);

        function countFileErrors($conn)
        {
            $counter=0;
            $sql = "SELECT * FROM beers";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
               while($row = $result->fetch_assoc()) {
                    
                    if(!file_exists('../'.$row['img_src'])) {
                        $counter++;
                    }
                }
            }
            return $counter;
        }
        if (isset($_GET['page'])) {
            $page = $_GET["page"];
        }
        else {
            $page = 1;
        }
    ?>
    

    <div class="center">
        <?php include('../etc/navbar.php')?>


        <script>
            var noErrors = <?php echo countFileErrors($conn)?>;
            var system = document.getElementById('header');
            var settings = document.getElementById('settings');
        
            if (noErrors>0) {
                system.classList.add("error");
                settings.classList.add("error");
                var notify = document.getElementById('notify');
                notify.style.display = 'block'
                notify.innerHTML = noErrors;

                if (noErrors<=9) {
                    notify.style.top = '-11px'
                    notify.style.right = '-11px'
                    notify.style.padding = '2px 7px 4px 7px';
                }
                else{
                    notify.style.padding = '3px 7px 5px 7px;';
                }
            }
        </script>


        <h3>List of all beers</h3>
           
        <table id='main'>
            <tr>
                <?php 
                    echo "<form method='post' action='list.php'>";

                    echo "<th>           
                            <button value='id_up'   type='submit' name='sort'class='fa sort fa-arrow-up'></button><br>ID<br>
                            <button value='id_down' type='submit' name='sort'class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>
                            <button value='beer_name_up'    type='submit' name='sort' class='fa sort fa-arrow-up'></button><br>BEER<br> 
                            <button value='beer_name_down'  type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>
                            <button value='brewery_up'      type='submit' name='sort' class='fa sort fa-arrow-up'></button><br>BREWERY<br>
                            <button value='brewery_down'    type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>
                            <button value='country_up'      type='submit' name='sort' class='fa sort fa-arrow-up'></button><br>COUNTRY<br> 
                            <button value='country_down'    type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>
                            <button value='prodDate_up'     type='submit' name='sort' class='fa sort fa-arrow-up'></button>DATE        
                            <button value='prodDate_down'   type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>IMG SRC</th>";

                    echo "</form>";
                    echo "<th colspan='2'>ACTION</th>";
                    ?>
            </tr>
            <?php

                $limit = 50 * $page;

                    if (isset($_POST['sort'])) {
                        switch($_POST['sort']) {
                            case 'id_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `id` ASC limit {$limit},50";
                                    break;
                                }
                            case 'id_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `id` DESC limit {$limit},50";
                                    break;
                                }
                            case 'beer_name_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `beer_name` ASC limit {$limit},50";
                                    break;
                                }
                            case 'beer_name_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `beer_name` DESC limit {$limit},50";
                                    break;
                                }
                            case 'country_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `country` ASC limit {$limit},50";
                                    break;
                                }
                            case 'country_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `country` DESC";
                                    break;
                                }
                            case 'brewery_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `brewery` ASC limit {$limit},50";
                                    break;
                                }
                            case 'brewery_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `brewery` DeSC limit {$limit},50";
                                    break;
                                }
                            case 'prodDate_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `production_date` ASC limit {$limit},50";
                                    break;
                                }
                            case 'prodDate_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `production_date` DESC limit {$limit},50";
                                    break;
                                }
                            default:
                                {
                                    $sql = "SELECT * FROM `beers` limit {$limit},50";
                                    break;
                                }
                        }
                    }
                    
                    if (!isset($_POST['sort'])) {
                        $sql = "SELECT * FROM `beers` limit {$limit},50";
                    }

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td name='{$row['id']}'>{$row['id']}</td>";
                            echo "<td class='beer_name' value='{$row['beer_name']}'>{$row['beer_name']}</td>";
                            echo "<td>
                                      <form action='../brewery/action/showAction.php' method='post'>
                                          <button class='action' value='{$row['brewery']}' type='submit' name='id'>{$row['brewery']}</button>
                                      </form>
                                  </td>";
                            echo "<td>{$row['country']}</td>";
                            echo "<td>{$row['production_date']}</td>";
                            $breweryLength = strlen($row['brewery']);
                            $stSlice = substr($row['img_src'],0,15+$breweryLength);
                            $ndSlice = substr($row['img_src'],15+$breweryLength,strlen($row['img_src']));
                            echo "<td class='smallerSrc' id='{$row['beer_name']}' onmouseover=\"showImage('{$row['img_src']}', '{$row['beer_name']}')\" onmouseout=\"destroyImage()\">
                                    <form method='post' action='action/imgShowAction.php'>
                                     <button class='action' name='img_src' type='submit' value='{$row['img_src']}'>{$stSlice}<br>{$ndSlice}</button>
                                    </form>
                                  </td>";
                                    
                            echo "<td>
                                    <form action='action/editAction.php' method='post'>
                                        <button  style='font-size: 36px; color:black' class='action fa' value='{$row['id']}' type='submit' name='id'>
                                            &#xf044;
                                        </button>
                                    </form>
                                </td>";

                            echo "<td>
                                    <form action='action/deleteAction.php' method='post'>
                                        <button style='font-size: 36px; color:black' class='action fa' value='{$row['id']}' type='submit' name='id'>
                                            &#xf00d;
                                        </button>
                                    </form>
                                </td>";

                            echo "</tr>";
                            echo "</form>";
                        }
                    } 
                ?>
        </table>

        <div class='pagination'>
            <?php 
                $sql = "SELECT * FROM beers";
                $result = $conn->query($sql);
                $elementCount = $result->num_rows;
                $pages = (float)$elementCount/50;
                for ($i=0; $i < floor($pages)+1 ; $i++) { 
                    $number = $i+1;
                    echo "<form  class='pageButton' action='list.php?page=$i' method='post'>";
                    echo "  <button  type='submit'>{$number}</button>";
                    echo "</form>";
                }
            ?>
        </div>

    </div>
    
</body>
<script>
    window.onload = function setFontSize() {
        var length = document.getElementsByClassName('beer_name').length
        for (let i=0; i < length; i++) {
            var beer = document.getElementsByClassName('beer_name')[i]
            var beername = beer.textContent;

            if (beername.length <= 22) {
                beer.style.fontSize = '15px';
            }
        }
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function showImage(img_src,beer_name)
    {
        var beerContainer = document.getElementById(beer_name)
        var element = document.getElementsByClassName('showImage')
        var element =  document.getElementById('elementId');
        if (typeof(element) == 'undefined' && element == null)
        {
            return false;
        }

        await sleep(200);
        
        var rect = beerContainer.getBoundingClientRect();
        //console.log(rect.top, rect.right, rect.bottom, rect.left);


        var width  = beerContainer.offsetWidth;
        var height = (350/480)*width;

        image = document.createElement("div")
        image.classList.add('showImage')
        image.setAttribute("id", "beerImg")
        image.style.position = 'absolute';
        image.style.top = rect.top+"px";
        image.style.left = rect.left+"px";


        image.innerHTML = "<img width="+width+" height="+height+" src="+"../"+img_src+" onerror=\"this.onerror=null; this.src='../etc/error.png';\">"
        //document.getElementById("main").appendChild(image);

    }
    function destroyImage()
    {
        var elements = document.getElementsByClassName('showImage');
        while(elements.length > 0){
            elements[0].parentNode.removeChild(elements[0]);
        }
    }





</script>

</html>
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
    ?>
    <div class="center">
    <?php include('../etc/navbar.php')?>
        <h3>List of all beers</h3><!-- TODO ADD ERRORS FROM SYSTEM/SETTINGS/FILEINTEGRITY -->
        <table id='main'>
            <tr>
                <?php 
                    echo "<form method='post' action='list.php'>";

                    echo "<th>           
                            <button value='id_up'   type='submit' name='sort'class='fa sort fa-arrow-up'></button>
                            <button value='id_down' type='submit' name='sort'class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>BEER 
                            <button value='beer_name_up'    type='submit' name='sort' class='fa sort fa-arrow-up'></button>
                            <button value='beer_name_down'  type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>BREWERY
                            <button value='brewery_up'      type='submit' name='sort' class='fa sort fa-arrow-up'></button>
                            <button value='brewery_down'    type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>COUNTRY 
                            <button value='country_up'      type='submit' name='sort' class='fa sort fa-arrow-up'></button>
                            <button value='country_down'    type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>DATE        
                            <button value='prodDate_up'     type='submit' name='sort' class='fa sort fa-arrow-up'></button>
                            <button value='prodDate_down'   type='submit' name='sort' class='fa sort fa-arrow-down'></button></th>";

                    echo "<th>IMG SRC</th>";

                    echo "</form>";
                    echo "<th colspan='2'>ACTION</th>";
                    ?>
            </tr>
            <?php
                    if (isset($_POST['sort'])) {
                        switch($_POST['sort']) {
                            case 'id_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `id` ASC";
                                    break;
                                }
                            case 'id_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `id` DESC";
                                    break;
                                }
                            case 'beer_name_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `beer_name` ASC";
                                    break;
                                }
                            case 'beer_name_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `beer_name` DESC";
                                    break;
                                }
                            case 'country_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `country` ASC";
                                    break;
                                }
                            case 'country_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `country` DESC";
                                    break;
                                }
                            case 'brewery_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `brewery` ASC";
                                    break;
                                }
                            case 'brewery_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `brewery` DeSC";
                                    break;
                                }
                            case 'prodDate_up':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `production_date` ASC";
                                    break;
                                }
                            case 'prodDate_down':
                                {
                                    $sql = "SELECT * FROM `beers` ORDER BY `production_date` DESC";
                                    break;
                                }
                            default:
                                {
                                    $sql = "SELECT * FROM `beers`";
                                    break;
                                }
                        }
                    }
                   
                    
                    if (!isset($_POST['sort'])) {
                        $sql = "SELECT * FROM `beers`";
                    }

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td name='{$row['id']}'>{$row['id']}</td>";
                            echo "<td class='beer_name'>{$row['beer_name']}</td>";
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
                            echo "<td class='smallerSrc'>
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
        </form>
    </div>
</body>

</html>
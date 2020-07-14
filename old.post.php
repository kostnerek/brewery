<?php
$devMode=0;

        include('config.php');
        $conn = mysqli_connect($server, $user, $password, $db);
    
$beerName = $_POST['beer_name'];
$beerName = str_replace(' ','_',$beerName);
$country = $_POST['country'];
$option = $_POST['option'];
$prodDate = $_POST['date'];

if ($option==1) {//non existing brewery option
  $brewery = $_POST['breweriesOne'];
} 
else {
  $brewery = $_POST['breweries'];
}
$brewery=str_replace(' ','_',$brewery);

echo $brewery;
/**
 * 
 * checks if there is beer with corresponding name
 * 
 */

$checkIfExist=false;
$sql = "SELECT * FROM `beers` WHERE `beer_name`= '{$beerName}'";
$checkResult = $conn->query($sql);
if ($checkResult->num_rows > 0) {
    while($row = $checkResult->fetch_assoc()) {
        if ($row["beer_name"]==$beerName && $row['brewery']==$brewery) {
            $checkIfExist = true;
        }
        else{
            $checkIfExist = false;
        }
    }
}
else {
    $checkIfExist = false;
}
echo "Exist check: ".$checkIfExist."<br>";


echo "Beer name: ".$beerName;
echo "<br>";
echo "Country: ".$country;
echo "<br>";
echo "Brewery: ".$brewery;
echo "<br>";
echo "Prod date: ".$prodDate;
echo "<br>";
echo "Option: ".$option;
echo "<br>";

/**
 * 
 * Handles adding images to temp_dir of server
 * 
 */
$target_dir = "";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["file"]["tmp_name"]);
  if($check !== false) {
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
  } else {
    echo "File is not an image.";
  }
}


$newName=$beerName.".jpg";//makes filename
$newName=strtolower(str_replace('_','',$newName));
rename($target_file, $newName);//renames image

$breweryNonSpaces=str_replace('_','',$brewery);//changes all _ to nothing


/**
 * 
 * Adds new brewery in database and creates folder with corresponding name
 * 
 */

if (is_dir('resources/img/'.$breweryNonSpaces)==false) {
  mkdir('resources/img/'.$breweryNonSpaces); //creates folder

  $sql = "SELECT * FROM 'breweries' WHERE `name` = '{$brewery}";
  $result = $conn->query($sql);
  
  if($result==false)
  {
    $sql = "INSERT INTO `breweries`(`id`, `name`) VALUES (null,'{$brewery}')";
    if($devMode==0){$result = $conn->query($sql);}
  }
}
rename( $newName,'resources/img/'.$breweryNonSpaces."/".$newName);//moves file to final dir 


$link = 'resources/img/'.$breweryNonSpaces."/".$newName;


if ($checkIfExist==false) { //if beer doesn't exist in database, creates it
  $sql = "INSERT INTO `beers`(`beer_name`, `country`, `brewery`, `production_date`, `img_src`) 
  VALUES ('{$beerName}','{$country}','{$brewery}','{$prodDate}','{$link}')";
  if (!$result = $conn->query($sql)) {
    echo "Error: ". $conn->error;
  }
  
}
else {
  echo "This beer already exist!<br>";
}
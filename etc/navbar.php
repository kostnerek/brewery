<?php 
    include('config.php');
    
    function setDepth($v) {
        $d="";
        for ($i=0; $i<$v;$i++) {
            $d.="../";
        }
        return $d;
    }

    function determineSlash($path) 
    {
        if (strpos($path, '/') != 0) {
            return substr_count($path,'/');
        }
        else {
            return substr_count($path,'\\');
        }
    }

    $posStart = strpos(getcwd(), $rootDir);

    $path = substr(getcwd(), $posStart);
    $depthCount = determineSlash($path);

    $depth = setDepth($depthCount);

    
    /* var_dump($path);
    var_dump($depthCount);
    var_dump(getcwd()); */

?>

<head>
    <link rel="stylesheet" type="text/css" href="<?php echo $depth?>resources/css/navbar.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

    <div class="navbar" role="group" aria-label="Basic example"> 
        <div class="nav-btn system" id="el" href="<?php echo $depth?>system/system.php">System
            <ol class="list">
                <li id="li1" class="list-element"><a href="<?php echo $depth?>system/import/import.php">Import</a></li>
                <li id="li1" class="list-element"><a href="<?php echo $depth?>system/export/export.php">Export</a></li>
                <li id="li1" class="list-element"><a href="<?php echo $depth?>system/settings/settings.php">Settings</a></li>
            </ol>
        </div>

        <a class="nav-btn" href="<?php echo $depth?>list/list.php">List</a>
        <a class="nav-btn" href="<?php echo $depth?>brewery/brewery.php">Brewery</a>
        <a class="nav-btn" href="<?php echo $depth?>stats/stats.php?select=beers">Stats</a>
        <a class="nav-btn fa logout" href="<?php echo $depth?>logout.php">&#xf08b;</a>
    </div>

<!-- 

    system
    import
    export
    settings
    list

 -->
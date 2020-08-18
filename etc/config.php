<?php


if((string)getcwd()[0] === "C") {
    $server   = 'localhost';
    $db       = 'brewery';
    $user     = 'root';
    $password = '';
    $rootDir  = 'brewery';
}
else {
    $server   = 'hosting2024247.online.pro';
    $db       = '00388586_brewery';
    $user     = '00388586_brewery';
    $password = '!Pastwisko37';
    $rootDir  = 'public_html';
}

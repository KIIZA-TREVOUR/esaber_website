<?php
$hostName = "localhost";
$userName = "root";
$password = "";
$dbName ="Isaber";

$conn = new mysqli($hostName, $userName, $password, $dbName);
if(!$conn){
    die('connection_eror');
} 
?>
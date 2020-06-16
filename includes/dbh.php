<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . "/config.php";
$conn = mysqli_connect($config["mysql_hostname"], $config["mysql_username"], $config["mysql_password"], $config["mysql_database"]);
?>

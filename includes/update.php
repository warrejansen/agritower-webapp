<?php
session_start();
include "./dbh.php";

$name = $_POST['name'];
$status = $_POST['status'];

$statusParsed = 0;

if ($status == 'false') {
  $statusParsed = 1;
} else {
  $statusParsed = 0;
}

$sql = "UPDATE `outputs` SET `status`=$status WHERE `name` = '$name'";
$result = mysqli_query($conn, $sql);

echo 'is geupdarted'.$status.$statusParsed.$name;

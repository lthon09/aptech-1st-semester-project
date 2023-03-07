<?php
require_once('../connect.php');
$id = $_GET['id'];
$sql = "Select  Username as Username , Email as Email , Administrator as Administrator from `members` where Username = '".$id."'";
$result = mysqli_query($con , $sql);
while($row = mysqli_fetch_array($result)){
    $data[] = array(
    'Username' => $row['Username'],
    'Email' => $row['Email'],
    'Administrator' => $row['Administrator'],
);
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
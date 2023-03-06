<?php
require_once('../connect.php');
$id = $_GET['id'];
$sql = "Select * from `countries` where  ID = '".$id."'";
$result = mysqli_query($con , $sql);
while($row = mysqli_fetch_array($result)){
    $data[] = array('id' =>  $row['ID'],
    'name' => $row['Name']
);
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

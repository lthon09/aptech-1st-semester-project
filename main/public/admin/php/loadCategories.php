<?php
require_once('../connect.php');

$sql = 'Select * from `categories` order by ID desc';
$result = mysqli_query($con , $sql);
while($row = mysqli_fetch_array($result)){
    $data[] = array('id' =>  $row['ID'],
    'name' => $row['Name']
);
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

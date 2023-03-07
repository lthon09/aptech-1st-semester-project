<?php
require_once('../connect.php');

$sql = 'Select * from `members`';
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

<?php
require_once('../connect.php');
$id = $_GET['id'];
$sql = "SELECT  t.ID as ID,
tu.ID as Name , 
t.Author as Author,
t.Content as Content,
t.Rating as Rating
FROM `reviews` t 
LEFT JOIN tours tu on t.Tour = tu.ID where t.ID = '".$id."'";

$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
    $data[] = array(
        'id' =>  $row['ID'],
        'name' => $row['Name'],
        'Author' => $row['Author'],
        'Content' => $row['Content'],
        'Rating' => $row['Rating'],
    );
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

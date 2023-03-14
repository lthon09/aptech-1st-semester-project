<?php
require_once('../connect.php');

$sql = "SELECT  t.ID as ID,
t.Name as Name , 
t.ShortDescription as ShortDescription,
t.LongDescription as LongDescription,
t.DetailedInformations as DetailedInformations,
t.Price as Price,
t.Sale as Sale,
t.Avatar as Avatar,
ca.Name as NameCategory,
co.Name as NameCoutries,
t.Hot as Hot
FROM `tours` t 
LEFT JOIN categories ca on t.Category = ca.ID
LEFT JOIN countries co on t.Country = co.ID";

$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result)) {
    $data[] = array(
        'id' =>  $row['ID'],
        'name' => $row['Name'],
        'ShortDescription' => $row['ShortDescription'],
        'LongDescription' => $row['LongDescription'],
        'DetailedInformations' => $row['DetailedInformations'],
        'Price' => $row['Price'],
        'Sale' => $row['Sale'],
        'NameCoutries' => $row['NameCoutries'],
        'NameCategory' => $row['NameCategory'],
        'Avatar' => $row['Avatar'],
        'Hot' => $row['Hot'],
    );
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

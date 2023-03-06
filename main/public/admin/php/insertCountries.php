<?php
  require_once('../connect.php');
  include '../../../global.php';

$id = generate_id(IDS["lengths"]["general"], "countries");
    $name = $_POST['name'];
    $sql = "Insert into `countries` (ID, Name) 
        Values ('$id', '$name') 
        ";
    $result = mysqli_query($con, $sql);
    if($result){
      echo 'ok';
    }else{
      echo 'error';
    }
?>